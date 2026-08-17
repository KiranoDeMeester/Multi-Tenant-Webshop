<?php

use App\Models\Landlord\Domain;
use App\Models\Landlord\Tenant;
use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use App\Models\Tenant\ProductVariation;
use App\Models\Tenant\User as TenantUser;
use App\Services\TenantManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    $this->migrateLandlord();

    $this->tenant = Tenant::create([
        'name' => 'Fashion Store',
        'slug' => 'fashionstore',
        'db_name' => ':memory:',
    ]);

    Domain::create([
        'tenant_id' => $this->tenant->id,
        'domain' => 'fashionstore.localhost',
        'is_primary' => true,
    ]);

    app(TenantManager::class)->setTenant($this->tenant);
    URL::defaults(['tenant' => $this->tenant->slug]);

    Config::set('database.connections.tenant.driver', 'sqlite');
    Config::set('database.connections.tenant.database', ':memory:');
    $this->migrateTenant();

    $this->merchant = TenantUser::create([
        'name' => 'Fashion Merchant',
        'email' => 'merchant@fashion.localhost',
        'password' => bcrypt('password'),
    ]);

    $this->category = Category::create([
        'name' => 'Kleding',
        'slug' => 'kleding',
    ]);
});

test('merchant can create a product with variations', function () {
    Auth::guard('tenant')->login($this->merchant);

    Livewire::test(\App\Livewire\Tenant\Products\Create::class)
        ->set('name', 'Designer Hoodie')
        ->set('sku', 'HOODIE-001')
        ->set('price', 79.99)
        ->set('category_id', $this->category->id)
        ->set('has_variations', true)
        ->set('variations', [
            [
                'attribute_name' => 'Maat',
                'attribute_value' => 'Medium',
                'sku' => 'HOODIE-M',
                'price' => 79.99,
                'stock' => 15,
            ],
            [
                'attribute_name' => 'Maat',
                'attribute_value' => 'Large',
                'sku' => 'HOODIE-L',
                'price' => 84.99,
                'stock' => 8,
            ],
        ])
        ->call('save')
        ->assertHasNoErrors();

    $product = Product::where('sku', 'HOODIE-001')->first();
    expect($product)->not->toBeNull();
    expect($product->variations()->count())->toBe(2);
    expect($product->total_stock)->toBe(23);
});

test('storefront customer can select variation and add to cart with correct price', function () {
    $product = Product::create([
        'name' => 'Classic Tee',
        'slug' => 'classic-tee',
        'sku' => 'TEE-001',
        'price' => 20.00,
        'stock' => 0,
        'category_id' => $this->category->id,
    ]);

    $attr = \App\Models\Tenant\Attribute::create(['name' => 'Maat']);
    $valM = \App\Models\Tenant\AttributeValue::create(['attribute_id' => $attr->id, 'value' => 'Medium']);
    $valXL = \App\Models\Tenant\AttributeValue::create(['attribute_id' => $attr->id, 'value' => 'XL']);

    $varM = ProductVariation::create([
        'product_id' => $product->id,
        'sku' => 'TEE-M',
        'price' => 20.00,
        'stock' => 10,
    ]);
    $varM->attributeValues()->sync([$valM->id]);

    $varXL = ProductVariation::create([
        'product_id' => $product->id,
        'sku' => 'TEE-XL',
        'price' => 25.00,
        'stock' => 5,
    ]);
    $varXL->attributeValues()->sync([$valXL->id]);

    Livewire::test(\App\Livewire\Storefront\Products\Show::class, ['slug' => 'classic-tee'])
        ->call('selectVariation', $varXL->id)
        ->assertSet('selectedVariationId', $varXL->id)
        ->call('addToCart')
        ->assertDispatched('product-added-to-cart');

    $cartItems = app(\App\Services\CartService::class)->getItems();
    $cartKey = "{$product->id}_{$varXL->id}";
    expect(isset($cartItems[$cartKey]))->toBeTrue();
    expect($cartItems[$cartKey]['price'])->toBe(25.00);
    expect($cartItems[$cartKey]['sku'])->toBe('TEE-XL');
});
