<?php

namespace Database\Factories\Tenant;

use App\Models\AppModelsTenantCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AppModelsTenantCategory>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->word();
        return [
            'id' => \Illuminate\Support\Str::uuid(),
            'name' => ucfirst($name),
            'slug' => \Illuminate\Support\Str::slug($name),
            'description' => $this->faker->sentence(),
        ];
    }
}
