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
        $user = auth('tenant')->user() ?? auth('customer')->user() ?? auth()->user();
        \Illuminate\Support\Facades\Gate::forUser($user)->authorize('update', $order);
        $this->order = $order;
        $this->status = $order->status;
        $this->order_number = $order->order_number;
    }

    public function save(StockService $stockService)
    {
        $user = auth('tenant')->user() ?? auth('customer')->user() ?? auth()->user();
        \Illuminate\Support\Facades\Gate::forUser($user)->authorize('update', $this->order);

        $this->validate([
            'status' => 'required|in:pending,paid,shipped,cancelled',
            'order_number' => 'required|string|max:255|unique:orders,order_number,' . $this->order->id,
        ]);

        $oldStatus = $this->order->status;
        $newStatus = $this->status;

        $tenant = app(\App\Services\TenantManager::class)->getTenant();
        $tenantId = $tenant?->id;

        if ($oldStatus !== $newStatus) {
            $customerEmail = $this->order->customer_details['email'] ?? $this->order->customer?->email;

            if ($newStatus === 'cancelled') {
                $stockService->restituteOrderStock($this->order);
                if ($customerEmail) {
                    \Illuminate\Support\Facades\Mail::to($customerEmail)->send(new \App\Mail\OrderCancelledMail($this->order->id, $tenantId));
                }
            } elseif (in_array($newStatus, ['paid', 'shipped']) && $oldStatus === 'pending') {
                $stockService->fulfillOrderStock($this->order);
            }

            if ($newStatus === 'shipped' && $customerEmail) {
                \Illuminate\Support\Facades\Mail::to($customerEmail)->send(new \App\Mail\OrderShippedMail($this->order->id, $tenantId));
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

        return redirect()->route('tenant.orders.index', ['tenant' => $tenant->slug]);
    }

    public function render()
    {
        return view('livewire.tenant.orders.edit');
    }
}
