<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('item_pedidos', function (Blueprint $table) {
            if (!Schema::hasColumn('item_pedidos', 'pedido_id')) {
                $table->foreignId('pedido_id')->nullable()->after('id')->constrained('pedidos')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('item_pedidos', 'producto_id')) {
                $table->foreignId('producto_id')->nullable()->after('pedido_id')->constrained('productos')->nullOnDelete();
            }
            if (!Schema::hasColumn('item_pedidos', 'nombre_producto')) {
                $table->string('nombre_producto')->nullable()->after('producto_id');
            }
            if (!Schema::hasColumn('item_pedidos', 'precio_unitario')) {
                $table->decimal('precio_unitario', 10, 2)->default(0)->after('nombre_producto');
            }
            if (!Schema::hasColumn('item_pedidos', 'cantidad')) {
                $table->integer('cantidad')->default(1)->after('precio_unitario');
            }
            if (!Schema::hasColumn('item_pedidos', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0)->after('cantidad');
            }
        });
    }

    public function down(): void
    {
        Schema::table('item_pedidos', function (Blueprint $table) {
            if (Schema::hasColumn('item_pedidos', 'pedido_id')) {
                $table->dropConstrainedForeignId('pedido_id');
            }
            if (Schema::hasColumn('item_pedidos', 'producto_id')) {
                $table->dropConstrainedForeignId('producto_id');
            }

            $drop = [];
            foreach (['nombre_producto', 'precio_unitario', 'cantidad', 'subtotal'] as $column) {
                if (Schema::hasColumn('item_pedidos', $column)) {
                    $drop[] = $column;
                }
            }

            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
