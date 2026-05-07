<?php

namespace App\Livewire\Tenant\Dashboard;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.auth')]
class Login extends Component
{
    public string $email = '';
    public string $password = '';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('tenant')->attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            
            $tenant = request()->route('tenant') ?? \Illuminate\Support\Str::before(request()->getHost(), '.');
            
            return redirect()->intended(route('tenant.dashboard', ['tenant' => $tenant]));
        }

        $this->addError('email', 'De opgegeven inloggegevens zijn onjuist.');
    }

    public function render()
    {
        return view('livewire.tenant.dashboard.login');
    }
}
