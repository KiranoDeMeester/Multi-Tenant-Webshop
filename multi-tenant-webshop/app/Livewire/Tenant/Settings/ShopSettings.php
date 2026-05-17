<?php

namespace App\Livewire\Tenant\Settings;

use App\Models\Tenant\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.tenant')]
class ShopSettings extends Component
{
    // SEO
    public string $meta_title = '';
    public string $meta_description = '';

    // Pagina's
    public string $about_us_content = '';

    // Shipping
    public float $shipping_fee = 0;
    public float $free_shipping_threshold = 0;

    public function mount()
    {
        $this->meta_title = Setting::where('key', 'shop_meta_title')->first()?->value ?? '';
        $this->meta_description = Setting::where('key', 'shop_meta_description')->first()?->value ?? '';
        
        $this->about_us_content = Setting::where('key', 'about_us_content')->first()?->value ?? '';
        
        $this->shipping_fee = (float) (Setting::where('key', 'shipping_fee')->first()?->value ?? 0);
        $this->free_shipping_threshold = (float) (Setting::where('key', 'free_shipping_threshold')->first()?->value ?? 0);
    }

    public function save()
    {
        Setting::updateOrCreate(['key' => 'shop_meta_title'], ['value' => $this->meta_title]);
        Setting::updateOrCreate(['key' => 'shop_meta_description'], ['value' => $this->meta_description]);
        
        Setting::updateOrCreate(['key' => 'about_us_content'], ['value' => $this->about_us_content]);
        
        Setting::updateOrCreate(['key' => 'shipping_fee'], ['value' => $this->shipping_fee]);
        Setting::updateOrCreate(['key' => 'free_shipping_threshold'], ['value' => $this->free_shipping_threshold]);

        session()->flash('message', __('Winkelinstellingen succesvol bijgewerkt!'));
    }

    public function render()
    {
        return view('livewire.tenant.settings.shop-settings');
    }
}
