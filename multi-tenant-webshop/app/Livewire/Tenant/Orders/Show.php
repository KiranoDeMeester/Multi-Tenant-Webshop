<?php

namespace App\Livewire\Tenant\Orders;

use App\Models\Tenant\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.tenant')]
class Show extends Component
{
    public Order $order;

    public function mount(Order $order)
    {
        $this->order = $order->load(['items', 'customer']);
    }

    public function updateStatus($status)
    {
        $this->order->update(['status' => $status]);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Bestellingsstatus bijgewerkt naar ' . $status
        ]);
    }

    public function render()
    {
        return view('livewire.tenant.orders.show');
    }
}
