<?php

namespace App\Livewire\Tenant\Products;

use App\Models\Tenant\Attribute;
use App\Models\Tenant\AttributeValue;
use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use App\Models\Tenant\ProductVariation;
use App\Services\TenantManager;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

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

    // Variations
    public bool $has_variations = false;

    public array $variations = [];

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,'.$this->product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'newImage' => 'nullable|image|max:2048',
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

    public function mount(Product $product)
    {
        $this->product = $product->load('variations.attributeValues.attribute');
        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->description = $product->description ?? '';
        $this->price = (float) $product->price;
        $this->stock = (int) $product->stock;
        $this->category_id = $product->category_id;
        $this->meta_title = $product->meta_title ?? '';
        $this->meta_description = $product->meta_description ?? '';

        if ($product->variations->isNotEmpty()) {
            $this->has_variations = true;
            $this->variations = $product->variations->map(function ($variation) {
                $attrValue = $variation->attributeValues->first();

                return [
                    'id' => $variation->id,
                    'attribute_name' => $attrValue?->attribute?->name ?? 'Maat',
                    'attribute_value' => $attrValue?->value ?? '',
                    'sku' => $variation->sku,
                    'price' => $variation->price !== null ? (float) $variation->price : null,
                    'stock' => (int) $variation->stock,
                ];
            })->toArray();
        }
    }

    public function toggleVariations()
    {
        $this->has_variations = ! $this->has_variations;
        if ($this->has_variations && empty($this->variations)) {
            $this->addVariation();
        }
    }

    public function addVariation()
    {
        $count = count($this->variations) + 1;
        $baseSku = $this->sku ?: 'SKU-'.strtoupper(Str::random(4));
        $this->variations[] = [
            'id' => null,
            'attribute_name' => 'Maat',
            'attribute_value' => '',
            'sku' => $baseSku.'-V'.$count,
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

        $this->product->update([
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->has_variations ? 0 : $this->stock,
            'category_id' => $this->category_id,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ]);

        if ($this->has_variations) {
            $keptIds = [];
            foreach ($this->variations as $varData) {
                $attribute = Attribute::firstOrCreate([
                    'name' => trim($varData['attribute_name']),
                ]);

                $attrValue = AttributeValue::firstOrCreate([
                    'attribute_id' => $attribute->id,
                    'value' => trim($varData['attribute_value']),
                ]);

                if (! empty($varData['id'])) {
                    $variation = ProductVariation::where('product_id', $this->product->id)->find($varData['id']);
                    if ($variation) {
                        $variation->update([
                            'sku' => $varData['sku'],
                            'price' => ! empty($varData['price']) ? (float) $varData['price'] : null,
                            'stock' => (int) $varData['stock'],
                        ]);
                    }
                } else {
                    $variation = ProductVariation::create([
                        'product_id' => $this->product->id,
                        'sku' => $varData['sku'],
                        'price' => ! empty($varData['price']) ? (float) $varData['price'] : null,
                        'stock' => (int) $varData['stock'],
                    ]);
                }

                if ($variation) {
                    $variation->attributeValues()->sync([$attrValue->id]);
                    $keptIds[] = $variation->id;
                }
            }

            // Remove variations that were deleted
            ProductVariation::where('product_id', $this->product->id)
                ->whereNotIn('id', $keptIds)
                ->delete();
        } else {
            ProductVariation::where('product_id', $this->product->id)->delete();
        }

        if ($this->newImage) {
            $this->product->clearMediaCollection('products');
            $this->product->addMedia($this->newImage->getRealPath())
                ->usingFileName($this->newImage->getClientOriginalName())
                ->toMediaCollection('products');
        }

        session()->flash('message', __('Product succesvol bijgewerkt!'));

        $tenant = app(TenantManager::class)->getTenant();

        return redirect()->route('tenant.products.manage', ['tenant' => $tenant->slug]);
    }

    public function render()
    {
        return view('livewire.tenant.products.edit', [
            'categories' => Category::all(),
        ]);
    }
}
