<?php

namespace App\Livewire\Storefront\Account;

use App\Mail\OrderCancelledMail;
use App\Models\Tenant\Order;
use App\Services\StockService;
use App\Services\TenantManager;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.storefront')]
class Orders extends Component
{
    use AuthorizesRequests, WithPagination;

    public function cancelOrder($orderId, StockService $stockService)
    {
        $user = auth('customer')->user() ?? auth('tenant')->user();

        if (! $user) {
            session()->flash('error', __('U moet ingelogd zijn om deze actie uit te voeren.'));

            return;
        }

        $order = Order::where('id', $orderId)
            ->where(function ($query) use ($user) {
                $query->where('customer_id', $user->id)
                    ->orWhere('customer_details->email', $user->email);
            })
            ->firstOrFail();

        $this->authorize('cancel', $order);

        if (in_array($order->status, ['pending', 'paid'])) {
            $stockService->restituteOrderStock($order);
            $order->update(['status' => 'cancelled']);

            $tenant = app(TenantManager::class)->getTenant();
            $customerEmail = $order->customer_details['email'] ?? $order->customer?->email ?? $user->email;
            if ($customerEmail) {
                Mail::to($customerEmail)->send(new OrderCancelledMail($order->id, $tenant?->id));
            }

            session()->flash('message', __('Uw bestelling is succesvol geannuleerd en de artikelen zijn hersteld in voorraad.'));
        } else {
            session()->flash('error', __('Deze bestelling kan niet meer geannuleerd worden.'));
        }
    }

    public function render()
    {
        $user = auth('customer')->user() ?? auth('tenant')->user();

        $orders = Order::query()
            ->when($user, function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('customer_id', $user->id)
                        ->orWhere('customer_details->email', $user->email);
                });
            }, function ($query) {
                $query->where('id', '0'); // No orders if not logged in
            })
            ->with(['items.product', 'items.variation'])
            ->latest()
            ->paginate(10);

        return view('livewire.storefront.account.orders', [
            'orders' => $orders,
        ]);
    }
}
