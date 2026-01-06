<?php

namespace Database\Factories;

use App\Models\ManufacturingOrder;
use App\Models\Product;
use App\Models\ProductionPlanDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ManufacturingOrder>
 */
class ManufacturingOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mo_number' => $this->faker->unique()->bothify('MO-2026-####'),
            'production_plan_detail_id' => ProductionPlanDetail::factory(),
            'product_id' => Product::factory(),
            'quantity' => $this->faker->randomFloat(2, 10, 1000),
            'due_date' => $this->faker->date(),
            'status' => 'Planned',
            'created_by' => User::factory(),
        ];
    }
}