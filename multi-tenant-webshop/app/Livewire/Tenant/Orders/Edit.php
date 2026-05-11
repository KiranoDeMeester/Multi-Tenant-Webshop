<?php

namespace App\Livewire\Tenant\Orders;

use App\Models\Tenant\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.tenant')]
class Edit extends Component
{
    public Order $order;
    public $status;
    public $order_number;

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->status = $order->status;
        $this->order_number = $order->order_number;
    }

    public function save()
    {
        $this->validate([
            'status' => 'required|in:pending,paid,shipped,cancelled',
            'order_number' => 'required|string|max:255|unique:orders,order_number,' . $this->order->id,
        ]);

        $this->order->update([
            'status' => $this->status,
            'order_number' => $this->order_number,
        ]);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Bestelling succesvol bijgewerkt!'
        ]);

        return redirect()->route('tenant.orders.index');
    }

    public function render()
    {
        return view('livewire.tenant.orders.edit');
    }
}
