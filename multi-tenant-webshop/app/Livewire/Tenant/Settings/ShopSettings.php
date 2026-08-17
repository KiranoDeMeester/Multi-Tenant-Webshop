<?php

namespace App\Livewire\Tenant\Settings;

use App\Models\Tenant\Setting;
use Livewire\Attributes\Layout;
use Livewire\Component;

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

    // Socials
    public string $social_instagram = '';

    public string $social_tiktok = '';

    // Contact
    public string $contact_email = '';

    public string $contact_phone = '';

    public string $contact_address = '';

    public string $contact_content = '';

    public function mount()
    {
        $this->meta_title = Setting::where('key', 'shop_meta_title')->first()?->value ?? '';
        $this->meta_description = Setting::where('key', 'shop_meta_description')->first()?->value ?? '';

        $this->about_us_content = Setting::where('key', 'about_us_content')->first()?->value ?? '';

        $this->shipping_fee = (float) (Setting::where('key', 'shipping_fee')->first()?->value ?? 0);
        $this->free_shipping_threshold = (float) (Setting::where('key', 'free_shipping_threshold')->first()?->value ?? 0);

        $this->social_instagram = Setting::where('key', 'social_instagram')->first()?->value ?? '';
        $this->social_tiktok = Setting::where('key', 'social_tiktok')->first()?->value ?? '';

        $this->contact_email = Setting::where('key', 'contact_email')->first()?->value ?? '';
        $this->contact_phone = Setting::where('key', 'contact_phone')->first()?->value ?? '';
        $this->contact_address = Setting::where('key', 'contact_address')->first()?->value ?? '';
        $this->contact_content = Setting::where('key', 'contact_content')->first()?->value ?? '';
    }

    public function save()
    {
        Setting::updateOrCreate(['key' => 'shop_meta_title'], ['value' => $this->meta_title]);
        Setting::updateOrCreate(['key' => 'shop_meta_description'], ['value' => $this->meta_description]);

        Setting::updateOrCreate(['key' => 'about_us_content'], ['value' => $this->about_us_content]);

        Setting::updateOrCreate(['key' => 'shipping_fee'], ['value' => $this->shipping_fee]);
        Setting::updateOrCreate(['key' => 'free_shipping_threshold'], ['value' => $this->free_shipping_threshold]);

        Setting::updateOrCreate(['key' => 'social_instagram'], ['value' => $this->social_instagram]);
        Setting::updateOrCreate(['key' => 'social_tiktok'], ['value' => $this->social_tiktok]);

        Setting::updateOrCreate(['key' => 'contact_email'], ['value' => $this->contact_email]);
        Setting::updateOrCreate(['key' => 'contact_phone'], ['value' => $this->contact_phone]);
        Setting::updateOrCreate(['key' => 'contact_address'], ['value' => $this->contact_address]);
        Setting::updateOrCreate(['key' => 'contact_content'], ['value' => $this->contact_content]);

        session()->flash('message', __('Winkelinstellingen succesvol bijgewerkt!'));
    }

    public function render()
    {
        return view('livewire.tenant.settings.shop-settings');
    }
}
