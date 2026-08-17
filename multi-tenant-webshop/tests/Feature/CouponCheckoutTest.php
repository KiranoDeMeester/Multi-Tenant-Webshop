<?php

use App\Livewire\Tenant\Coupons\Index;
use App\Models\Landlord\Domain;
use App\Models\Landlord\Tenant;
use App\Models\Tenant\Category;
use App\Models\Tenant\Coupon;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Product;
use App\Models\Tenant\User as TenantUser;
use App\Services\CartService;
use App\Services\TenantManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    $this->migrateLandlord();

    $this->tenant = Tenant::create([
        'name' => 'Discount Store',
        'slug' => 'discountstore',
        'db_name' => ':memory:',
        'stripe_account_id' => 'acct_test_12345',
    ]);

    Domain::create([
        'tenant_id' => $this->tenant->id,
        'domain' => 'discountstore.localhost',
        'is_primary' => true,
    ]);

    app(TenantManager::class)->setTenant($this->tenant);
    URL::defaults(['tenant' => $this->tenant->slug]);

    Config::set('database.connections.tenant.driver', 'sqlite');
    Config::set('database.connections.tenant.database', ':memory:');
    Config::set('database.default', 'tenant');
    $this->migrateTenant();

    $this->merchant = TenantUser::create([
        'name' => 'Coupon Merchant',
        'email' => 'merchant@discount.localhost',
        'password' => bcrypt('password'),
    ]);

    $this->customer = Customer::create([
        'name' => 'Amy Pond',
        'email' => 'amy@leadworth.co.uk',
        'password' => bcrypt('password'),
    ]);

    $this->category = Category::create([
        'name' => 'Fashion',
        'slug' => 'fashion',
    ]);

    $this->product = Product::create([
        'name' => 'Designer Jacket',
        'slug' => 'designer-jacket',
        'sku' => 'JACKET-01',
        'price' => 100.00,
        'stock' => 10,
        'category_id' => $this->category->id,
    ]);
});

test('merchant can create and manage coupons via dashboard', function () {
    Auth::guard('tenant')->login($this->merchant);

    Livewire::test(Index::class)
        ->set('code', 'SALE20')
        ->set('type', 'percentage')
        ->set('value', 20)
        ->set('min_order_amount', 50)
        ->set('max_uses', 100)
        ->call('save');

    expect(Coupon::where('code', 'SALE20')->exists())->toBeTrue();
    $coupon = Coupon::where('code', 'SALE20')->first();
    expect($coupon->type)->toBe('percentage');
    expect($coupon->value)->toBe(20);
    expect($coupon->min_order_amount)->toBe(5000); // 50.00 in cents
});

test('customer can apply a valid percentage coupon during checkout and receive discount', function () {
    Coupon::create([
        'code' => 'SAVE15',
        'type' => 'percentage',
        'value' => 15,
        'is_active' => true,
    ]);

    $cartService = app(CartService::class);
    $cartService->add($this->product, 1);

    Livewire::test(App\Livewire\Storefront\Checkout\Index::class)
        ->set('coupon_code', 'SAVE15')
        ->call('applyCoupon')
        ->assertSet('applied_coupon_code', 'SAVE15')
        ->assertSet('discount_amount', 1500) // 15% of €100 = €15.00 (1500 cents)
        ->assertDispatched('toast');
});

test('expired or maxed out coupon is rejected with appropriate error', function () {
    Coupon::create([
        'code' => 'EXPIRED50',
        'type' => 'percentage',
        'value' => 50,
        'expires_at' => now()->subDay(),
        'is_active' => true,
    ]);

    $cartService = app(CartService::class);
    $cartService->add($this->product, 1);

    Livewire::test(App\Livewire\Storefront\Checkout\Index::class)
        ->set('coupon_code', 'EXPIRED50')
        ->call('applyCoupon')
        ->assertSet('applied_coupon_code', null)
        ->assertSet('discount_amount', 0);
});
