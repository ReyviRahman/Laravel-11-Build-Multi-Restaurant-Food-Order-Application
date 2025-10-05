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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();

            // Relasi
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();

            // Data produk
            $table->string('code')->unique()->nullable();
            $table->integer('qty')->default(0); // stok default 0
            $table->string('size')->nullable();

            $table->decimal('price', 12, 0)->default(0);
            $table->decimal('discount_price', 12, 0)->nullable();

            $table->string('image')->nullable();

            // Flags produk
            $table->boolean('most_populer')->default(false);
            $table->boolean('best_seller')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
