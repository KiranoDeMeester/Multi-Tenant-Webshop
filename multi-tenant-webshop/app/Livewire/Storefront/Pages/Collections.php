<?php

namespace App\Livewire\Storefront\Pages;

use App\Models\Tenant\Category;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.storefront')]
class Collections extends Component
{
    public function render()
    {
        return view('livewire.storefront.pages.collections', [
            'categories' => Category::all(),
        ]);
    }
}
