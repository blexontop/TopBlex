<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('contact_messages', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('contact_messages', 'name')) {
                $table->string('name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('contact_messages', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
            if (!Schema::hasColumn('contact_messages', 'subject')) {
                $table->string('subject')->nullable()->after('email');
            }
            if (!Schema::hasColumn('contact_messages', 'message')) {
                $table->text('message')->nullable()->after('subject');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            if (Schema::hasColumn('contact_messages', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }

            $drop = [];
            foreach (['name', 'email', 'subject', 'message'] as $column) {
                if (Schema::hasColumn('contact_messages', $column)) {
                    $drop[] = $column;
                }
            }

            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
