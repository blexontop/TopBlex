<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmedMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

// Controlador del pago con Stripe: resumen, iniciar pago, confirmarlo y registrar el pedido.
class CheckoutController extends Controller
{
    // Muestra el resumen del carrito antes de pagar.
    public function show(Request $request): View|RedirectResponse
    {
        $items = $this->cartFromSession($request);

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors([
                'payment' => 'Tu carrito esta vacio.',
            ]);
        }

        $total = (float) $items->sum(function (array $item): float {
            return (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 1);
        });

        return view('payments.stripe-checkout', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    // Crea la sesión de pago en Stripe y redirige al usuario a la página segura de Stripe.
    public function createSession(Request $request): RedirectResponse
    {
        $items = $this->cartFromSession($request);

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors([
                'payment' => 'Tu carrito esta vacio.',
            ]);
        }

        $secret = (string) config('services.stripe.secret');
        if ($secret === '') {
            return redirect()->route('stripe.checkout.show')->withErrors([
                'payment' => 'Falta configurar STRIPE_SECRET en el entorno.',
            ]);
        }

        // Guarda una copia del carrito para recuperarlo al volver de Stripe.
        $request->session()->put('stripe_checkout_cart', $items->values()->all());

        // Convierte cada producto del carrito al formato que pide Stripe (precio en céntimos).
        $lineItems = $items->map(function (array $item): array {
            $priceInCents = max(1, (int) round(((float) ($item['price'] ?? 0)) * 100));

            return [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => (string) ($item['name'] ?? 'Producto'),
                    ],
                    'unit_amount' => $priceInCents,
                ],
                'quantity' => max(1, (int) ($item['quantity'] ?? 1)),
            ];
        })->values()->all();

        try {
            $stripe = new StripeClient($secret);

            // Crea la sesión de pago indicando a dónde volver si paga (success) o cancela (cancel).
            $checkoutSession = $stripe->checkout->sessions->create([
                'mode' => 'payment',
                'line_items' => $lineItems,
                'success_url' => route('stripe.checkout.success', [], true).'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.checkout.cancel', [], true),
                'customer_email' => $request->user()?->email,
                'metadata' => [
                    'user_id' => (string) $request->user()->id,
                ],
            ]);
        } catch (ApiErrorException $e) {
            return redirect()->route('stripe.checkout.show')->withErrors([
                'payment' => 'No se pudo iniciar el pago con Stripe: '.$e->getMessage(),
            ]);
        }

        // Redirige al usuario a la pasarela de Stripe para que introduzca su tarjeta.
        return redirect()->away((string) $checkoutSession->url);
    }

    // Stripe devuelve al usuario aquí tras pagar: verifica el pago y registra el pedido.
    public function success(Request $request): RedirectResponse
    {
        $sessionId = (string) $request->query('session_id', '');
        if ($sessionId === '') {
            return redirect()->route('stripe.checkout.show')->withErrors([
                'payment' => 'No se recibio la sesion de pago de Stripe.',
            ]);
        }

        $secret = (string) config('services.stripe.secret');
        if ($secret === '') {
            return redirect()->route('stripe.checkout.show')->withErrors([
                'payment' => 'Falta configurar STRIPE_SECRET en el entorno.',
            ]);
        }

        try {
            // Pregunta a Stripe por la sesión de pago usando su ID.
            $stripe = new StripeClient($secret);
            $checkoutSession = $stripe->checkout->sessions->retrieve($sessionId, []);
        } catch (ApiErrorException $e) {
            return redirect()->route('stripe.checkout.show')->withErrors([
                'payment' => 'No se pudo validar el pago en Stripe: '.$e->getMessage(),
            ]);
        }

        // SEGURIDAD: solo continúa si Stripe confirma que el pago está 'paid' (pagado).
        if (($checkoutSession->payment_status ?? null) !== 'paid') {
            return redirect()->route('stripe.checkout.show')->withErrors([
                'payment' => 'Stripe aun no confirma el pago como completado.',
            ]);
        }

        // IDEMPOTENCIA: si ya existe un pago con esta referencia, no se duplica el pedido.
        if (Payment::where('reference', $checkoutSession->id)->exists()) {
            $order = Order::whereHas('payments', function ($query) use ($checkoutSession) {
                $query->where('reference', $checkoutSession->id);
            })->first();

            if ($order && $order->user_id === $request->user()->id) {
                $request->session()->forget(['cart', 'stripe_checkout_cart']);
                return redirect()->route('stripe.checkout.success.page', $order)->with('success', 'Pago confirmado.');
            }

            return redirect()->route('account.index')->with('success', 'Pago confirmado. El pedido ya fue registrado.');
        }

        $items = collect($request->session()->get('stripe_checkout_cart', $request->session()->get('cart', [])));
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors([
                'payment' => 'No encontramos el carrito para registrar tu pedido.',
            ]);
        }

        $user = $request->user();
        $total = (float) $items->sum(function (array $item): float {
            return (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 1);
        });

        // TRANSACCIÓN: crea el pedido, sus líneas y el pago todo junto (o nada si algo falla).
        $order = DB::transaction(function () use ($items, $total, $user, $checkoutSession) {
            // Cabecera del pedido.
            $order = Order::create([
                'user_id' => $user->id,
                'code' => 'TBX-' . Str::upper(Str::random(8)),
                'status' => 'paid',
                'total' => $total,
                'currency' => 'EUR',
                'shipping_address' => trim(($user->address ?? '').' '.($user->city ?? '')),
            ]);

            // Una línea por cada producto del carrito (guarda también la talla).
            foreach ($items as $item) {
                $quantity = max(1, (int) ($item['quantity'] ?? 1));
                $price = (float) ($item['price'] ?? 0);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'] ?? null,
                    'product_name' => (string) ($item['name'] ?? 'Producto'),
                    'size' => $item['size'] ?? null,
                    'unit_price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $price * $quantity,
                ]);
            }

            // Registro del pago, con la referencia que devuelve Stripe.
            Payment::create([
                'order_id' => $order->id,
                'method' => 'stripe',
                'status' => 'paid',
                'reference' => (string) $checkoutSession->id,
                'amount' => $total,
                'paid_at' => now(),
            ]);

            return $order;
        });

        // Vacía el carrito porque el pedido ya está registrado.
        $request->session()->forget(['cart', 'stripe_checkout_cart']);

        // Envía el correo con el comprobante del pago al comprador.
        try {
            Mail::to($order->user->email)->send(new OrderConfirmedMail($order));
        } catch (\Exception $e) {
            \Log::error('Error enviando correo de confirmación: ' . $e->getMessage());
        }

        return redirect()->route('stripe.checkout.success.page', $order)->with('success', 'Pedido ' . $order->code . ' confirmado.');
    }

    // Página de "pedido confirmado". Comprueba que el pedido pertenece al usuario que lo ve.
    public function successPage(Order $order): View|RedirectResponse
    {
        // Verificar que el usuario autenticado sea el propietario del pedido
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('home')->withErrors([
                'payment' => 'No tienes acceso a este pedido.',
            ]);
        }

        return view('payments.success', [
            'order' => $order->load('items', 'user'),
        ]);
    }

    // Si el usuario cancela el pago en Stripe, vuelve al carrito.
    public function cancel(): RedirectResponse
    {
        return redirect()->route('cart.index')->withErrors([
            'payment' => 'Has cancelado el pago. Tu carrito sigue disponible.',
        ]);
    }

    // Devuelve el carrito guardado en la sesión.
    private function cartFromSession(Request $request): Collection
    {
        return collect($request->session()->get('cart', []));
    }
}
