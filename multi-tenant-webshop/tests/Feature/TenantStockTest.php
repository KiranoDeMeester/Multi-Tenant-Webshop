<?php

use App\Models\Landlord\Tenant;
use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use App\Models\Tenant\ProductVariation;
use App\Services\TenantManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

// uses(RefreshDatabase::class);

beforeEach(function () {
    // Setup tenant connection to use sqlite in-memory
    Config::set('database.connections.tenant.driver', 'sqlite');
    Config::set('database.connections.tenant.database', ':memory:');
    DB::purge('tenant');
    DB::reconnect('tenant');

    // Migrate landlord tables
    $this->migrateLandlord();

    // Create a real tenant record to satisfy TenantManager
    $tenant = Tenant::create([
        'name' => 'Stock Shop',
        'db_name' => ':memory:',
    ]);
    app(TenantManager::class)->setTenant($tenant);

    // Migrate tenant tables
    $this->artisan('migrate', [
        '--database' => 'tenant',
        '--path' => 'database/migrations/tenant',
        '--realpath' => true,
    ]);
});

test('can manage stock for a simple product', function () {
    $category = Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Charger',
        'slug' => 'charger',
        'sku' => 'CHG-001',
        'price' => 10.00,
        'stock' => 10,
    ]);

    expect($product->total_stock)->toBe(10);
    expect($product->is_in_stock)->toBeTrue();

    $product->decrementStock(3);
    expect($product->fresh()->stock)->toBe(7);

    $product->incrementStock(5);
    expect($product->fresh()->stock)->toBe(12);
});

test('cannot decrement simple product stock below zero', function () {
    $category = Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Charger',
        'slug' => 'charger',
        'sku' => 'CHG-002',
        'price' => 10.00,
        'stock' => 2,
    ]);

    $product->decrementStock(3);
})->throws(Exception::class, 'Onvoldoende voorraad voor product: Charger');

test('aggregate stock works for products with variations', function () {
    $category = Category::create(['name' => 'Clothing', 'slug' => 'clothing']);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'T-Shirt',
        'slug' => 't-shirt',
        'sku' => 'TSHIRT-STOCK',
        'price' => 20.00,
        'stock' => 100, // Should be ignored if variations exist
    ]);

    $var1 = ProductVariation::create([
        'product_id' => $product->id,
        'sku' => 'VAR-RED',
        'stock' => 5,
    ]);

    $var2 = ProductVariation::create([
        'product_id' => $product->id,
        'sku' => 'VAR-BLUE',
        'stock' => 10,
    ]);

    expect($product->total_stock)->toBe(15);
    expect($product->is_in_stock)->toBeTrue();

    $var1->decrementStock(2);
    expect($product->fresh()->total_stock)->toBe(13);
});

test('cannot decrement variation stock below zero', function () {
    $category = Category::create(['name' => 'Clothing', 'slug' => 'clothing']);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'T-Shirt',
        'slug' => 't-shirt',
        'sku' => 'TSHIRT-VAR-BASE',
        'price' => 20.00,
    ]);

    $variation = ProductVariation::create([
        'product_id' => $product->id,
        'sku' => 'VAR-RED',
        'stock' => 1,
    ]);

    $variation->decrementStock(2);
})->throws(Exception::class, 'Onvoldoende voorraad voor variatie: VAR-RED');
