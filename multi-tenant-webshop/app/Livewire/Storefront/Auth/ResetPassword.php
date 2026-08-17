<?php

namespace App\Livewire\Storefront\Auth;

use App\Models\Tenant\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.storefront')]
#[Title('Wachtwoord Resetten')]
class ResetPassword extends Component
{
    public string $token = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(string $token)
    {
        $this->token = $token;
        $this->email = request()->query('email', '');
    }

    protected function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function resetPassword()
    {
        $this->validate();

        $record = DB::connection('tenant')->table('password_reset_tokens')
            ->where('email', $this->email)
            ->first();

        if (! $record || ! Hash::check($this->token, $record->token)) {
            $this->addError('email', __('Deze herstellink is ongeldig of verlopen.'));

            return;
        }

        // Check if token expired (> 60 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            $this->addError('email', __('Deze herstellink is verlopen. Vraag een nieuwe aan.'));

            return;
        }

        $customer = Customer::where('email', $this->email)->first();
        if (! $customer) {
            $this->addError('email', __('Geen klantaccount gevonden met dit e-mailadres.'));

            return;
        }

        $customer->update([
            'password' => Hash::make($this->password),
        ]);

        // Clean up token
        DB::connection('tenant')->table('password_reset_tokens')->where('email', $this->email)->delete();

        Auth::guard('customer')->login($customer);

        session()->flash('message', __('Je wachtwoord is succesvol gewijzigd!'));

        return redirect()->route('storefront.account');
    }

    public function render()
    {
        return view('livewire.storefront.auth.reset-password');
    }
}
