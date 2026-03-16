<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->nullable()->unique();
            $table->text('descripcion')->nullable();
            $table->text('descripcion_corta')->nullable();
            $table->decimal('precio', 10, 2)->default(0);
            $table->string('sku')->nullable()->unique();
            $table->integer('stock')->default(0);
            $table->boolean('visible')->default(true);
            $table->boolean('destacado')->default(false);
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            $table->foreignId('coleccion_id')->nullable()->constrained('coleccions')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
