<?php

namespace App\Policies;

use App\Models\Tenant\Customer;
use App\Models\Tenant\User;
use Illuminate\Contracts\Auth\Authenticatable;

class CustomerPolicy
{
    /**
     * Determine whether the user can view any customers.
     */
    public function viewAny(?Authenticatable $user): bool
    {
        return $user instanceof User;
    }

    /**
     * Determine whether the user can view the customer profile.
     */
    public function view(?Authenticatable $user, Customer $customer): bool
    {
        if ($user instanceof User) {
            return true;
        }

        if ($user instanceof Customer) {
            return $user->id === $customer->id;
        }

        return false;
    }

    /**
     * Determine whether the user can update the customer profile.
     */
    public function update(?Authenticatable $user, Customer $customer): bool
    {
        if ($user instanceof User) {
            return true;
        }

        if ($user instanceof Customer) {
            return $user->id === $customer->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the customer.
     */
    public function delete(?Authenticatable $user, Customer $customer): bool
    {
        return $user instanceof User;
    }
}
