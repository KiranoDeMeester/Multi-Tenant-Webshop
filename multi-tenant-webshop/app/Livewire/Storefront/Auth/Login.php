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
        // For demo purposes: if owner@example.com logs in, actually log them in
        if ($this->email === 'owner@example.com' && $this->password === 'password') {
            $user = \App\Models\Tenant\User::where('email', $this->email)->first();
            if ($user) {
                auth('tenant')->login($user);
                $this->dispatch('user-logged-in');
                $this->showChoiceModal = true;
                return;
            }
        }

        // Placeholder for customer authentication logic
        $this->addError('email', __('Authenticatie voor klanten wordt in een volgende stap geïmplementeerd. Gebruik owner@example.com / password voor demo.'));
    }

    public function goToDashboard()
    {
        return redirect()->route('tenant.dashboard');
    }

    public function goToShop()
    {
        return redirect()->route('storefront.products.index');
    }

    public function loginAsOwner()
    {
        $this->email = 'owner@example.com';
        $this->password = 'password';
        $this->login();
    }

    public function render()
    {
        return view('livewire.storefront.auth.login');
    }
}
