<?php

namespace App\Livewire\Storefront\Account;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class Profile extends Component
{
    public $user;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount()
    {
        $this->user = auth('customer')->user() ?? auth('tenant')->user();

        if (! $this->user) {
            return redirect()->route('storefront.login');
        }

        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique($this->user->getTable())->ignore($this->user->id),
            ],
        ]);

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->dispatch('profile-updated');

        session()->flash('message', __('Profiel succesvol bijgewerkt!'));
    }

    public function updatePassword()
    {
        $this->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $this->user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->password = '';
        $this->password_confirmation = '';

        session()->flash('password_message', __('Wachtwoord succesvol gewijzigd!'));
    }

    public function render()
    {
        return view('livewire.storefront.account.profile');
    }
}
