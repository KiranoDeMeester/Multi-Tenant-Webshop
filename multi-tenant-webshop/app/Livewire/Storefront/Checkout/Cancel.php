<?php

namespace App\Livewire\Storefront\Checkout;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Betaling geannuleerd')]
class Cancel extends Component
{
    public function render()
    {
        return view('livewire.storefront.checkout.cancel')
            ->layout('components.layouts.storefront');
    }
}
