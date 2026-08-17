<?php

namespace App\Livewire\Storefront\Cart;

use App\Services\CartService;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Winkelwagen')]
class Index extends Component
{
    public $notes = '';

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

    public function checkout()
    {
        return redirect()->route('storefront.checkout');
    }

    public function render()
    {
        $cartService = app(CartService::class);

        return view('livewire.storefront.cart.index', [
            'items' => $cartService->getItems(),
            'total' => $cartService->getTotal(),
            'shippingFee' => $cartService->getShippingFee(),
            'grandTotal' => $cartService->getGrandTotal(),
            'count' => $cartService->getCount(),
        ])->layout('components.layouts.storefront');
    }
}
