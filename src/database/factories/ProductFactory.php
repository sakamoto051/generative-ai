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
      'code' => $this->faker->unique()->ean8,
      'name' => $this->faker->word,
      'type' => 'product',
      'standard_cost' => $this->faker->randomFloat(2, 10, 100),
      'current_stock' => 0,
    ];
  }
}
