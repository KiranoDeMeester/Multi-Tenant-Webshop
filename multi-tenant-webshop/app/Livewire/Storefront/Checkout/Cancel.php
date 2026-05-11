<?php

namespace App\Livewire\Storefront\Checkout;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Betaling geannuleerd')]
class Cancel extends Component
{
    public function render()
    {
        return view('livewire.storefront.checkout.cancel')
            ->layout('components.layouts.storefront');
    }
}
