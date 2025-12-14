<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductionPlan>
 */
class ProductionPlanFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'plan_number' => 'PP-' . $this->faker->unique()->numberBetween(1000, 9999),
      'period_start' => now(),
      'period_end' => now()->addMonth(),
      'status' => 'draft',
      'creator_id' => \App\Models\User::factory(),
      'description' => $this->faker->sentence,
    ];
  }
}
