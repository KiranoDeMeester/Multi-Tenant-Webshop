<?php

namespace App\Livewire\Storefront\Navigation;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class UserDropdown extends Component
{
    #[On('user-logged-in')]
    public function refresh() {}

    public function logout()
    {
        $wasTenant = Auth::guard('tenant')->check();
        $tenant = app(\App\Services\TenantManager::class)->getTenant();

        Auth::guard('tenant')->logout();
        Auth::guard('web')->logout();
        Auth::guard('customer')->logout();

        session()->invalidate();
        session()->regenerateToken();

        if ($wasTenant && $tenant) {
            return redirect()->route('tenant.login', ['tenant' => $tenant->slug]);
        }

        return redirect()->route('storefront.products.index');
    }

    public function render()
    {
        return view('livewire.storefront.navigation.user-dropdown');
    }
}
