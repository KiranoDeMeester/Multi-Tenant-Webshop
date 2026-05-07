<?php

namespace App\Console\Commands;

use App\Models\Landlord\Tenant;
use App\Services\TenantManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup-demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup a demo shop with test data and a test customer.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Demo Setup...');

        // 1. Seed Landlord
        $this->info('Seeding Landlord database...');
        Artisan::call('db:seed', ['--class' => 'Database\Seeders\Landlord\LandlordSeeder']);

        // 2. Get Demo Tenant
        $tenant = Tenant::where('name', 'Demo Shop')->first();

        if (!$tenant) {
            $this->error('Demo Shop tenant not found!');
            return 1;
        }

        // 3. Setup Tenant Context
        $this->info('Switching to Tenant context: ' . $tenant->name);
        
        // Delete existing sqlite file to ensure fresh migrations
        $dbPath = database_path('tenants/' . $tenant->db_name . '.sqlite');
        if (file_exists($dbPath)) {
            unlink($dbPath);
        }

        app(TenantManager::class)->setTenant($tenant);

        // 4. Migrate Tenant
        $this->info('Migrating Tenant database...');
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);

        // 5. Seed Tenant
        $this->info('Seeding Tenant database...');
        Artisan::call('db:seed', [
            '--class' => 'Database\Seeders\Tenant\TenantDatabaseSeeder',
        ]);

        $this->success('Demo Setup Complete!');
        $this->info('------------------------------------------');
        $this->info('1. PLATFORM ADMIN (Manage Webshops)');
        $this->info('   URL: http://localhost:8000/login');
        $this->info('   Login: admin@example.com / password');
        $this->info('------------------------------------------');
        $this->info('2. SHOP STOREFRONT (Customer View)');
        $this->info('   URL: http://demo-shop.localhost:8000');
        $this->info('   Login: test@example.com / password');
        $this->info('------------------------------------------');
        $this->info('3. SHOP DASHBOARD (Merchant View)');
        $this->info('   URL: http://demo-shop.localhost:8000/dashboard');
        $this->info('   Login: owner@example.com / password');
        $this->info('------------------------------------------');

        return 0;
    }

    /**
     * Custom success method for better output.
     */
    protected function success($message)
    {
        $this->output->writeln("<info>✔</info> $message");
    }
}
