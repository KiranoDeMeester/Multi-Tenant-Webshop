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

    public function mount()
    {
        $this->primary_color = Setting::where('key', 'theme_primary_color')->first()?->value ?? '#4f46e5';
        $this->secondary_color = Setting::where('key', 'theme_secondary_color')->first()?->value ?? '#10b981';
        $this->accent_color = Setting::where('key', 'theme_accent_color')->first()?->value ?? '#f59e0b';
        $this->font_family = Setting::where('key', 'theme_font_family')->first()?->value ?? 'Inter';
    }

    public function save()
    {
        Setting::updateOrCreate(['key' => 'theme_primary_color'], ['value' => $this->primary_color]);
        Setting::updateOrCreate(['key' => 'theme_secondary_color'], ['value' => $this->secondary_color]);
        Setting::updateOrCreate(['key' => 'theme_accent_color'], ['value' => $this->accent_color]);
        Setting::updateOrCreate(['key' => 'theme_font_family'], ['value' => $this->font_family]);

        session()->flash('message', 'Huisstijl succesvol bijgewerkt!');
    }

    public function render()
    {
        return view('livewire.tenant.settings.style-dashboard');
    }
}
