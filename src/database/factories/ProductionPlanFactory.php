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
            'plan_code' => $this->faker->unique()->bothify('PLAN-####'),
            'name' => $this->faker->sentence(3),
            'start_date' => now()->addDays(7)->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'status' => 'Draft',
            'created_by' => \App\Models\User::factory(),
        ];
    }
}
