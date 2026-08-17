<?php

namespace App\Livewire\Tenant\Orders;

use App\Models\Tenant\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.tenant')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $status = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function deleteOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->delete(); // Dit voert een soft-delete uit

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Bestelling succesvol verplaatst naar prullenbak.',
        ]);
    }

    public function render()
    {
        $query = Order::query()
            ->when($this->search, function ($q) {
                $q->where('order_number', 'like', '%'.$this->search.'%');
            })
            ->when($this->status, function ($q) {
                $q->where('status', $this->status);
            })
            ->latest();

        return view('livewire.tenant.orders.index', [
            'orders' => $query->paginate(10),
        ]);
    }
}
