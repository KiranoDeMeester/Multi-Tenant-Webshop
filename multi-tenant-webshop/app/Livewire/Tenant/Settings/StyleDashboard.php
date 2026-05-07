<?php

namespace App\Livewire\Tenant\Settings;

use App\Models\Tenant\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.tenant')]
class StyleDashboard extends Component
{
    public string $primary_color = '#4f46e5';
    public string $secondary_color = '#10b981';
    public string $accent_color = '#f59e0b';
    public string $font_family = 'Inter';
    
    // Layout & Features
    public string $layout_type = 'modern';
    public bool $show_hero_banner = true;
    public bool $show_newsletter_popup = false;

    public string $tab = 'design';

    public function mount()
    {
        $this->primary_color = Setting::where('key', 'theme_primary_color')->first()?->value ?? '#4f46e5';
        $this->secondary_color = Setting::where('key', 'theme_secondary_color')->first()?->value ?? '#10b981';
        $this->accent_color = Setting::where('key', 'theme_accent_color')->first()?->value ?? '#f59e0b';
        $this->font_family = Setting::where('key', 'theme_font_family')->first()?->value ?? 'Inter';
        
        $this->layout_type = Setting::where('key', 'layout_type')->first()?->value ?? 'modern';
        $this->show_hero_banner = (bool) (Setting::where('key', 'show_hero_banner')->first()?->value ?? true);
        $this->show_newsletter_popup = (bool) (Setting::where('key', 'show_newsletter_popup')->first()?->value ?? false);
    }

    public function save()
    {
        Setting::updateOrCreate(['key' => 'theme_primary_color'], ['value' => $this->primary_color]);
        Setting::updateOrCreate(['key' => 'theme_secondary_color'], ['value' => $this->secondary_color]);
        Setting::updateOrCreate(['key' => 'theme_accent_color'], ['value' => $this->accent_color]);
        Setting::updateOrCreate(['key' => 'theme_font_family'], ['value' => $this->font_family]);
        
        Setting::updateOrCreate(['key' => 'layout_type'], ['value' => $this->layout_type]);
        Setting::updateOrCreate(['key' => 'show_hero_banner'], ['value' => $this->show_hero_banner]);
        Setting::updateOrCreate(['key' => 'show_newsletter_popup'], ['value' => $this->show_newsletter_popup]);

        session()->flash('message', 'Huisstijl en layout succesvol bijgewerkt!');
    }

    public function render()
    {
        return view('livewire.tenant.settings.style-dashboard');
    }
}
