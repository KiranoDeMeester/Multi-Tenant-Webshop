<?php

namespace App\Services;

use App\Models\Landlord\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            $dbPath = database_path('tenants/' . $tenant->db_name . '.sqlite');
            $dbDir = dirname($dbPath);

            // Ensure the directory exists
            if (!file_exists($dbDir)) {
                mkdir($dbDir, 0755, true);
            }

            // Ensure the SQLite file exists
            if (!file_exists($dbPath)) {
                touch($dbPath);
            }
        }

        // 1. Update the 'tenant' connection configuration dynamically
        Config::set('database.connections.tenant.database', $dbPath);

        // 2. Purge the connection so Laravel is forced to re-read the config next time it's used
        DB::purge('tenant');

        // 3. Set the default connection to 'tenant' for this request
        Config::set('database.default', 'tenant');

        Log::info('Database connection switched to SQLite tenant: ' . $tenant->name, [
            'tenant_id' => $tenant->id,
            'db_path' => $dbPath,
        ]);
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
        if (!$this->currentTenant) {
            return [];
        }

        return \App\Models\Tenant\Setting::pluck('value', 'key')->toArray();
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
