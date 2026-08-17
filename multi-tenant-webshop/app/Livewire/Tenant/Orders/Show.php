<?php

namespace App\Livewire\Tenant\Orders;

use App\Models\Tenant\Order;
use App\Services\StockService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.tenant')]
class Show extends Component
{
    use AuthorizesRequests;

    public Order $order;

    public function mount(Order $order)
    {
        $user = auth('tenant')->user() ?? auth('customer')->user() ?? auth()->user();
        \Illuminate\Support\Facades\Gate::forUser($user)->authorize('view', $order);
        $this->order = $order->load(['items', 'customer']);
    }

    public function updateStatus($status, StockService $stockService)
    {
        $user = auth('tenant')->user() ?? auth('customer')->user() ?? auth()->user();
        \Illuminate\Support\Facades\Gate::forUser($user)->authorize('update', $this->order);

        $oldStatus = $this->order->status;
        $newStatus = $status;

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

        $this->order->update(['status' => $newStatus]);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => __('Bestellingsstatus bijgewerkt naar :status', ['status' => $newStatus])
        ]);
    }

    public function render()
    {
        return view('livewire.tenant.orders.show');
    }
}
