<?php

use App\Actions\Tenant\PrepareCheckoutAction;
use App\Models\Landlord\Tenant;
use App\Models\Tenant\Category;
use App\Models\Tenant\Order;
use App\Models\Tenant\Product;
use App\Services\CartService;
use App\Services\StripeService;
use App\Services\TenantManager;
use Stripe\Checkout\Session;

beforeEach(function () {
    $this->migrateLandlord();

    // 1. Create the tenant record first
    $this->tenant = Tenant::create([
        'name' => 'Test Shop',
        'db_name' => ':memory:',
        'stripe_account_id' => 'acct_test123',
    ]);

    // 2. Set the tenant (this switches connection to :memory:)
    app(TenantManager::class)->setTenant($this->tenant);

    // 3. Migrate the tenant database (now that we are connected to the right :memory:)
    $this->migrateTenant();

    // 4. Create data
    $this->category = Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
    $this->product = Product::create([
        'name' => 'iPhone',
        'slug' => 'iphone',
        'sku' => 'IPHONE-001',
        'price' => 999.00,
        'category_id' => $this->category->id,
    ]);
});

test('successful checkout initiation creates order and redirects to stripe', function () {
    // 1. Setup Cart
    $cartService = mock(CartService::class);
    $cartService->shouldReceive('getItems')->andReturn([
        [
            'id' => $this->product->id,
            'name' => 'iPhone',
            'price' => 999.00,
            'quantity' => 1,
            'sku' => 'IPHONE-001',
        ],
    ]);
    $cartService->shouldReceive('getTotal')->andReturn(999.00);
    $cartService->shouldReceive('getShippingFee')->andReturn(0.00);
    $this->app->instance(CartService::class, $cartService);

    // 2. Mock Stripe Session
    $stripeSession = (object) [
        'id' => 'cs_test_123',
        'url' => 'https://checkout.stripe.com/test',
    ];

    $stripeService = mock(StripeService::class);
    $stripeService->shouldReceive('createCheckoutSession')
        ->once()
        ->andReturn($stripeSession);
    $this->app->instance(StripeService::class, $stripeService);

    // 3. Execute Action
    $action = app(PrepareCheckoutAction::class);
    $url = $action->execute('Please deliver carefully');

    // 4. Assertions
    expect($url)->toBe('https://checkout.stripe.com/test');

    $order = Order::first();
    expect($order)->not->toBeNull();
    expect($order->total_amount)->toBe(99900); // Stored in cents
    expect($order->stripe_session_id)->toBe('cs_test_123');
    expect($order->items)->toHaveCount(1);
    expect($order->items->first()->product_name)->toBe('iPhone');
});

test('checkout fails if cart is empty', function () {
    $cartService = mock(CartService::class);
    $cartService->shouldReceive('getItems')->andReturn([]);
    $this->app->instance(CartService::class, $cartService);

    $action = app(PrepareCheckoutAction::class);

    expect(fn () => $action->execute())->toThrow(Exception::class, 'Winkelwagen is leeg.');
});

test('checkout fails if stripe is not configured', function () {
    $this->tenant->update(['stripe_account_id' => null]);

    $action = app(PrepareCheckoutAction::class);

    expect(fn () => $action->execute())->toThrow(Exception::class, 'Deze winkel kan momenteel geen betalingen accepteren');
});
