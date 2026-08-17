<?php

namespace Database\Seeders\Landlord;

use App\Models\Landlord\Domain;
use App\Models\Landlord\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LandlordSeeder extends Seeder
{
    /**
     * Seed the landlord database.
     */
    public function run(): void
    {
        // 1. Create Platform Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Platform Admin',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Create Demo Tenant (Shop 1)
        $tenant = Tenant::updateOrCreate(
            ['db_name' => 'tenant_demo_shop'],
            [
                'name' => 'Demo Shop',
            ]
        );

        Domain::updateOrCreate(
            ['domain' => 'demo-shop.localhost'],
            [
                'tenant_id' => $tenant->id,
                'is_primary' => true,
            ]
        );

        // 3. Create Vintage Antique Store (Shop 2)
        $tenant3 = Tenant::updateOrCreate(
            ['db_name' => 'tenant_vintage_shop'],
            [
                'name' => 'Vintage Antique Store',
            ]
        );

        Domain::updateOrCreate(
            ['domain' => 'vintage.localhost'],
            [
                'tenant_id' => $tenant3->id,
                'is_primary' => true,
            ]
        );
    }
}
