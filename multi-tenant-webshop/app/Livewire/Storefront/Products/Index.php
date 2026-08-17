<?php

namespace App\Livewire\Storefront\Products;

use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.storefront')]
class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public string $category = '';

    #[Url]
    public string $sort = 'latest';

    public $themeSettings;

    public function mount(?string $categorySlug = null)
    {
        $this->themeSettings = app(\App\Services\TenantManager::class)->getThemeSettings();

        if ($categorySlug) {
            $categoryExists = Category::where('slug', $categorySlug)->exists();
            if (!$categoryExists) {
                abort(404);
            }
            $this->category = $categorySlug;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function quickAddToCart(string $productId)
    {
        $product = Product::with('variations')->findOrFail($productId);

        if ($product->variations->isNotEmpty()) {
            return redirect()->route('storefront.products.show', ['slug' => $product->slug]);
        }

        if ($product->stock <= 0) {
            session()->flash('error', __('Dit product is uitverkocht.'));
            return;
        }

        try {
            app(\App\Services\CartService::class)->add($product, 1);
            $this->dispatch('product-added-to-cart');
            $this->dispatch('open-cart');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->when($this->category, function ($query) {
                $query->whereHas('category', function ($q) {
                    $q->where('slug', $this->category);
                });
            })
            ->when($this->sort, function ($query) {
                match ($this->sort) {
                    'price_low' => $query->orderBy('price', 'asc'),
                    'price_high' => $query->orderBy('price', 'desc'),
                    'latest' => $query->latest(),
                    default => $query->latest(),
                };
            })
            ->with('category')
            ->paginate(12);

        $categories = Category::all();

        $selectedCategory = null;
        if ($this->category) {
            $selectedCategory = Category::where('slug', $this->category)->first();
        }

        return view('livewire.storefront.products.index', [
            'products' => $products,
            'categories' => $categories,
            'title' => $selectedCategory?->meta_title ?: $selectedCategory?->name,
            'meta_description' => $selectedCategory?->meta_description ?: ($selectedCategory ? str($selectedCategory->description)->limit(160) : null),
        ]);
    }
}
