<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Setting extends Model implements HasMedia
{
    use \Illuminate\Database\Eloquent\Concerns\HasUuids, InteractsWithMedia;

    protected $guarded = [];

    public $incrementing = false;
    protected $keyType = 'string';

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('hero')
            ->width(1600)
            ->height(800)
            ->sharpen(10);
            
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300);
    }
}
