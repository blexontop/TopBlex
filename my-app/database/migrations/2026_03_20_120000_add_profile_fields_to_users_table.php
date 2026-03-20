<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'telefono')) {
                $table->string('telefono')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'ciudad')) {
                $table->string('ciudad')->nullable()->after('telefono');
            }
            if (!Schema::hasColumn('users', 'direccion')) {
                $table->string('direccion')->nullable()->after('ciudad');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $drop = [];
            if (Schema::hasColumn('users', 'telefono')) {
                $drop[] = 'telefono';
            }
            if (Schema::hasColumn('users', 'ciudad')) {
                $drop[] = 'ciudad';
            }
            if (Schema::hasColumn('users', 'direccion')) {
                $drop[] = 'direccion';
            }
            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
