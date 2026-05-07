<?php

namespace Database\Factories\Landlord;

use App\Models\Landlord\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Landlord\Tenant>
 */
class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'db_name' => 'tenant_' . strtolower(\Illuminate\Support\Str::random(10)),
            'stripe_account_id' => 'acct_' . $this->faker->unique()->lexify('????????????'),
        ];
    }
}
