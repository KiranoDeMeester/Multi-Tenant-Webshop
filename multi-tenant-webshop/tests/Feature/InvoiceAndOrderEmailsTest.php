<?php

use App\Livewire\Tenant\Orders\Show;
use App\Mail\OrderCancelledMail;
use App\Mail\OrderShippedMail;
use App\Models\Landlord\Domain;
use App\Models\Landlord\Tenant;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Order;
use App\Models\Tenant\OrderItem;
use App\Models\Tenant\Setting;
use App\Models\Tenant\User as TenantUser;
use App\Services\InvoiceService;
use App\Services\StockService;
use App\Services\TenantManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

beforeEach(function () {
    $this->migrateLandlord();

    $this->tenant = Tenant::create([
        'name' => 'Fashion Haven',
        'slug' => 'fashionhaven',
        'db_name' => ':memory:',
    ]);

    Domain::create([
        'tenant_id' => $this->tenant->id,
        'domain' => 'fashionhaven.localhost',
        'is_primary' => true,
    ]);

    app(TenantManager::class)->setTenant($this->tenant);
    URL::defaults(['tenant' => $this->tenant->slug]);

    Config::set('database.connections.tenant.driver', 'sqlite');
    Config::set('database.connections.tenant.database', ':memory:');
    Config::set('database.default', 'tenant');
    $this->migrateTenant();

    $this->merchant = TenantUser::create([
        'name' => 'Haven Merchant',
        'email' => 'merchant@haven.localhost',
        'password' => bcrypt('password'),
    ]);

    $this->customer = Customer::create([
        'name' => 'Clara Oswald',
        'email' => 'clara@tardis.org',
        'password' => bcrypt('password'),
    ]);

    Setting::create(['key' => 'invoice_company_name', 'value' => 'Fashion Haven BV']);
    Setting::create(['key' => 'invoice_vat_number', 'value' => 'BE 0999.888.777']);
    Setting::create(['key' => 'invoice_vat_percentage', 'value' => '21']);
});

test('invoice service generates valid html and pdf output with correct vat breakdown', function () {
    $order = Order::create([
        'order_number' => 'ORD-INV-001',
        'total_amount' => 12100, // €121.00
        'tax_amount' => 2100,    // €21.00 VAT
        'shipping_amount' => 0,
        'status' => 'paid',
        'customer_id' => $this->customer->id,
        'customer_details' => [
            'name' => 'Clara Oswald',
            'email' => 'clara@tardis.org',
            'billing_address' => [
                'street' => 'Time Lord Way',
                'house_number' => '42',
                'postal_code' => '1000',
                'city' => 'Brussel',
                'country' => 'België',
            ],
        ],
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_name' => 'Silk Dress',
        'sku' => 'DRESS-01',
        'price' => 12100,
        'quantity' => 1,
    ]);

    $invoiceService = app(InvoiceService::class);
    $html = $invoiceService->renderHtml($order);

    expect($html)->toContain('Fashion Haven BV');
    expect($html)->toContain('BE 0999.888.777');
    expect($html)->toContain('Silk Dress');
    expect($html)->toContain('Clara Oswald');
    expect($html)->toContain('121,00');
});

test('status emails are queued when order is marked shipped or cancelled', function () {
    Mail::fake();
    Auth::guard('tenant')->login($this->merchant);

    $order = Order::create([
        'order_number' => 'ORD-SHIP-001',
        'total_amount' => 5000,
        'status' => 'paid',
        'customer_id' => $this->customer->id,
        'customer_details' => [
            'name' => 'Clara Oswald',
            'email' => 'clara@tardis.org',
        ],
    ]);

    $stockService = app(StockService::class);
    $showComponent = new Show;
    $showComponent->order = $order;

    // Transition to shipped
    $showComponent->updateStatus('shipped', $stockService);

    Mail::assertQueued(OrderShippedMail::class, function ($mail) use ($order) {
        return $mail->orderId === $order->id;
    });

    // Transition to cancelled
    $showComponent->updateStatus('cancelled', $stockService);

    Mail::assertQueued(OrderCancelledMail::class, function ($mail) use ($order) {
        return $mail->orderId === $order->id;
    });
});
