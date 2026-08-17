<?php

namespace App\Livewire\Storefront\Pages;

use App\Models\Tenant\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class OrderTracking extends Component
{
    public string $orderId;

    public ?Order $order = null;

    public function mount(string $id)
    {
        $this->orderId = $id;
        $this->order = Order::with(['items.product', 'items.productVariation'])->where('id', $this->orderId)->first();

        if (! $this->order) {
            abort(404, 'Bestelling niet gevonden.');
        }
    }

    public function render()
    {
        return view('livewire.storefront.pages.order-tracking', [
            'order' => $this->order,
        ]);
    }
}
