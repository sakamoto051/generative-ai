<?php

namespace Database\Factories;

use App\Models\ManufacturingOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ManufacturingExecution>
 */
class ManufacturingExecutionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'manufacturing_order_id' => ManufacturingOrder::factory(),
            'good_quantity' => $this->faker->randomFloat(2, 1, 100),
            'scrap_quantity' => $this->faker->randomFloat(2, 0, 10),
            'actual_duration' => $this->faker->numberBetween(10, 480), // in minutes
            'operator_id' => User::factory(),
            'reported_at' => now(),
        ];
    }
}