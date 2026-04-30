<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderDemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->where('email', 'test@example.com')->first();
        if (!$user) {
            return;
        }

        $products = Product::query()->orderBy('id')->take(2)->get();
        if ($products->isEmpty()) {
            return;
        }

        $total = (float) $products->sum('price');

        $pedido = Order::updateOrCreate(
            ['code' => 'TBX-DEMO-0001'],
            [
                'user_id' => $user->id,
                'status' => 'entregado',
                'total' => $total,
                'currency' => 'EUR',
                'shipping_address' => trim(($user->address ?? 'Calle Demo 1') . ' ' . ($user->city ?? 'Madrid')),
                'notes' => 'Order de ejemplo para mostrar historial de cuenta.',
            ]
        );

        OrderItem::query()->where('order_id', $pedido->id)->delete();

        foreach ($products as $producto) {
            OrderItem::create([
                'order_id' => $pedido->id,
                'product_id' => $producto->id,
                'product_name' => $producto->name,
                'unit_price' => (float) $producto->price,
                'quantity' => 1,
                'subtotal' => (float) $producto->price,
            ]);
        }

        Payment::updateOrCreate(
            ['order_id' => $pedido->id],
            [
                'method' => 'card',
                'status' => 'pagado',
                'reference' => 'PAY-DEMO-0001',
                'amount' => $total,
                'paid_at' => now()->subDays(2),
            ]
        );
    }
}
