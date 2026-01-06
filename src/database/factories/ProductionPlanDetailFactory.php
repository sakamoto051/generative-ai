<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductionPlanDetail>
 */
class ProductionPlanDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'production_plan_id' => \App\Models\ProductionPlan::factory(),
            'product_id' => \App\Models\Product::factory(),
            'quantity' => $this->faker->randomFloat(2, 10, 1000),
            'due_date' => now()->addDays(14)->toDateString(),
            'priority' => $this->faker->numberBetween(1, 5),
            'remarks' => $this->faker->text(100),
        ];
    }
}
