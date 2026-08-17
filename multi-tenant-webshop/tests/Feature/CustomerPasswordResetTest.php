<?php

use App\Livewire\Storefront\Auth\ForgotPassword;
use App\Livewire\Storefront\Auth\ResetPassword;
use App\Mail\CustomerResetPasswordMail;
use App\Models\Landlord\Domain;
use App\Models\Landlord\Tenant;
use App\Models\Tenant\Customer;
use App\Services\TenantManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

beforeEach(function () {
    $this->migrateLandlord();

    $this->tenant = Tenant::create([
        'name' => 'Secure Vault',
        'slug' => 'securevault',
        'db_name' => ':memory:',
    ]);

    Domain::create([
        'tenant_id' => $this->tenant->id,
        'domain' => 'securevault.localhost',
        'is_primary' => true,
    ]);

    app(TenantManager::class)->setTenant($this->tenant);
    URL::defaults(['tenant' => $this->tenant->slug]);

    Config::set('database.connections.tenant.driver', 'sqlite');
    Config::set('database.connections.tenant.database', ':memory:');
    Config::set('database.default', 'tenant');
    $this->migrateTenant();

    $this->customer = Customer::create([
        'name' => 'Rory Williams',
        'email' => 'rory@leadworth.co.uk',
        'password' => bcrypt('oldpassword123'),
    ]);
});

test('customer can request a password reset email', function () {
    Mail::fake();

    Livewire::test(ForgotPassword::class)
        ->set('email', 'rory@leadworth.co.uk')
        ->call('sendResetLink')
        ->assertSet('sent', true);

    Mail::assertQueued(CustomerResetPasswordMail::class, function ($mail) {
        return $mail->hasTo('rory@leadworth.co.uk') && $mail->customerName === 'Rory Williams';
    });

    expect(DB::connection('tenant')->table('password_reset_tokens')->where('email', 'rory@leadworth.co.uk')->exists())->toBeTrue();
});

test('customer can reset password with valid token and is logged into customer guard', function () {
    $token = 'test-token-123456';
    DB::connection('tenant')->table('password_reset_tokens')->insert([
        'email' => 'rory@leadworth.co.uk',
        'token' => Hash::make($token),
        'created_at' => now(),
    ]);

    Livewire::test(ResetPassword::class, ['token' => $token])
        ->set('email', 'rory@leadworth.co.uk')
        ->set('password', 'newsecretpassword123')
        ->set('password_confirmation', 'newsecretpassword123')
        ->call('resetPassword')
        ->assertRedirect(route('storefront.account'));

    expect(Hash::check('newsecretpassword123', $this->customer->fresh()->password))->toBeTrue();
    expect(Auth::guard('customer')->check())->toBeTrue();
    expect(Auth::guard('customer')->id())->toBe($this->customer->id);
});
