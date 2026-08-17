<?php

namespace App\Actions\Landlord;

use App\Models\Landlord\Tenant;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateTenantDatabaseAction
{
    /**
     * Create a new physical database for the given tenant.
     *
     * @throws Exception
     */
    public function execute(Tenant $tenant): void
    {
        $dbName = $tenant->db_name;

        // Validation: Ensure database name is safe (alphanumeric and underscores only)
        if (! preg_match('/^[a-z0-9_]+$/', $dbName)) {
            throw new Exception("Invalid database name: {$dbName}");
        }

        try {
            Log::info("Starting database provisioning for tenant: {$tenant->name}", [
                'tenant_id' => $tenant->id,
                'db_name' => $dbName,
            ]);

            // Execute raw SQL to create the database
            // Note: We use the landlord connection to create the new database
            DB::connection('landlord')->statement("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

            Log::info("Successfully created database: {$dbName}", [
                'tenant_id' => $tenant->id,
            ]);

        } catch (Exception $e) {
            Log::error("Failed to create database for tenant: {$tenant->name}", [
                'tenant_id' => $tenant->id,
                'db_name' => $dbName,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
