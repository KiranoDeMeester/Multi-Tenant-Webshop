<?php

namespace App\Livewire\Tenant\Coupons;

use App\Models\Tenant\Coupon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.tenant')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public bool $showModal = false;

    public ?string $editingCouponId = null;

    // Form fields
    public string $code = '';

    public string $type = 'percentage';

    public $value = '';

    public $min_order_amount = '';

    public $max_uses = '';

    public ?string $expires_at = null;

    public bool $is_active = true;

    protected function rules(): array
    {
        $uniqueRule = 'unique:coupons,code';
        if ($this->editingCouponId) {
            $uniqueRule .= ','.$this->editingCouponId;
        }

        return [
            'code' => ['required', 'string', 'max:50', $uniqueRule],
            'type' => ['required', 'in:percentage,fixed'],
            'value' => ['required', 'numeric', 'min:1'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'max_uses' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
            'is_active' => ['boolean'],
        ];
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $this->editingCouponId = $coupon->id;
        $this->code = $coupon->code;
        $this->type = $coupon->type;
        $this->value = $coupon->type === 'percentage' ? $coupon->value : ($coupon->value / 100);
        $this->min_order_amount = $coupon->min_order_amount ? ($coupon->min_order_amount / 100) : '';
        $this->max_uses = $coupon->max_uses ?? '';
        $this->expires_at = $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : null;
        $this->is_active = $coupon->is_active;

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'code' => strtoupper(trim($this->code)),
            'type' => $this->type,
            'value' => $this->type === 'percentage' ? (int) $this->value : (int) round($this->value * 100),
            'min_order_amount' => $this->min_order_amount ? (int) round($this->min_order_amount * 100) : null,
            'max_uses' => $this->max_uses ? (int) $this->max_uses : null,
            'expires_at' => $this->expires_at ? $this->expires_at.' 23:59:59' : null,
            'is_active' => $this->is_active,
        ];

        if ($this->editingCouponId) {
            $coupon = Coupon::findOrFail($this->editingCouponId);
            $coupon->update($data);
            $message = __('Kortingscode succesvol bijgewerkt.');
        } else {
            Coupon::create($data);
            $message = __('Kortingscode succesvol aangemaakt.');
        }

        $this->showModal = false;
        $this->resetForm();

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => $message,
        ]);
    }

    public function toggleActive(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update(['is_active' => ! $coupon->is_active]);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => __('Status van kortingscode gewijzigd.'),
        ]);
    }

    public function delete(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => __('Kortingscode verwijderd.'),
        ]);
    }

    protected function resetForm()
    {
        $this->editingCouponId = null;
        $this->code = '';
        $this->type = 'percentage';
        $this->value = '';
        $this->min_order_amount = '';
        $this->max_uses = '';
        $this->expires_at = null;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $coupons = Coupon::query()
            ->when($this->search, function ($q) {
                $q->where('code', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.tenant.coupons.index', [
            'coupons' => $coupons,
        ]);
    }
}
