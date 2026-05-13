<?php

namespace App\Livewire\Admin;

use App\Models\Landlord\Tenant;
use App\Models\Landlord\Domain;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'totalTenants' => Tenant::count(),
            'totalDomains' => Domain::count(),
            'recentTenants' => Tenant::with('domains')->latest()->take(5)->get(),
        ]);
    }
}
