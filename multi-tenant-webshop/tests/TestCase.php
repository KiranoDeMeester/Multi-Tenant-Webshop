<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Fortify\Features;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        if ($this->app) {
            $manager = app(\App\Services\TenantManager::class);
            if (method_exists($manager, 'reset') && !($manager instanceof \Mockery\MockInterface)) {
                $manager->reset();
            }
        }
        
        parent::tearDown();
    }

    protected function migrateLandlord(): void
    {
        $this->artisan('migrate', [
            '--database' => 'landlord',
            '--path' => 'database/migrations/landlord',
            '--realpath' => true,
        ]);
    }

    protected function migrateTenant(): void
    {
        $this->artisan('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
        ]);
    }

    protected function skipUnlessFortifyHas(string $feature, ?string $message = null): void
    {
        if (! Features::enabled($feature)) {
            $this->markTestSkipped($message ?? "Fortify feature [{$feature}] is not enabled.");
        }
    }
}
