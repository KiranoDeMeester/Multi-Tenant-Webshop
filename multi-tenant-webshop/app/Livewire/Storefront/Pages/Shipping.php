<?php

namespace App\Livewire\Storefront\Pages;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class Shipping extends Component
{
    public function render()
    {
        return view('livewire.storefront.pages.shipping');
    }
}
