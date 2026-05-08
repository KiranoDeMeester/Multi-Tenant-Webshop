<?php

namespace App\Livewire\Storefront\Cart;

use App\Services\CartService;
use Livewire\Component;
use Livewire\Attributes\On;

class SidePanel extends Component
{
    public bool $open = false;

    #[On('cart-updated')]
    #[On('product-added-to-cart')]
    public function refresh()
    {
        // This method triggers a re-render
    }

    public function toggle()
    {
        $this->open = !$this->open;
    }

    #[On('open-cart')]
    public function openCart()
    {
        $this->open = true;
    }

    #[On('toggle-cart')]
    public function toggleCart()
    {
        $this->open = !$this->open;
    }

    public function removeItem(string $key)
    {
        app(CartService::class)->remove($key);
        $this->dispatch('cart-updated');
    }

    public function updateQuantity(string $key, int $quantity)
    {
        app(CartService::class)->updateQuantity($key, $quantity);
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        $cartService = app(CartService::class);
        
        return view('livewire.storefront.cart.side-panel', [
            'items' => $cartService->getItems(),
            'total' => $cartService->getTotal(),
            'count' => $cartService->getCount(),
        ]);
    }
}
