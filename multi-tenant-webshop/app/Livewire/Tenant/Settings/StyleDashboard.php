<?php

namespace App\Livewire\Tenant\Settings;

use App\Models\Tenant\Setting;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.tenant')]
class StyleDashboard extends Component
{
    use WithFileUploads;

    public string $primary_color = '#4f46e5';

    public string $secondary_color = '#10b981';

    public string $accent_color = '#f59e0b';

    public string $font_family = 'Inter';

    // Layout & Features
    public string $layout_type = 'modern';

    public bool $show_hero_banner = true;

    public string $hero_image_url = '';

    public $hero_image_upload;

    public bool $has_uploaded_hero_image = false;

    public string $hero_title = '';

    public string $hero_subtitle = '';

    public string $tab = 'design';

    public function mount()
    {
        $this->primary_color = Setting::where('key', 'theme_primary_color')->first()?->value ?? '#4f46e5';
        $this->secondary_color = Setting::where('key', 'theme_secondary_color')->first()?->value ?? '#10b981';
        $this->accent_color = Setting::where('key', 'theme_accent_color')->first()?->value ?? '#f59e0b';
        $this->font_family = Setting::where('key', 'theme_font_family')->first()?->value ?? 'Inter';

        $this->layout_type = Setting::where('key', 'layout_type')->first()?->value ?? 'modern';
        $this->show_hero_banner = (bool) (Setting::where('key', 'show_hero_banner')->first()?->value ?? true);
        $this->hero_title = Setting::where('key', 'hero_title')->first()?->value ?? '';
        $this->hero_subtitle = Setting::where('key', 'hero_subtitle')->first()?->value ?? '';

        $heroSetting = Setting::firstOrCreate(['key' => 'hero_image_url'], ['value' => '']);
        $this->has_uploaded_hero_image = $heroSetting->hasMedia('hero_image');
        $this->hero_image_url = $heroSetting->getFirstMediaUrl('hero_image') ?: $heroSetting->value;
    }

    public function save()
    {
        Setting::updateOrCreate(['key' => 'theme_primary_color'], ['value' => $this->primary_color]);
        Setting::updateOrCreate(['key' => 'theme_secondary_color'], ['value' => $this->secondary_color]);
        Setting::updateOrCreate(['key' => 'theme_accent_color'], ['value' => $this->accent_color]);
        Setting::updateOrCreate(['key' => 'theme_font_family'], ['value' => $this->font_family]);

        Setting::updateOrCreate(['key' => 'layout_type'], ['value' => $this->layout_type]);
        Setting::updateOrCreate(['key' => 'show_hero_banner'], ['value' => $this->show_hero_banner]);
        Setting::updateOrCreate(['key' => 'hero_title'], ['value' => $this->hero_title]);
        Setting::updateOrCreate(['key' => 'hero_subtitle'], ['value' => $this->hero_subtitle]);

        $heroSetting = Setting::updateOrCreate(['key' => 'hero_image_url'], ['value' => $this->hero_image_url]);

        if ($this->hero_image_upload) {
            $heroSetting->clearMediaCollection('hero_image');
            $heroSetting->addMedia($this->hero_image_upload->getRealPath())
                ->usingFileName($this->hero_image_upload->getClientOriginalName())
                ->toMediaCollection('hero_image');

            $this->hero_image_url = $heroSetting->getFirstMediaUrl('hero_image');
            $heroSetting->update(['value' => $this->hero_image_url]);
            $this->has_uploaded_hero_image = true;
            $this->hero_image_upload = null;
        }

        session()->flash('message', 'Huisstijl en layout succesvol bijgewerkt!');
    }

    public function deleteHeroImage()
    {
        $heroSetting = Setting::where('key', 'hero_image_url')->first();
        if ($heroSetting) {
            $heroSetting->clearMediaCollection('hero_image');
            $this->has_uploaded_hero_image = false;
            $this->hero_image_url = ''; // Reset to empty or maybe some default
            $heroSetting->update(['value' => '']);
        }
        $this->hero_image_upload = null;
    }

    public function render()
    {
        return view('livewire.tenant.settings.style-dashboard');
    }
}
