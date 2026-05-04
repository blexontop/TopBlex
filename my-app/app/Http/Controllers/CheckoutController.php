<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class CheckoutController extends Controller
{
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

        $request->session()->put('stripe_checkout_cart', $items->values()->all());

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

        return redirect()->away((string) $checkoutSession->url);
    }

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
            $stripe = new StripeClient($secret);
            $checkoutSession = $stripe->checkout->sessions->retrieve($sessionId, []);
        } catch (ApiErrorException $e) {
            return redirect()->route('stripe.checkout.show')->withErrors([
                'payment' => 'No se pudo validar el pago en Stripe: '.$e->getMessage(),
            ]);
        }

        if (($checkoutSession->payment_status ?? null) !== 'paid') {
            return redirect()->route('stripe.checkout.show')->withErrors([
                'payment' => 'Stripe aun no confirma el pago como completado.',
            ]);
        }

        if (Payment::where('reference', $checkoutSession->id)->exists()) {
            $request->session()->forget(['cart', 'stripe_checkout_cart']);

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

        $order = DB::transaction(function () use ($items, $total, $user, $checkoutSession) {
            $order = Order::create([
                'user_id' => $user->id,
                'code' => 'TBX-' . Str::upper(Str::random(8)),
                'status' => 'paid',
                'total' => $total,
                'currency' => 'EUR',
                'shipping_address' => trim(($user->address ?? '').' '.($user->city ?? '')),
            ]);

            foreach ($items as $item) {
                $quantity = max(1, (int) ($item['quantity'] ?? 1));
                $price = (float) ($item['price'] ?? 0);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'] ?? null,
                    'product_name' => (string) ($item['name'] ?? 'Producto'),
                    'unit_price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $price * $quantity,
                ]);
            }

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

        $request->session()->forget(['cart', 'stripe_checkout_cart']);

        return redirect()->route('account.index')->with('success', 'Pago recibido. Pedido '.$order->code.' confirmado.');
    }

    public function cancel(): RedirectResponse
    {
        return redirect()->route('cart.index')->withErrors([
            'payment' => 'Has cancelado el pago. Tu carrito sigue disponible.',
        ]);
    }

    private function cartFromSession(Request $request): Collection
    {
        return collect($request->session()->get('cart', []));
    }
}
