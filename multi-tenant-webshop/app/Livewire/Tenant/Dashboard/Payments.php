<?php

namespace App\Livewire\Tenant\Dashboard;

use App\Services\TenantManager;
use Livewire\Component;

class Payments extends Component
{
    public function render()
    {
        $tenant = app(TenantManager::class)->getTenant();

        return view('livewire.tenant.dashboard.payments', [
            'tenant' => $tenant,
            'isConnected' => ! empty($tenant->stripe_account_id),
        ])->layout('layouts.tenant');
    }
}
