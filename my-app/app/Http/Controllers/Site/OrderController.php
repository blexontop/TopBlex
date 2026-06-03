<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmedMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function confirm(Request $request)
    {
        $cart = collect($request->session()->get('cart', []));
        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('success', 'Tu carrito esta vacio.');
        }

        $user = $request->user();

        $pedido = DB::transaction(function () use ($cart, $user) {
            $total = (float) $cart->sum(function (array $item) {
                return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
            });

            $code = 'TBX-' . Str::upper(Str::random(8));

            $pedido = Order::create([
                'user_id' => $user->id,
                'code' => $code,
                'status' => 'pending',
                'total' => $total,
                'currency' => 'EUR',
                'shipping_address' => trim(($user->address ?? '') . ' ' . ($user->city ?? '')),
            ]);

            foreach ($cart as $item) {
                $quantity = (int) ($item['quantity'] ?? 1);
                $price = (float) ($item['price'] ?? 0);

                OrderItem::create([
                    'order_id' => $pedido->id,
                    'product_id' => $item['id'] ?? null,
                    'product_name' => (string) ($item['name'] ?? 'Product'),
                    'unit_price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $price * $quantity,
                    'size' => $item['size'] ?? null,
                ]);
            }

            Payment::create([
                'order_id' => $pedido->id,
                'method' => 'pending',
                'status' => 'pending',
                'reference' => 'REF-' . Str::upper(Str::random(10)),
                'amount' => $total,
            ]);

            return $pedido;
        });

        $request->session()->forget('cart');

        Mail::to($user->email)->send(new OrderConfirmedMail($pedido));

        return redirect()->route('account.index')->with('success', 'Pedido ' . $pedido->code . ' realizado con exito. Se ha enviado una confirmacion a tu correo.');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $orders = Order::query()
            ->with(['items'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }
}
