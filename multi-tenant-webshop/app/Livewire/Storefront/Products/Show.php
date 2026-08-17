<?php

namespace App\Livewire\Storefront\Products;

use App\Models\Tenant\Product;
use App\Models\Tenant\ProductVariation;
use App\Services\CartService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class Show extends Component
{
    public Product $product;

    public int $quantity = 1;

    public ?string $selectedVariationId = null;

    public function mount($slug)
    {
        $this->product = Product::where('slug', $slug)
            ->with(['category', 'variations.attributeValues.attribute'])
            ->firstOrFail();

        if ($this->product->variations->isNotEmpty()) {
            // Default to first in-stock variation or first available
            $firstInStock = $this->product->variations->firstWhere('stock', '>', 0) ?? $this->product->variations->first();
            $this->selectedVariationId = $firstInStock?->id;
        }
    }

    public function selectVariation(string $variationId)
    {
        $this->selectedVariationId = $variationId;
        $this->quantity = 1;
    }

    public function getSelectedVariationProperty(): ?ProductVariation
    {
        if (! $this->selectedVariationId) {
            return null;
        }

        return $this->product->variations->firstWhere('id', $this->selectedVariationId);
    }

    public function getCurrentPriceProperty(): float
    {
        if ($this->selectedVariation) {
            return (float) $this->selectedVariation->effective_price;
        }

        return (float) $this->product->price;
    }

    public function getCurrentStockProperty(): int
    {
        if ($this->selectedVariation) {
            return (int) $this->selectedVariation->stock;
        }

        return (int) $this->product->stock;
    }

    public function increment()
    {
        if ($this->quantity < $this->currentStock) {
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
        if ($this->currentStock <= 0) {
            session()->flash('error', __('Dit product is momenteel niet op voorraad.'));

            return;
        }

        try {
            app(CartService::class)->add($this->product, $this->quantity, $this->selectedVariationId);

            $this->dispatch('product-added-to-cart');
            $this->dispatch('open-cart');

            session()->flash('message', __('Product toegevoegd aan winkelmand!'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.storefront.products.show', [
            'title' => $this->product->meta_title ?: $this->product->name,
            'meta_description' => $this->product->meta_description ?: str($this->product->description)->limit(160),
            'currentPrice' => $this->currentPrice,
            'currentStock' => $this->currentStock,
            'selectedVariation' => $this->selectedVariation,
        ]);
    }
}
