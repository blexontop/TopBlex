<?php

namespace Database\Seeders;

use App\Models\ItemPedido;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Seeder;

class PedidoDemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->where('email', 'test@example.com')->first();
        if (!$user) {
            return;
        }

        $productos = Producto::query()->orderBy('id')->take(2)->get();
        if ($productos->isEmpty()) {
            return;
        }

        $total = (float) $productos->sum('precio');

        $pedido = Pedido::updateOrCreate(
            ['codigo' => 'TBX-DEMO-0001'],
            [
                'user_id' => $user->id,
                'estado' => 'entregado',
                'total' => $total,
                'moneda' => 'EUR',
                'direccion_envio' => trim(($user->direccion ?? 'Calle Demo 1') . ' ' . ($user->ciudad ?? 'Madrid')),
                'notas' => 'Pedido de ejemplo para mostrar historial de cuenta.',
            ]
        );

        ItemPedido::query()->where('pedido_id', $pedido->id)->delete();

        foreach ($productos as $producto) {
            ItemPedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $producto->id,
                'nombre_producto' => $producto->nombre,
                'precio_unitario' => (float) $producto->precio,
                'cantidad' => 1,
                'subtotal' => (float) $producto->precio,
            ]);
        }

        Pago::updateOrCreate(
            ['pedido_id' => $pedido->id],
            [
                'metodo' => 'tarjeta',
                'estado' => 'pagado',
                'referencia' => 'PAY-DEMO-0001',
                'monto' => $total,
                'fecha_pagado' => now()->subDays(2),
            ]
        );
    }
}
