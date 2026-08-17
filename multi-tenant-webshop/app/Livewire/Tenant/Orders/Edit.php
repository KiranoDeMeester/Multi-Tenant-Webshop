<?php

namespace App\Livewire\Tenant\Orders;

use App\Models\Tenant\Order;
use App\Services\StockService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.tenant')]
class Edit extends Component
{
    use AuthorizesRequests;

    public Order $order;
    public string $status = '';
    public string $order_number = '';

    public function mount(Order $order)
    {
        $this->authorize('update', $order);
        $this->order = $order;
        $this->status = $order->status;
        $this->order_number = $order->order_number;
    }

    public function save(StockService $stockService)
    {
        $this->authorize('update', $this->order);

        $this->validate([
            'status' => 'required|in:pending,paid,shipped,cancelled',
            'order_number' => 'required|string|max:255|unique:orders,order_number,' . $this->order->id,
        ]);

        $oldStatus = $this->order->status;
        $newStatus = $this->status;

        if ($oldStatus !== $newStatus) {
            if ($newStatus === 'cancelled') {
                $stockService->restituteOrderStock($this->order);
            } elseif (in_array($newStatus, ['paid', 'shipped']) && $oldStatus === 'pending') {
                $stockService->fulfillOrderStock($this->order);
            }
        }

        $this->order->update([
            'status' => $newStatus,
            'order_number' => $this->order_number,
        ]);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => __('Bestelling succesvol bijgewerkt!')
        ]);

        $tenant = app(\App\Services\TenantManager::class)->getTenant();

        return redirect()->route('tenant.orders.index', ['tenant' => $tenant->slug]);
    }

    public function render()
    {
        return view('livewire.tenant.orders.edit');
    }
}
