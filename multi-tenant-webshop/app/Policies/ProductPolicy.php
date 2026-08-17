<?php

namespace App\Policies;

use App\Models\Tenant\Product;
use Illuminate\Contracts\Auth\Authenticatable;

class ProductPolicy
{
    /**
     * Determine whether the user can view any products.
     */
    public function viewAny(?Authenticatable $user): bool
    {
        return true; // Anyone can browse storefront products
    }

    /**
     * Determine whether the user can view the product.
     */
    public function view(?Authenticatable $user, Product $product): bool
    {
        return true; // Publicly accessible
    }

    /**
     * Determine whether the user can manage/create products (Merchant Admin).
     */
    public function create(?Authenticatable $user): bool
    {
        return $user instanceof \App\Models\Tenant\User;
    }

    /**
     * Determine whether the user can update the product.
     */
    public function update(?Authenticatable $user, Product $product): bool
    {
        return $user instanceof \App\Models\Tenant\User;
    }

    /**
     * Determine whether the user can delete the product.
     */
    public function delete(?Authenticatable $user, Product $product): bool
    {
        return $user instanceof \App\Models\Tenant\User;
    }
}
