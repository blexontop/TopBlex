<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pregunta_frecuentes', function (Blueprint $table) {
            if (!Schema::hasColumn('pregunta_frecuentes', 'pregunta')) {
                $table->string('pregunta')->nullable()->after('id');
            }
            if (!Schema::hasColumn('pregunta_frecuentes', 'respuesta')) {
                $table->text('respuesta')->nullable()->after('pregunta');
            }
            if (!Schema::hasColumn('pregunta_frecuentes', 'orden')) {
                $table->integer('orden')->default(0)->after('respuesta');
            }
            if (!Schema::hasColumn('pregunta_frecuentes', 'activo')) {
                $table->boolean('activo')->default(true)->after('orden');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pregunta_frecuentes', function (Blueprint $table) {
            $drop = [];
            foreach (['pregunta', 'respuesta', 'orden', 'activo'] as $column) {
                if (Schema::hasColumn('pregunta_frecuentes', $column)) {
                    $drop[] = $column;
                }
            }

            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
