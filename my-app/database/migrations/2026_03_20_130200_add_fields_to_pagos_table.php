<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            if (!Schema::hasColumn('pagos', 'pedido_id')) {
                $table->foreignId('pedido_id')->nullable()->after('id')->constrained('pedidos')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('pagos', 'metodo')) {
                $table->string('metodo')->default('tarjeta')->after('pedido_id');
            }
            if (!Schema::hasColumn('pagos', 'estado')) {
                $table->string('estado')->default('pendiente')->after('metodo');
            }
            if (!Schema::hasColumn('pagos', 'referencia')) {
                $table->string('referencia')->nullable()->after('estado');
            }
            if (!Schema::hasColumn('pagos', 'monto')) {
                $table->decimal('monto', 10, 2)->default(0)->after('referencia');
            }
            if (!Schema::hasColumn('pagos', 'fecha_pagado')) {
                $table->timestamp('fecha_pagado')->nullable()->after('monto');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            if (Schema::hasColumn('pagos', 'pedido_id')) {
                $table->dropConstrainedForeignId('pedido_id');
            }

            $drop = [];
            foreach (['metodo', 'estado', 'referencia', 'monto', 'fecha_pagado'] as $column) {
                if (Schema::hasColumn('pagos', $column)) {
                    $drop[] = $column;
                }
            }

            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
