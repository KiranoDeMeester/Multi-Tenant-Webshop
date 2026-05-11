<?php

namespace App\Livewire\Tenant\Dashboard;

use App\Models\Tenant\Product;
use App\Models\Tenant\Category;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $lowStockProducts = Product::doesntHave('variations')
            ->where('stock', '<', 5)
            ->count();
            
        $lowStockVariations = \App\Models\Tenant\ProductVariation::where('stock', '<', 5)
            ->count();

        return view('livewire.tenant.dashboard.index', [
            'productCount' => Product::count(),
            'categoryCount' => Category::count(),
            'stockWarningCount' => $lowStockProducts + $lowStockVariations,
        ])->layout('layouts.tenant');
    }
}
