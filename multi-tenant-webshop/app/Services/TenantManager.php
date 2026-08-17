<?php

namespace App\Services;

use App\Models\Landlord\Tenant;
use App\Models\Tenant\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class TenantManager
{
    protected ?Tenant $currentTenant = null;

    /**
     * Set the current tenant and switch the database connection.
     */
    public function setTenant(Tenant $tenant): void
    {
        $this->currentTenant = $tenant;

        // Handle :memory: database (primarily for testing)
        if ($tenant->db_name === ':memory:') {
            $dbPath = ':memory:';
        } else {
            // Determine the path to the SQLite database file
            $dbPath = database_path('tenants/'.$tenant->db_name.'.sqlite');
            $dbDir = dirname($dbPath);

            // Ensure the directory exists
            if (! file_exists($dbDir)) {
                mkdir($dbDir, 0755, true);
            }

            // Ensure the SQLite file exists
            if (! file_exists($dbPath)) {
                touch($dbPath);
            }
        }

        // 1. Update the 'tenant' connection configuration dynamically
        Config::set('database.connections.tenant.database', $dbPath);

        // 2. Purge the connection so Laravel is forced to re-read the config next time it's used
        DB::purge('tenant');

        // 3. Set the default connection to 'tenant' for this request
        Config::set('database.default', 'tenant');

        Log::info('Database connection switched to SQLite tenant: '.$tenant->name, [
            'tenant_id' => $tenant->id,
            'db_path' => $dbPath,
        ]);

        // 4. Auto-migrate and seed tenant DB if tables are missing (on-demand bootstrapping)
        if ($tenant->db_name !== ':memory:' && ! app()->runningInConsole()) {
            try {
                if (! Schema::connection('tenant')->hasTable('settings')) {
                    Log::info('Bootstrapping empty tenant database: '.$tenant->name);

                    Artisan::call('migrate', [
                        '--database' => 'tenant',
                        '--path' => 'database/migrations/tenant',
                        '--force' => true,
                    ]);

                    // Decide seeder based on name/db_name to keep demo store distinct
                    $seeder = 'Database\\Seeders\\Tenant\\TenantDatabaseSeeder';
                    if (str_contains($tenant->db_name, 'minimalist')) {
                        $seeder = 'Database\\Seeders\\Tenant\\DemoShopSeeder';
                    }

                    Artisan::call('db:seed', [
                        '--class' => $seeder,
                        '--force' => true,
                        '--database' => 'tenant',
                    ]);

                    Log::info('Successfully bootstrapped tenant database: '.$tenant->name);
                }
            } catch (\Exception $e) {
                Log::error('Dynamic tenant bootstrap failed: '.$e->getMessage(), [
                    'tenant_name' => $tenant->name,
                ]);
            }
        }
    }

    /**
     * Get the currently active tenant.
     */
    public function getTenant(): ?Tenant
    {
        return $this->currentTenant;
    }

    /**
     * Get theme settings for the current tenant.
     */
    public function getThemeSettings(): array
    {
        if (! $this->currentTenant) {
            return [];
        }

        return Setting::pluck('value', 'key')->toArray();
    }

    /**
     * Reset the tenant manager state.
     */
    public function reset(): void
    {
        $this->currentTenant = null;
        Config::set('database.default', 'sqlite'); // Hardcoded for this app's architecture
        DB::purge('tenant');
    }
}
