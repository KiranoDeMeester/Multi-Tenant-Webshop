<?php

namespace App\Livewire\Storefront\Checkout;

use App\Models\Tenant\Order;
use App\Services\CartService;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Bestelling geslaagd')]
class Success extends Component
{
    public ?Order $order = null;

    public function mount()
    {
        $sessionId = request('session_id');

        if ($sessionId) {
            $this->order = Order::where('stripe_session_id', $sessionId)->first();

            if ($this->order) {
                // Clear the cart on success
                app(CartService::class)->clear();
                $this->dispatch('cart-updated');
            }
        }
    }

    public function render()
    {
        return view('livewire.storefront.checkout.success')
            ->layout('components.layouts.storefront');
    }
}
