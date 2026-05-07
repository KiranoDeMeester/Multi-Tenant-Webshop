<?php

namespace App\Services\Media;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class TenantPathGenerator implements PathGenerator
{
    /*
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media).'/';
    }

    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media).'/conversions/';
    }

    /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media).'/responsive-images/';
    }

    /*
     * Get the unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        // Get the tenant ID. 
        // Note: The media record itself belongs to a model that is on a tenant connection.
        // We can try to get the tenant from the context.
        $tenantId = tenant()?->id ?? 'default';

        return "tenants/{$tenantId}/media/{$media->getKey()}";
    }
}
