<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
        return [
            'product_code' => fake()->unique()->bothify('PROD-####'),
            'name' => fake()->words(3, true),
            'category' => fake()->word(),
            'unit' => 'pcs',
            'standard_cost' => fake()->randomFloat(2, 10, 1000),
            'standard_manufacturing_time' => fake()->randomFloat(2, 1, 100),
            'lead_time' => fake()->randomFloat(2, 1, 30),
            'safety_stock' => fake()->randomFloat(2, 0, 500),
            'reorder_point' => fake()->randomFloat(2, 0, 200),
        ];
    }
}
