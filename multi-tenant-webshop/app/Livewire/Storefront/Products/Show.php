<?php

namespace App\Livewire\Storefront\Products;

use App\Models\Tenant\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class Show extends Component
{
    public Product $product;
    public int $quantity = 1;

    public function mount($slug)
    {
        $this->product = Product::where('slug', $slug)
            ->with(['category', 'variations.attributeValues.attribute'])
            ->firstOrFail();
    }

    public function increment()
    {
        if ($this->quantity < $this->product->stock) {
            $this->quantity++;
        }
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        app(\App\Services\CartService::class)->add($this->product, $this->quantity);
        
        $this->dispatch('product-added-to-cart');
        $this->dispatch('open-cart');
        
        session()->flash('message', __('Product toegevoegd aan winkelmand!'));
    }

    public function render()
    {
        return view('livewire.storefront.products.show');
    }
}
