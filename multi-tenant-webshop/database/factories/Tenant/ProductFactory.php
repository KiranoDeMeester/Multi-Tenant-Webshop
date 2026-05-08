<?php

namespace Database\Factories\Tenant;

use App\Models\AppModelsTenantProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AppModelsTenantProduct>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            'id' => \Illuminate\Support\Str::uuid(),
            'name' => ucfirst($name),
            'slug' => \Illuminate\Support\Str::slug($name),
            'sku' => strtoupper($this->faker->unique()->bothify('??-#####')),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $this->faker->randomFloat(2, 5, 500),
            'stock' => $this->faker->numberBetween(0, 100),
            'category_id' => \App\Models\Tenant\Category::inRandomOrder()->first()?->id ?? \App\Models\Tenant\Category::factory(),
            'image_url' => 'https://picsum.photos/seed/' . \Illuminate\Support\Str::random(10) . '/800/1000',
        ];
    }
}
