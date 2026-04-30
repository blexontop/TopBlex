<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable()->after('city');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $drop = [];
            if (Schema::hasColumn('users', 'phone')) {
                $drop[] = 'phone';
            }
            if (Schema::hasColumn('users', 'city')) {
                $drop[] = 'city';
            }
            if (Schema::hasColumn('users', 'address')) {
                $drop[] = 'address';
            }
            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
