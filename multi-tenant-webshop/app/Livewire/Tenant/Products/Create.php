<?php

namespace App\Livewire\Tenant\Products;

use App\Models\Tenant\Attribute;
use App\Models\Tenant\AttributeValue;
use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use App\Models\Tenant\ProductVariation;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

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

    // Variations
    public bool $has_variations = false;
    public array $variations = [];

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048',
        ];

        if ($this->has_variations) {
            $rules['variations'] = 'required|array|min:1';
            $rules['variations.*.attribute_name'] = 'required|string|max:50';
            $rules['variations.*.attribute_value'] = 'required|string|max:50';
            $rules['variations.*.sku'] = 'required|string|max:50';
            $rules['variations.*.stock'] = 'required|integer|min:0';
            $rules['variations.*.price'] = 'nullable|numeric|min:0';
        }

        return $rules;
    }

    public function toggleVariations()
    {
        $this->has_variations = !$this->has_variations;
        if ($this->has_variations && empty($this->variations)) {
            $this->addVariation();
        }
    }

    public function addVariation()
    {
        $count = count($this->variations) + 1;
        $baseSku = $this->sku ?: 'SKU-' . strtoupper(Str::random(4));
        $this->variations[] = [
            'attribute_name' => 'Maat',
            'attribute_value' => '',
            'sku' => $baseSku . '-V' . $count,
            'price' => $this->price > 0 ? $this->price : null,
            'stock' => 5,
        ];
    }

    public function removeVariation(int $index)
    {
        unset($this->variations[$index]);
        $this->variations = array_values($this->variations);
    }

    public function save()
    {
        $this->validate();

        $product = Product::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name) . '-' . Str::random(5),
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->has_variations ? 0 : $this->stock,
            'category_id' => $this->category_id,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ]);

        if ($this->has_variations) {
            foreach ($this->variations as $varData) {
                $attribute = Attribute::firstOrCreate([
                    'name' => trim($varData['attribute_name']),
                ]);

                $attrValue = AttributeValue::firstOrCreate([
                    'attribute_id' => $attribute->id,
                    'value' => trim($varData['attribute_value']),
                ]);

                $variation = ProductVariation::create([
                    'product_id' => $product->id,
                    'sku' => $varData['sku'],
                    'price' => !empty($varData['price']) ? (float) $varData['price'] : null,
                    'stock' => (int) $varData['stock'],
                ]);

                $variation->attributeValues()->sync([$attrValue->id]);
            }
        }

        if ($this->image) {
            $product->addMedia($this->image->getRealPath())
                ->usingFileName($this->image->getClientOriginalName())
                ->toMediaCollection('products');
        }

        session()->flash('message', __('Product succesvol aangemaakt!'));

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
