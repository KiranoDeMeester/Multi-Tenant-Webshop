<?php

namespace App\Livewire\Admin;

use App\Models\Landlord\ContactMessage;
use App\Models\Landlord\Domain;
use App\Models\Landlord\Tenant;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'totalTenants' => Tenant::count(),
            'activeTenants' => Tenant::where('is_active', true)->count(),
            'totalDomains' => Domain::count(),
            'totalMessages' => ContactMessage::count(),
            'recentTenants' => Tenant::with('domains')->latest()->take(5)->get(),
        ]);
    }
}
