<?php

namespace App\Livewire\Storefront\Cart;

use App\Services\CartService;
use App\Actions\Tenant\PrepareCheckoutAction;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Winkelwagen')]
class Index extends Component
{
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

    public function checkout(PrepareCheckoutAction $prepareCheckout)
    {
        try {
            $checkoutUrl = $prepareCheckout->execute();
            return redirect($checkoutUrl);
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $cartService = app(CartService::class);
        
        return view('livewire.storefront.cart.index', [
            'items' => $cartService->getItems(),
            'total' => $cartService->getTotal(),
            'count' => $cartService->getCount(),
        ])->layout('components.layouts.storefront');
    }
}
