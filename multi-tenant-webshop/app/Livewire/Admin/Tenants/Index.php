<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Landlord\Tenant;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.admin.tenants.index', [
            'tenants' => Tenant::with('domains')->latest()->paginate(10),
            'totalTenants' => Tenant::count(),
            'totalDomains' => \App\Models\Landlord\Domain::count(),
        ]);
    }
}
