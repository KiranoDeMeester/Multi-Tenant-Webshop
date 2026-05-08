<?php

namespace App\Services;

use App\Models\Tenant\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected string $sessionKey = 'shopping_cart';

    /**
     * Get all items in the cart.
     */
    public function getItems(): array
    {
        return Session::get($this->sessionKey, []);
    }

    /**
     * Add a product to the cart.
     */
    public function add(Product $product, int $quantity = 1, ?string $variationId = null): void
    {
        $cart = $this->getItems();
        $key = $variationId ? "{$product->id}_{$variationId}" : $product->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->getFirstMediaUrl('products', 'thumb') ?: $product->image_url,
                'variation_id' => $variationId,
                'sku' => $product->sku,
            ];
        }

        Session::put($this->sessionKey, $cart);
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(string $key): void
    {
        $cart = $this->getItems();
        unset($cart[$key]);
        Session::put($this->sessionKey, $cart);
    }

    /**
     * Update quantity of an item.
     */
    public function updateQuantity(string $key, int $quantity): void
    {
        $cart = $this->getItems();
        if (isset($cart[$key])) {
            if ($quantity <= 0) {
                $this->remove($key);
                return;
            }
            $cart[$key]['quantity'] = $quantity;
            Session::put($this->sessionKey, $cart);
        }
    }

    /**
     * Clear the cart.
     */
    public function clear(): void
    {
        Session::forget($this->sessionKey);
    }

    /**
     * Get total price of the cart.
     */
    public function getTotal(): float
    {
        return array_reduce($this->getItems(), function ($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);
    }

    /**
     * Get total item count.
     */
    public function getCount(): int
    {
        return array_reduce($this->getItems(), function ($count, $item) {
            return $count + $item['quantity'];
        }, 0);
    }
}
