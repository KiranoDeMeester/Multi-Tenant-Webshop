<?php

namespace App\Livewire\Tenant\Products;

use App\Models\Tenant\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.tenant')]
class Index extends Component
{
    use WithPagination;

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        session()->flash('message', 'Product succesvol verwijderd!');
    }

    public function render()
    {
        return view('livewire.tenant.products.index', [
            'products' => Product::with('category')->latest()->paginate(10),
        ]);
    }
}
