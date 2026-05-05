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

        // Update the 'tenant' connection configuration dynamically
        Config::set('database.connections.tenant.database', $tenant->db_name);

        // Purge the connection to ensure the next call uses the new config
        DB::purge('tenant');

        // Reconnect to the tenant database
        DB::reconnect('tenant');

        // Set the default connection to 'tenant' for this request
        Config::set('database.default', 'tenant');

        Log::info('Database connection switched to tenant: ' . $tenant->name, [
            'tenant_id' => $tenant->id,
            'db_name' => $tenant->db_name,
        ]);
    }

    /**
     * Get the currently active tenant.
     */
    public function getTenant(): ?Tenant
    {
        return $this->currentTenant;
    }
}
