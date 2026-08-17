<?php

namespace App\Policies;

use App\Models\Tenant\Customer;
use App\Models\Tenant\CustomerAddress;
use App\Models\Tenant\User;
use Illuminate\Contracts\Auth\Authenticatable;

class CustomerAddressPolicy
{
    /**
     * Determine whether the user can view the address.
     */
    public function view(?Authenticatable $user, CustomerAddress $address): bool
    {
        if ($user instanceof User) {
            return true;
        }

        if ($user instanceof Customer) {
            return $user->id === $address->customer_id;
        }

        return false;
    }

    /**
     * Determine whether the user can update the address.
     */
    public function update(?Authenticatable $user, CustomerAddress $address): bool
    {
        if ($user instanceof User) {
            return true;
        }

        if ($user instanceof Customer) {
            return $user->id === $address->customer_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the address.
     */
    public function delete(?Authenticatable $user, CustomerAddress $address): bool
    {
        if ($user instanceof User) {
            return true;
        }

        if ($user instanceof Customer) {
            return $user->id === $address->customer_id;
        }

        return false;
    }
}
