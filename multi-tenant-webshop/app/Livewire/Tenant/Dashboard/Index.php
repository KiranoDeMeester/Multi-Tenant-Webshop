<?php

namespace App\Livewire\Tenant\Dashboard;

use App\Models\Tenant\Product;
use App\Models\Tenant\Category;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.tenant.dashboard.index', [
            'productCount' => Product::count(),
            'categoryCount' => Category::count(),
            'stockWarningCount' => Product::where('stock', '<', 5)->count(),
        ])->layout('layouts.tenant');
    }
}
