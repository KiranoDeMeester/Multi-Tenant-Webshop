<?php

namespace App\Policies;

use App\Models\Tenant\Customer;
use App\Models\Tenant\Order;
use App\Models\Tenant\User;
use Illuminate\Contracts\Auth\Authenticatable;

class OrderPolicy
{
    /**
     * Determine whether the user can view any orders.
     */
    public function viewAny(?Authenticatable $user): bool
    {
        return $user instanceof User || $user instanceof Customer;
    }

    /**
     * Determine whether the user can view the order.
     */
    public function view(?Authenticatable $user, Order $order): bool
    {
        if ($user instanceof User) {
            return true;
        }

        if ($user instanceof Customer) {
            $customerDetails = is_array($order->customer_details) ? $order->customer_details : [];
            $email = $customerDetails['email'] ?? null;

            return $order->customer_id === $user->id || ($email && strtolower($email) === strtolower($user->email));
        }

        return false;
    }

    /**
     * Determine whether the user can update the order.
     */
    public function update(?Authenticatable $user, Order $order): bool
    {
        return $user instanceof User;
    }

    /**
     * Determine whether the user can cancel the order.
     */
    public function cancel(?Authenticatable $user, Order $order): bool
    {
        if ($user instanceof User) {
            return in_array($order->status, ['pending', 'paid']);
        }

        if ($user instanceof Customer) {
            $isOwner = $order->customer_id === $user->id ||
                (isset($order->customer_details['email']) && strtolower($order->customer_details['email']) === strtolower($user->email));

            return $isOwner && in_array($order->status, ['pending', 'paid']);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the order.
     */
    public function delete(?Authenticatable $user, Order $order): bool
    {
        return $user instanceof User;
    }
}
