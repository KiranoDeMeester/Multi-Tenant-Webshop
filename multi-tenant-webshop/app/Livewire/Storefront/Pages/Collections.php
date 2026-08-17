<?php

namespace App\Livewire\Storefront\Pages;

use App\Models\Tenant\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;

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
