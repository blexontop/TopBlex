<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            if (!Schema::hasColumn('faqs', 'pregunta')) {
                $table->string('pregunta')->nullable()->after('id');
            }
            if (!Schema::hasColumn('faqs', 'respuesta')) {
                $table->text('respuesta')->nullable()->after('pregunta');
            }
            if (!Schema::hasColumn('faqs', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('respuesta');
            }
            if (!Schema::hasColumn('faqs', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('sort_order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $drop = [];
            foreach (['pregunta', 'respuesta', 'sort_order', 'is_active'] as $column) {
                if (Schema::hasColumn('faqs', $column)) {
                    $drop[] = $column;
                }
            }

            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
