<?php

use App\Models\Concerns\BelongsToTenant;
use App\Models\Landlord\Tenant;
use App\Services\TenantManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

// Dummy model for testing the scope
class TestTenantModel extends Model
{
    use BelongsToTenant;
    protected $table = 'test_tenant_models';
    protected $fillable = ['name', 'tenant_id'];
}

// uses(RefreshDatabase::class);

beforeEach(function () {
    $this->migrateLandlord();
    
    Schema::create('test_tenant_models', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->uuid('tenant_id');
        $table->timestamps();
    });
});

test('it filters models by active tenant', function () {
    $tenant1 = Tenant::factory()->create(['id' => '11111111-1111-1111-1111-111111111111']);
    $tenant2 = Tenant::factory()->create(['id' => '22222222-2222-2222-2222-222222222222']);

    TestTenantModel::create(['name' => 'Model 1', 'tenant_id' => $tenant1->id]);
    TestTenantModel::create(['name' => 'Model 2', 'tenant_id' => $tenant2->id]);

    // Mock TenantManager to return tenant 1
    $this->mock(TenantManager::class, function ($mock) use ($tenant1) {
        $mock->shouldReceive('getTenant')->andReturn($tenant1);
    });

    expect(TestTenantModel::all())->toHaveCount(1);
    expect(TestTenantModel::first()->name)->toBe('Model 1');

    // Mock TenantManager to return tenant 2
    $this->mock(TenantManager::class, function ($mock) use ($tenant2) {
        $mock->shouldReceive('getTenant')->andReturn($tenant2);
    });

    expect(TestTenantModel::all())->toHaveCount(1);
    expect(TestTenantModel::first()->name)->toBe('Model 2');
});

test('it automatically sets tenant_id on creation', function () {
    $tenant = Tenant::factory()->create(['id' => '33333333-3333-3333-3333-333333333333']);
    
    // Mock TenantManager to return the tenant
    $this->mock(TenantManager::class, function ($mock) use ($tenant) {
        $mock->shouldReceive('getTenant')->andReturn($tenant);
    });

    $model = TestTenantModel::create(['name' => 'Auto Model']);

    expect($model->tenant_id)->toBe($tenant->id);
});
