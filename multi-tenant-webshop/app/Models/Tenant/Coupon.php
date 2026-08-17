<?php

namespace App\Models\Tenant;

use App\Traits\HasTenantConnection;
use App\Traits\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory, HasTenantConnection, HasUuid;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'value' => 'integer',
        'min_order_amount' => 'integer',
        'max_uses' => 'integer',
        'used_count' => 'integer',
    ];

    /**
     * Scope active coupons.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', Carbon::now());
            })
            ->where(function ($q) {
                $q->whereNull('max_uses')
                    ->orWhereColumn('used_count', '<', 'max_uses');
            });
    }

    /**
     * Check if coupon is valid for a given subtotal in cents.
     */
    public function isValidForAmount(int $subtotalInCents): array
    {
        if (! $this->is_active) {
            return ['valid' => false, 'message' => __('Deze kortingscode is niet meer actief.')];
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return ['valid' => false, 'message' => __('Deze kortingscode is verlopen.')];
        }

        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return ['valid' => false, 'message' => __('Deze kortingscode heeft het maximaal aantal gebruiken bereikt.')];
        }

        if ($this->min_order_amount && $subtotalInCents < $this->min_order_amount) {
            $minFormatted = number_format($this->min_order_amount / 100, 2, ',', '.');

            return ['valid' => false, 'message' => __("Minimaal bestelbedrag van €{$minFormatted} vereist voor deze kortingscode.")];
        }

        return ['valid' => true, 'message' => null];
    }

    /**
     * Calculate discount amount in cents for a given subtotal.
     */
    public function calculateDiscount(int $subtotalInCents): int
    {
        if ($this->type === 'percentage') {
            $discount = (int) round(($subtotalInCents * $this->value) / 100);
        } else {
            // Fixed amount in cents
            $discount = (int) $this->value;
        }

        return min($discount, $subtotalInCents);
    }
}
