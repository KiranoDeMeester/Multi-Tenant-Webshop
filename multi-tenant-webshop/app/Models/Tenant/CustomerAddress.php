<?php

namespace App\Models\Tenant;

use App\Traits\HasTenantConnection;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    use HasFactory, HasTenantConnection, HasUuid;

    protected $guarded = [];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
