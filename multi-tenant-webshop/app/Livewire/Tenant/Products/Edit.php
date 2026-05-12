<?php

namespace App\Livewire\Tenant\Products;

use App\Models\Tenant\Product;
use App\Models\Tenant\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('layouts.tenant')]
class Edit extends Component
{
    use WithFileUploads;

    public Product $product;
    
    public string $name = '';
    public string $sku = '';
    public string $description = '';
    public float $price = 0;
    public int $stock = 0;
    public ?string $category_id = null;
    public string $meta_title = '';
    public string $meta_description = '';
    public $newImage;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $this->product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'newImage' => 'nullable|image|max:2048',
        ];
    }

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->description = $product->description ?? '';
        $this->price = (float) $product->price;
        $this->stock = (int) $product->stock;
        $this->category_id = $product->category_id;
        $this->meta_title = $product->meta_title ?? '';
        $this->meta_description = $product->meta_description ?? '';
    }

    public function save()
    {
        $this->validate();

        $this->product->update([
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

        if ($this->newImage) {
            $this->product->clearMediaCollection('products');
            $this->product->addMedia($this->newImage->getRealPath())
                ->usingFileName($this->newImage->getClientOriginalName())
                ->toMediaCollection('products');
        }

        session()->flash('message', 'Product succesvol bijgewerkt!');

        $tenant = app(\App\Services\TenantManager::class)->getTenant();

        return redirect()->route('tenant.products.manage', ['tenant' => $tenant->slug]);
    }

    public function render()
    {
        return view('livewire.tenant.products.edit', [
            'categories' => Category::all(),
        ]);
    }
}
