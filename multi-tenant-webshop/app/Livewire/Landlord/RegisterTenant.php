<?php

namespace App\Livewire\Landlord;

use App\Models\Landlord\Domain;
use App\Models\Landlord\Tenant;
use App\Models\Tenant\User;
use App\Services\TenantManager;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class RegisterTenant extends Component
{
    public string $sessionId = '';

    public bool $isPaid = false;

    public string $errorMessage = '';

    // Form fields
    public string $shop_name = '';

    public string $subdomain = '';

    public string $admin_name = '';

    public string $admin_email = '';

    public string $admin_password = '';

    public function mount()
    {
        $this->sessionId = request()->query('session_id', '');

        if (empty($this->sessionId)) {
            if (app()->environment('local')) {
                $this->isPaid = true; // Allow testing directly in local dev

                return;
            }
            $this->errorMessage = 'Geen betalingssessie gevonden. Betaal eerst het abonnement.';

            return;
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($this->sessionId);

            if ($session->payment_status === 'paid') {
                $this->isPaid = true;
            } else {
                $this->errorMessage = 'De betaling voor deze sessie is nog niet voltooid.';
            }
        } catch (\Exception $e) {
            if (app()->environment('local')) {
                // Bypass Stripe verification failure in local if it's a dummy session_id
                $this->isPaid = true;

                return;
            }
            $this->errorMessage = 'Fout bij het controleren van de betalingsstatus: '.$e->getMessage();
        }
    }

    protected function rules(): array
    {
        $centralDomain = config('app.central_domain', 'localhost');

        return [
            'shop_name' => 'required|string|min:3|max:100',
            'subdomain' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-z0-9-]+$/',
                function ($attribute, $value, $fail) use ($centralDomain) {
                    $domain = "{$value}.{$centralDomain}";
                    if (Domain::where('domain', $domain)->exists()) {
                        $fail('Dit subdomein is al in gebruik.');
                    }
                },
            ],
            'admin_name' => 'required|string|min:2|max:100',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'required|string|min:8',
        ];
    }

    protected array $messages = [
        'shop_name.required' => 'Vul de naam van je winkel in.',
        'subdomain.required' => 'Vul het gewenste subdomein in.',
        'subdomain.regex' => 'Het subdomein mag alleen kleine letters, cijfers en streepjes bevatten.',
        'admin_name.required' => 'Vul je naam in.',
        'admin_email.required' => 'Vul je e-mailadres in.',
        'admin_email.email' => 'Vul een geldig e-mailadres in.',
        'admin_password.required' => 'Vul een wachtwoord in.',
        'admin_password.min' => 'Het wachtwoord moet minimaal 8 tekens lang zijn.',
    ];

    public function register()
    {
        $this->validate();

        try {
            $centralDomain = config('app.central_domain', 'localhost');
            $fullDomain = "{$this->subdomain}.{$centralDomain}";
            $dbName = 'tenant_'.Str::slug($this->shop_name).'_'.Str::random(5);

            // 1. Create Tenant in Landlord DB
            $tenant = Tenant::create([
                'name' => $this->shop_name,
                'db_name' => $dbName,
                'is_active' => true,
            ]);

            // 2. Create Domain in Landlord DB
            Domain::create([
                'tenant_id' => $tenant->id,
                'domain' => $fullDomain,
                'is_primary' => true,
            ]);

            // 3. Create, migrate and seed the tenant database
            $tenantManager = app(TenantManager::class);
            $tenantManager->setTenant($tenant);

            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);

            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\Tenant\\TenantDatabaseSeeder',
                '--force' => true,
            ]);

            // 4. Update the owner user in the tenant database
            User::updateOrCreate(
                ['email' => 'owner@example.com'], // Update default seeded owner
                [
                    'name' => $this->admin_name,
                    'email' => $this->admin_email,
                    'password' => Hash::make($this->admin_password),
                    'email_verified_at' => now(),
                ]
            );

            // 5. Build Redirect URL with port if present
            $port = request()->getPort();
            $redirectDomain = $fullDomain;
            if ($port && ! in_array($port, [80, 443])) {
                $redirectDomain = "{$redirectDomain}:{$port}";
            }
            $protocol = str_contains(config('app.url'), 'https') ? 'https' : 'http';

            session()->flash('success', 'Je winkel is succesvol aangemaakt! Log in met je gegevens.');

            return redirect("{$protocol}://{$redirectDomain}/dashboard/login");

        } catch (\Exception $e) {
            session()->flash('error', 'Er is iets fout gegaan bij het aanmaken van de winkel: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.landlord.register-tenant')
            ->layout('layouts.blank', ['title' => 'Winkel Aanmaken - ShopSaaS Onboarding']);
    }
}
