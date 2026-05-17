<?php

namespace App\Livewire\Storefront\Pages;

use App\Models\Tenant\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.storefront')]
class Terms extends Component
{
    public string $content = '';

    public function mount()
    {
        $this->content = Setting::where('key', 'terms_conditions_content')->first()?->value ?? 'Onze algemene voorwaarden zijn momenteel niet beschikbaar.';
    }

    public function render()
    {
        return view('livewire.storefront.pages.terms');
    }
}
