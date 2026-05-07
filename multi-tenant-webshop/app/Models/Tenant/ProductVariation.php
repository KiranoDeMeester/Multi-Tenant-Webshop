<?php

namespace App\Models\Tenant;

use App\Traits\HasTenantConnection;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariation extends Model
{
    use HasFactory, HasTenantConnection, HasUuid;

    protected $guarded = [];

    /**
     * Get the product that owns the variation.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the attribute values for the variation.
     */
    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variation_attribute_value');
    }

    /**
     * Get the actual price of the variation.
     * Falls back to the product price if not set.
     */
    public function getEffectivePriceAttribute(): float
    {
        return $this->price ?? $this->product->price;
    }

    /**
     * Determine if the variation is in stock.
     */
    public function getIsInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Decrement the stock of the variation.
     *
     * @throws \Exception
     */
    public function decrementStock(int $quantity = 1): void
    {
        if ($this->stock < $quantity) {
            throw new \Exception("Onvoldoende voorraad voor variatie: {$this->sku}");
        }

        $this->decrement('stock', $quantity);
    }

    /**
     * Increment the stock of the variation.
     */
    public function incrementStock(int $quantity = 1): void
    {
        $this->increment('stock', $quantity);
    }
}
