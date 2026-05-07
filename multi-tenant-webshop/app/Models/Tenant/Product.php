<?php

namespace App\Models\Tenant;

use App\Traits\HasTenantConnection;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, HasTenantConnection, HasUuid, SoftDeletes, InteractsWithMedia;

    protected $guarded = [];

    /**
     * Get the variations for the product.
     */
    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    /**
     * Determine if the product has variations.
     */
    public function getHasVariationsAttribute(): bool
    {
        return $this->variations()->exists();
    }

    /**
     * Get the total stock across all variations or from the product itself.
     */
    public function getTotalStockAttribute(): int
    {
        if ($this->has_variations) {
            return (int) $this->variations()->sum('stock');
        }

        return (int) $this->stock;
    }

    /**
     * Determine if the product is in stock.
     */
    public function getIsInStockAttribute(): bool
    {
        return $this->total_stock > 0;
    }

    /**
     * Decrement the stock of a simple product.
     * Note: If the product has variations, you should call decrementStock on the specific variation.
     *
     * @throws \Exception
     */
    public function decrementStock(int $quantity = 1): void
    {
        if ($this->has_variations) {
            throw new \Exception("Dit product heeft variaties. Verlaag de voorraad via de specifieke variatie.");
        }

        if ($this->stock < $quantity) {
            throw new \Exception("Onvoldoende voorraad voor product: {$this->name}");
        }

        $this->decrement('stock', $quantity);
    }

    /**
     * Increment the stock of a simple product.
     */
    public function incrementStock(int $quantity = 1): void
    {
        if ($this->has_variations) {
            throw new \Exception("Dit product heeft variaties. Verhoog de voorraad via de specifieke variatie.");
        }

        $this->increment('stock', $quantity);
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Register media conversions.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10);

        $this->addMediaConversion('large')
            ->width(1200)
            ->height(1200);
    }
}
