<?php

namespace App\Livewire\Storefront\Pages;

use App\Models\Tenant\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.storefront')]
class PrivacyPolicy extends Component
{
    public string $content = '';

    public function mount()
    {
        $this->content = Setting::where('key', 'compliance_privacy_policy_content')->first()?->value ?? 'Onze privacy policy is momenteel niet beschikbaar.';
    }

    public function render()
    {
        return view('livewire.storefront.pages.privacy-policy');
    }
}
