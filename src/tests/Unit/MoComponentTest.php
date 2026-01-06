<?php

namespace Tests\Unit;

use App\Models\ManufacturingOrder;
use App\Models\Material;
use App\Models\MoComponent;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoComponentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_an_order()
    {
        $mo = ManufacturingOrder::factory()->create();
        $component = MoComponent::factory()->create(['manufacturing_order_id' => $mo->id]);

        $this->assertInstanceOf(ManufacturingOrder::class, $component->order);
        $this->assertEquals($mo->id, $component->order->id);
    }

    /** @test */
    public function it_belongs_to_a_polymorphic_item()
    {
        // Case 1: Material
        $material = Material::factory()->create();
        $component1 = MoComponent::factory()->create([
            'item_id' => $material->id,
            'item_type' => Material::class,
        ]);

        $this->assertInstanceOf(Material::class, $component1->item);
        $this->assertEquals($material->id, $component1->item->id);

        // Case 2: Product
        $product = Product::factory()->create();
        $component2 = MoComponent::factory()->create([
            'item_id' => $product->id,
            'item_type' => Product::class,
        ]);

        $this->assertInstanceOf(Product::class, $component2->item);
        $this->assertEquals($product->id, $component2->item->id);
    }
}