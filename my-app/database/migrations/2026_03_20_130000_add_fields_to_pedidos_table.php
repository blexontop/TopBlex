<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            if (!Schema::hasColumn('pedidos', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('pedidos', 'codigo')) {
                $table->string('codigo')->nullable()->unique()->after('user_id');
            }
            if (!Schema::hasColumn('pedidos', 'estado')) {
                $table->string('estado')->default('pendiente')->after('codigo');
            }
            if (!Schema::hasColumn('pedidos', 'total')) {
                $table->decimal('total', 10, 2)->default(0)->after('estado');
            }
            if (!Schema::hasColumn('pedidos', 'moneda')) {
                $table->string('moneda', 10)->default('EUR')->after('total');
            }
            if (!Schema::hasColumn('pedidos', 'direccion_envio')) {
                $table->text('direccion_envio')->nullable()->after('moneda');
            }
            if (!Schema::hasColumn('pedidos', 'notas')) {
                $table->text('notas')->nullable()->after('direccion_envio');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            if (Schema::hasColumn('pedidos', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }

            $drop = [];
            foreach (['codigo', 'estado', 'total', 'moneda', 'direccion_envio', 'notas'] as $column) {
                if (Schema::hasColumn('pedidos', $column)) {
                    $drop[] = $column;
                }
            }

            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
