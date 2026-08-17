<?php

namespace Database\Seeders\Landlord;

use App\Models\Landlord\Domain;
use App\Models\Landlord\Tenant;
use App\Services\TenantManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DemoTenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenantManager = app(TenantManager::class);

        // 1. Create the Demo Tenant
        $tenant = Tenant::updateOrCreate(
            ['db_name' => 'demo_minimalist_shop'],
            [
                'name' => 'Minimalist Design Store',
            ]
        );

        // 2. Create the Domain
        Domain::updateOrCreate(
            ['domain' => 'minimalist.localhost'],
            [
                'tenant_id' => $tenant->id,
                'is_primary' => true,
            ]
        );

        // 3. Migrate and Seed the Tenant Database
        echo "Migrating and seeding tenant: {$tenant->name}...\n";

        // Delete the tenant database file if it exists to ensure a fresh start
        $dbPath = database_path('tenants/'.$tenant->db_name.'.sqlite');
        if (file_exists($dbPath)) {
            unlink($dbPath);
        }

        $tenantManager->setTenant($tenant);

        // Run migrations for the tenant
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);

        // Run the DemoShopSeeder for this tenant
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\Tenant\\DemoShopSeeder',
            '--force' => true,
        ]);

        echo "Demo tenant created successfully: http://minimalist.localhost\n";
    }
}
