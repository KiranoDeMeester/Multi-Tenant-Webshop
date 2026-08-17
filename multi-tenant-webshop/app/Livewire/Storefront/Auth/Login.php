<?php

namespace App\Livewire\Storefront\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $showChoiceModal = false;

    public function login()
    {
        if (auth('tenant')->attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->dispatch('user-logged-in');
            $this->showChoiceModal = true;

            return;
        }

        if (auth('customer')->attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->dispatch('user-logged-in');

            return redirect()->route('storefront.account');
        }

        $this->addError('email', __('De inloggegevens zijn onjuist.'));
    }

    public function goToDashboard()
    {
        return redirect()->route('tenant.dashboard');
    }

    public function goToShop()
    {
        return redirect()->route('storefront.products.index');
    }

    public function goToAccount()
    {
        return redirect()->route('storefront.account');
    }

    public function render()
    {
        return view('livewire.storefront.auth.login');
    }
}
