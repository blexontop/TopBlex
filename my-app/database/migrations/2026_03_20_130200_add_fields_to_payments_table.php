<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'order_id')) {
                $table->foreignId('order_id')->nullable()->after('id')->constrained('orders')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('payments', 'method')) {
                $table->string('method')->default('card')->after('order_id');
            }
            if (!Schema::hasColumn('payments', 'status')) {
                $table->string('status')->default('pending')->after('method');
            }
            if (!Schema::hasColumn('payments', 'reference')) {
                $table->string('reference')->nullable()->after('status');
            }
            if (!Schema::hasColumn('payments', 'amount')) {
                $table->decimal('amount', 10, 2)->default(0)->after('reference');
            }
            if (!Schema::hasColumn('payments', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'order_id')) {
                $table->dropConstrainedForeignId('order_id');
            }

            $drop = [];
            foreach (['method', 'status', 'reference', 'amount', 'paid_at'] as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $drop[] = $column;
                }
            }

            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
