<?php

namespace App\Livewire\Storefront\Pages;

use App\Models\Tenant\Setting;
use App\Services\TenantManager;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class AboutUs extends Component
{
    public string $content = '';

    public string $shopName = 'Onze Winkel';

    public function mount()
    {
        $this->content = Setting::where('key', 'about_us_content')->first()?->value ?? 'Informatie over ons is momenteel niet beschikbaar.';
        $this->shopName = Setting::where('key', 'shop_name')->first()?->value ?? app(TenantManager::class)->getTenant()?->name ?? 'Onze Winkel';
    }

    public function render()
    {
        return view('livewire.storefront.pages.about-us');
    }
}
