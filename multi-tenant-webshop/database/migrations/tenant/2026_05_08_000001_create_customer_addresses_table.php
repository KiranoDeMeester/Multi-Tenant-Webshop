<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id')->index();
            $table->string('type')->default('shipping'); // shipping, billing
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('street');
            $table->string('house_number');
            $table->string('house_number_suffix')->nullable();
            $table->string('postal_code');
            $table->string('city');
            $table->string('country')->default('België');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
