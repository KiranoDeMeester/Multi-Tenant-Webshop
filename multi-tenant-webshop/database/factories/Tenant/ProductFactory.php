<?php

namespace Database\Factories\Tenant;

use App\Models\AppModelsTenantProduct;
use App\Models\Tenant\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'id' => Str::uuid(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'sku' => strtoupper($this->faker->unique()->bothify('??-#####')),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $this->faker->randomFloat(2, 5, 500),
            'stock' => $this->faker->numberBetween(0, 100),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'image_url' => 'https://picsum.photos/seed/'.Str::random(10).'/800/1000',
        ];
    }
}
