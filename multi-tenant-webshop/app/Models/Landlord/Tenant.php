<?php

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory;

    protected $connection = 'landlord';

    protected $fillable = [
        'name',
        'db_name',
        'stripe_account_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tenant) {
            if (!$tenant->id) {
                $tenant->id = (string) Str::uuid();
            }
        });
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    /**
     * Get the tenant's slug (domain prefix).
     */
    public function getSlugAttribute(): string
    {
        return Str::before($this->domains->first()?->domain ?? '', '.');
    }
}
