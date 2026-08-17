<?php

namespace App\Livewire\Storefront\Account;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class Dashboard extends Component
{
    public $user;

    public function mount()
    {
        $this->user = auth('customer')->user() ?? auth('tenant')->user();

        if (! $this->user) {
            return redirect()->route('storefront.login');
        }
    }

    public function render()
    {
        return view('livewire.storefront.account.dashboard');
    }
}
