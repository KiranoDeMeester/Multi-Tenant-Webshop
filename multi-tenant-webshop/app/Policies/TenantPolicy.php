<?php

namespace App\Policies;

use App\Models\Landlord\Tenant;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class TenantPolicy
{
    /**
     * Determine whether the user can view any tenants.
     */
    public function viewAny(?Authenticatable $user): bool
    {
        return $user instanceof User;
    }

    /**
     * Determine whether the user can view the tenant.
     */
    public function view(?Authenticatable $user, Tenant $tenant): bool
    {
        return $user instanceof User;
    }

    /**
     * Determine whether the user can create tenants.
     */
    public function create(?Authenticatable $user): bool
    {
        return $user instanceof User;
    }

    /**
     * Determine whether the user can update the tenant.
     */
    public function update(?Authenticatable $user, Tenant $tenant): bool
    {
        return $user instanceof User;
    }

    /**
     * Determine whether the user can delete the tenant.
     */
    public function delete(?Authenticatable $user, Tenant $tenant): bool
    {
        return $user instanceof User;
    }
}
