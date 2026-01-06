<?php

namespace Database\Factories;

use App\Models\ManufacturingOrder;
use App\Models\Material;
use App\Models\MoComponent;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MoComponent>
 */
class MoComponentFactory extends Factory
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
            'item_id' => Material::factory(),
            'item_type' => Material::class,
            'required_quantity' => $this->faker->randomFloat(2, 1, 100),
            'unit' => 'pcs',
        ];
    }

    /**
     * Set the item to a product.
     */
    public function product(): static
    {
        return $this->state(fn (array $attributes) => [
            'item_id' => Product::factory(),
            'item_type' => Product::class,
        ]);
    }
}