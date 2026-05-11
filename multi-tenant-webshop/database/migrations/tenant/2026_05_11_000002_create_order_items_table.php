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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('order_id')->constrained()->onDelete('cascade');
            $table->uuid('product_id')->nullable();
            $table->uuid('product_variation_id')->nullable();
            $table->string('product_name');
            $table->string('sku')->nullable();
            $table->integer('price'); // In cents at time of purchase
            $table->integer('quantity');
            $table->json('options')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('product_variation_id')->references('id')->on('product_variations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
