<?php

use App\Models\Landlord\Tenant;
use App\Services\TenantManager;

if (! function_exists('tenant')) {
    /**
     * Get the current tenant.
     */
    function tenant(): ?Tenant
    {
        return app(TenantManager::class)->getTenant();
    }
}

if (! function_exists('is_tenant_context')) {
    /**
     * Check if a tenant context is active.
     */
    function is_tenant_context(): bool
    {
        return ! is_null(tenant());
    }
}
