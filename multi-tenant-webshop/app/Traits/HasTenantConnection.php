<?php

namespace App\Traits;

trait HasTenantConnection
{
    /**
     * Get the current connection name for the model.
     *
     * @return string|null
     */
    public function getConnectionName()
    {
        return 'tenant';
    }
}
