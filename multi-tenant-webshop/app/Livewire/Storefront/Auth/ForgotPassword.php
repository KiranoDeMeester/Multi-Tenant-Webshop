<?php

namespace App\Livewire\Storefront\Auth;

use App\Mail\CustomerResetPasswordMail;
use App\Models\Tenant\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.storefront')]
#[Title('Wachtwoord Vergeten')]
class ForgotPassword extends Component
{
    public string $email = '';

    public bool $sent = false;

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    public function sendResetLink()
    {
        $this->validate();

        $customer = Customer::where('email', strtolower(trim($this->email)))->first();

        if ($customer) {
            $token = Str::random(64);

            // Store in password_reset_tokens or update existing
            DB::connection('tenant')->table('password_reset_tokens')->updateOrInsert(
                ['email' => $customer->email],
                [
                    'token' => Hash::make($token),
                    'created_at' => now(),
                ]
            );

            $resetUrl = route('storefront.password.reset', [
                'token' => $token,
                'email' => $customer->email,
            ]);

            Mail::to($customer->email)->send(new CustomerResetPasswordMail($resetUrl, $customer->name));
        }

        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.storefront.auth.forgot-password');
    }
}
