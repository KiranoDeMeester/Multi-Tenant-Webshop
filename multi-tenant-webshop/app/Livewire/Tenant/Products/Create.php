<?php

namespace App\Livewire\Tenant\Products;

use App\Models\Tenant\Product;
use App\Models\Tenant\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('layouts.tenant')]
class Create extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $sku = '';
    public string $description = '';
    public float $price = 0;
    public int $stock = 0;
    public ?string $category_id = null;
    public string $meta_title = '';
    public string $meta_description = '';
    public $image;

    protected $rules = [
        'name' => 'required|string|max:255',
        'sku' => 'required|string|max:50|unique:products,sku',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'category_id' => 'nullable|exists:categories,id',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string|max:1000',
        'image' => 'nullable|image|max:2048',
    ];

    public function save()
    {
        $this->validate();

        $product = Product::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name) . '-' . Str::random(5),
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ]);

        if ($this->image) {
            $product->addMedia($this->image->getRealPath())
                ->usingFileName($this->image->getClientOriginalName())
                ->toMediaCollection('products');
        }

        session()->flash('message', 'Product succesvol aangemaakt!');

        $tenant = app(\App\Services\TenantManager::class)->getTenant();

        return redirect()->route('tenant.products.manage', ['tenant' => $tenant->slug]);
    }

    public function render()
    {
        return view('livewire.tenant.products.create', [
            'categories' => Category::all(),
        ]);
    }
}
