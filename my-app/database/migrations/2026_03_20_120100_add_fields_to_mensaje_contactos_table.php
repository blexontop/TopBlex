<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mensaje_contactos', function (Blueprint $table) {
            if (!Schema::hasColumn('mensaje_contactos', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('mensaje_contactos', 'nombre')) {
                $table->string('nombre')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('mensaje_contactos', 'email')) {
                $table->string('email')->nullable()->after('nombre');
            }
            if (!Schema::hasColumn('mensaje_contactos', 'asunto')) {
                $table->string('asunto')->nullable()->after('email');
            }
            if (!Schema::hasColumn('mensaje_contactos', 'mensaje')) {
                $table->text('mensaje')->nullable()->after('asunto');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mensaje_contactos', function (Blueprint $table) {
            if (Schema::hasColumn('mensaje_contactos', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }

            $drop = [];
            foreach (['nombre', 'email', 'asunto', 'mensaje'] as $column) {
                if (Schema::hasColumn('mensaje_contactos', $column)) {
                    $drop[] = $column;
                }
            }

            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
