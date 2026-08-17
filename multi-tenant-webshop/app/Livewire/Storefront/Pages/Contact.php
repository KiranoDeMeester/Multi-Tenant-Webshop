<?php

namespace App\Livewire\Storefront\Pages;

use App\Models\Tenant\Setting;
use App\Services\TenantManager;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class Contact extends Component
{
    public string $email = '';

    public string $phone = '';

    public string $address = '';

    public string $content = '';

    public string $shopName = '';

    public function mount()
    {
        $this->email = Setting::where('key', 'contact_email')->first()?->value ?? '';
        $this->phone = Setting::where('key', 'contact_phone')->first()?->value ?? '';
        $this->address = Setting::where('key', 'contact_address')->first()?->value ?? '';
        $this->content = Setting::where('key', 'contact_content')->first()?->value ?? '';
        $this->shopName = Setting::where('key', 'shop_name')->first()?->value ?? app(TenantManager::class)->getTenant()?->name ?? 'Onze Winkel';
    }

    public function render()
    {
        return view('livewire.storefront.pages.contact');
    }
}
