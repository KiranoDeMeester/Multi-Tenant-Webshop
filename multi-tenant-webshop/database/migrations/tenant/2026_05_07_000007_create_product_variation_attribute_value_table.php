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
        Schema::create('product_variation_attribute_value', function (Blueprint $table) {
            $table->foreignUuid('product_variation_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('attribute_value_id')->constrained()->cascadeOnDelete();
            $table->primary(['product_variation_id', 'attribute_value_id'], 'pv_av_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variation_attribute_value');
    }
};
