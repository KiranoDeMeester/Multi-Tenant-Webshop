<?php

namespace App\Livewire\Storefront;

use App\Models\Tenant\Setting;
use Livewire\Component;

class CookieBanner extends Component
{
    public bool $enabled = true;
    public string $text = '';
    public bool $isVisible = false;

    public function mount()
    {
        $this->enabled = (bool) (Setting::where('key', 'compliance_cookie_banner_enabled')->first()?->value ?? true);
        $this->text = Setting::where('key', 'compliance_cookie_banner_text')->first()?->value ?? 'Wij gebruiken cookies om uw ervaring te verbeteren. Door verder te gaan op onze website gaat u akkoord met ons gebruik van cookies.';
    }

    public function render()
    {
        return view('livewire.storefront.cookie-banner');
    }
}
