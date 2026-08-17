<?php

namespace App\Livewire\Storefront\Auth;

use App\Models\Tenant\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $phone = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255|unique:customers,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:30',
        ];
    }

    protected array $messages = [
        'name.required' => 'Vul uw volledige naam in.',
        'email.required' => 'Vul een geldig e-mailadres in.',
        'email.email' => 'Het e-mailadres is niet geldig.',
        'email.unique' => 'Er bestaat al een account met dit e-mailadres.',
        'password.required' => 'Vul een wachtwoord in.',
        'password.min' => 'Het wachtwoord moet minimaal 8 tekens bevatten.',
        'password.confirmed' => 'De wachtwoorden komen niet overeen.',
    ];

    public function register()
    {
        $this->validate();

        $customer = Customer::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'phone' => $this->phone ?: null,
            'email_verified_at' => now(),
        ]);

        Auth::guard('customer')->login($customer);

        session()->flash('message', __('Welkom! Uw account is succesvol aangemaakt.'));

        return redirect()->route('storefront.account');
    }

    public function render()
    {
        return view('livewire.storefront.auth.register');
    }
}
