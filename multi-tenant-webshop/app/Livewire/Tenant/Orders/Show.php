<?php

namespace App\Livewire\Tenant\Orders;

use App\Mail\OrderCancelledMail;
use App\Mail\OrderShippedMail;
use App\Models\Tenant\Order;
use App\Services\StockService;
use App\Services\TenantManager;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
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
        Gate::forUser($user)->authorize('view', $order);
        $this->order = $order->load(['items', 'customer']);
    }

    public function updateStatus($status, StockService $stockService)
    {
        $user = auth('tenant')->user() ?? auth('customer')->user() ?? auth()->user();
        Gate::forUser($user)->authorize('update', $this->order);

        $oldStatus = $this->order->status;
        $newStatus = $status;

        $tenant = app(TenantManager::class)->getTenant();
        $tenantId = $tenant?->id;

        if ($oldStatus !== $newStatus) {
            $customerEmail = $this->order->customer_details['email'] ?? $this->order->customer?->email;

            if ($newStatus === 'cancelled') {
                $stockService->restituteOrderStock($this->order);
                if ($customerEmail) {
                    Mail::to($customerEmail)->send(new OrderCancelledMail($this->order->id, $tenantId));
                }
            } elseif (in_array($newStatus, ['paid', 'shipped']) && $oldStatus === 'pending') {
                $stockService->fulfillOrderStock($this->order);
            }

            if ($newStatus === 'shipped' && $customerEmail) {
                Mail::to($customerEmail)->send(new OrderShippedMail($this->order->id, $tenantId));
            }
        }

        $this->order->update(['status' => $newStatus]);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => __('Bestellingsstatus bijgewerkt naar :status', ['status' => $newStatus]),
        ]);
    }

    public function render()
    {
        return view('livewire.tenant.orders.show');
    }
}
