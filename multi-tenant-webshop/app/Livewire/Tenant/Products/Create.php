<?php

namespace App\Livewire\Tenant\Products;

use App\Models\Tenant\Product;
use App\Models\Tenant\Category;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('layouts.tenant')]
class Create extends Component
{
    public string $name = '';
    public string $sku = '';
    public string $description = '';
    public float $price = 0;
    public int $stock = 0;
    public ?string $category_id = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'sku' => 'required|string|max:50|unique:products,sku',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'category_id' => 'nullable|exists:categories,id',
    ];

    public function save()
    {
        $this->validate();

        Product::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name) . '-' . Str::random(5),
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
        ]);

        session()->flash('message', 'Product succesvol aangemaakt!');

        $tenant = app(\App\Services\TenantManager::class)->getTenant();

        return redirect()->route('tenant.products.index', ['tenant' => $tenant->slug]);
    }

    public function render()
    {
        return view('livewire.tenant.products.create', [
            'categories' => Category::all(),
        ]);
    }
}
