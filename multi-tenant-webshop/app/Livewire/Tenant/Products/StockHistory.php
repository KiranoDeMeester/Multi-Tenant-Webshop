<?php

namespace App\Livewire\Tenant\Products;

use App\Models\Tenant\Product;
use App\Models\Tenant\StockMutation;
use App\Services\StockService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.tenant')]
class StockHistory extends Component
{
    use WithPagination;

    public string $search = '';
    public string $typeFilter = '';
    public ?string $selectedProductId = null;

    // Manual Stock Adjustment Form
    public bool $showAdjustModal = false;
    public ?string $adjustProductId = null;
    public ?string $adjustVariationId = null;
    public int $adjustDelta = 0;
    public string $adjustType = 'adjustment';
    public string $adjustReason = '';

    protected $rules = [
        'adjustProductId' => 'required|exists:products,id',
        'adjustDelta' => 'required|integer|not_in:0',
        'adjustType' => 'required|in:purchase,adjustment,return',
        'adjustReason' => 'nullable|string|max:255',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function openAdjustModal(?string $productId = null)
    {
        $this->adjustProductId = $productId;
        $this->adjustVariationId = null;
        $this->adjustDelta = 0;
        $this->adjustType = 'adjustment';
        $this->adjustReason = '';
        $this->showAdjustModal = true;
    }

    public function saveAdjustment(StockService $stockService)
    {
        $this->validate();

        $product = Product::findOrFail($this->adjustProductId);
        $variation = null;
        if ($this->adjustVariationId) {
            $variation = $product->variations()->find($this->adjustVariationId);
        }

        try {
            $stockService->adjustStock(
                product: $product,
                variation: $variation,
                delta: $this->adjustDelta,
                type: $this->adjustType,
                description: $this->adjustReason ?: 'Handmatige voorraadcorrectie door beheerder'
            );

            $this->showAdjustModal = false;
            session()->flash('message', __('Voorraad succesvol aangepast en gelogd.'));
        } catch (\Exception $e) {
            $this->addError('adjustDelta', $e->getMessage());
        }
    }

    public function render()
    {
        $mutations = StockMutation::query()
            ->with(['product', 'variation.attributeValues.attribute', 'order'])
            ->when($this->search, function ($q) {
                $q->whereHas('product', function ($pq) {
                    $pq->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('sku', 'like', '%' . $this->search . '%');
                })->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->typeFilter, function ($q) {
                $q->where('type', $this->typeFilter);
            })
            ->when($this->selectedProductId, function ($q) {
                $q->where('product_id', $this->selectedProductId);
            })
            ->latest()
            ->paginate(15);

        $products = Product::with('variations.attributeValues.attribute')->get();

        return view('livewire.tenant.products.stock-history', [
            'mutations' => $mutations,
            'products' => $products,
        ]);
    }
}
