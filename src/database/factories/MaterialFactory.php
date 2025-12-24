<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'material_code' => fake()->unique()->bothify('MAT-####'),
            'name' => fake()->words(3, true),
            'category' => fake()->word(),
            'unit' => 'kg',
            'standard_price' => fake()->randomFloat(2, 1, 500),
            'lead_time' => fake()->randomFloat(2, 1, 60),
            'minimum_order_quantity' => fake()->randomFloat(2, 1, 1000),
            'safety_stock' => fake()->randomFloat(2, 0, 1000),
            'is_lot_managed' => fake()->boolean(),
            'has_expiry_management' => fake()->boolean(),
        ];
    }
}
