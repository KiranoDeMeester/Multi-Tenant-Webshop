<?php

namespace App\Livewire\Tenant\Settings;

use App\Models\Tenant\Setting;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.tenant')]
class InvoiceSettings extends Component
{
    use WithFileUploads;

    public string $company_name = '';

    public string $address = '';

    public string $vat_number = '';

    public string $email = '';

    public string $footer_text = '';

    public $logo;

    public ?string $current_logo = null;

    public function mount()
    {
        $this->company_name = Setting::where('key', 'invoice_company_name')->first()?->value ?? config('app.name');
        $this->address = Setting::where('key', 'invoice_address')->first()?->value ?? '';
        $this->vat_number = Setting::where('key', 'invoice_vat_number')->first()?->value ?? '';
        $this->email = Setting::where('key', 'invoice_email')->first()?->value ?? config('mail.from.address');
        $this->footer_text = Setting::where('key', 'invoice_footer_text')->first()?->value ?? 'Bedankt voor uw bestelling!';
        $this->current_logo = Setting::where('key', 'invoice_logo')->first()?->value;
    }

    public function save()
    {
        $this->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'required|string',
            'vat_number' => 'nullable|string|max:50',
            'email' => 'required|email',
            'footer_text' => 'nullable|string|max:500',
            'logo' => 'nullable|image|max:1024', // 1MB Max
        ]);

        Setting::updateOrCreate(['key' => 'invoice_company_name'], ['value' => $this->company_name]);
        Setting::updateOrCreate(['key' => 'invoice_address'], ['value' => $this->address]);
        Setting::updateOrCreate(['key' => 'invoice_vat_number'], ['value' => $this->vat_number]);
        Setting::updateOrCreate(['key' => 'invoice_email'], ['value' => $this->email]);
        Setting::updateOrCreate(['key' => 'invoice_footer_text'], ['value' => $this->footer_text]);

        if ($this->logo) {
            $path = $this->logo->store('branding', 'public');
            Setting::updateOrCreate(['key' => 'invoice_logo'], ['value' => $path]);
            $this->current_logo = $path;
        }

        session()->flash('message', 'Factuurinstellingen succesvol bijgewerkt!');
    }

    public function render()
    {
        return view('livewire.tenant.settings.invoice-settings');
    }
}
