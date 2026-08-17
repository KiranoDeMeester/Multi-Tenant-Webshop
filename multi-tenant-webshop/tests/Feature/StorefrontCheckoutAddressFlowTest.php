<?php

use App\Livewire\Storefront\Checkout\Index;
use App\Models\Landlord\Domain;
use App\Models\Landlord\Tenant;
use App\Models\Tenant\Category;
use App\Models\Tenant\Customer;
use App\Models\Tenant\CustomerAddress;
use App\Models\Tenant\Order;
use App\Models\Tenant\Product;
use App\Services\CartService;
use App\Services\StripeService;
use App\Services\TenantManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    $this->migrateLandlord();

    $this->tenant = Tenant::create([
        'name' => 'Demo Shop',
        'slug' => 'demoshop',
        'db_name' => ':memory:',
        'stripe_account_id' => 'acct_test_123',
    ]);

    Domain::create([
        'tenant_id' => $this->tenant->id,
        'domain' => 'demoshop.localhost',
        'is_primary' => true,
    ]);

    app(TenantManager::class)->setTenant($this->tenant);
    URL::defaults(['tenant' => $this->tenant->slug]);

    Config::set('database.connections.tenant.driver', 'sqlite');
    Config::set('database.connections.tenant.database', ':memory:');
    $this->migrateTenant();

    $this->category = Category::create([
        'name' => 'Electronics',
        'slug' => 'electronics',
    ]);

    $this->product = Product::create([
        'name' => 'Wireless Speaker',
        'slug' => 'wireless-speaker',
        'sku' => 'SPEAK-001',
        'price' => 50.00,
        'stock' => 10,
        'category_id' => $this->category->id,
    ]);
});

test('guest can fill in address and proceed through checkout', function () {
    app(CartService::class)->add($this->product, 2);

    $stripeMock = Mockery::mock(StripeService::class);
    $dummySession = (object) [
        'id' => 'cs_test_dummy_checkout',
        'url' => 'https://checkout.stripe.com/pay/cs_test_dummy_checkout',
    ];
    $stripeMock->shouldReceive('createCheckoutSession')->once()->andReturn($dummySession);
    app()->instance(StripeService::class, $stripeMock);

    Livewire::test(Index::class)
        ->set('first_name', 'Emma')
        ->set('last_name', 'Watson')
        ->set('email', 'emma@example.com')
        ->set('phone', '+32 470 12 34 56')
        ->set('shipping_street', 'Antwerpsesteenweg')
        ->set('shipping_house_number', '45')
        ->set('shipping_postal_code', '9000')
        ->set('shipping_city', 'Gent')
        ->set('shipping_country', 'België')
        ->set('notes', 'Laat pakje achter bij de buren.')
        ->call('processCheckout')
        ->assertRedirect('https://checkout.stripe.com/pay/cs_test_dummy_checkout');

    $order = Order::latest()->first();
    expect($order)->not->toBeNull();
    expect($order->total_amount)->toBe(10000); // €100.00 in cents
    expect($order->customer_details['name'])->toBe('Emma Watson');
    expect($order->customer_details['email'])->toBe('emma@example.com');
    expect($order->customer_details['shipping_address']['street'])->toBe('Antwerpsesteenweg');
    expect($order->customer_details['shipping_address']['city'])->toBe('Gent');
    expect($order->notes)->toBe('Laat pakje achter bij de buren.');
});

test('logged in customer can select saved address during checkout', function () {
    $customer = Customer::create([
        'name' => 'Bruce Wayne',
        'email' => 'bruce@wayne.com',
        'password' => bcrypt('password'),
    ]);

    $savedAddress = CustomerAddress::create([
        'customer_id' => $customer->id,
        'type' => 'shipping',
        'first_name' => 'Bruce',
        'last_name' => 'Wayne',
        'street' => 'Wayne Manor Way',
        'house_number' => '1007',
        'postal_code' => '1000',
        'city' => 'Gotham',
        'country' => 'België',
    ]);

    Auth::guard('customer')->login($customer);
    app(CartService::class)->add($this->product, 1);

    $stripeMock = Mockery::mock(StripeService::class);
    $dummySession = (object) [
        'id' => 'cs_test_dummy_customer',
        'url' => 'https://checkout.stripe.com/pay/cs_test_dummy_customer',
    ];
    $stripeMock->shouldReceive('createCheckoutSession')->once()->andReturn($dummySession);
    app()->instance(StripeService::class, $stripeMock);

    Livewire::test(Index::class)
        ->assertSet('selected_address_id', $savedAddress->id)
        ->assertSet('shipping_street', 'Wayne Manor Way')
        ->call('processCheckout')
        ->assertRedirect('https://checkout.stripe.com/pay/cs_test_dummy_customer');

    $order = Order::latest()->first();
    expect($order)->not->toBeNull();
    expect($order->customer_id)->toBe($customer->id);
    expect($order->customer_details['shipping_address']['street'])->toBe('Wayne Manor Way');
});
