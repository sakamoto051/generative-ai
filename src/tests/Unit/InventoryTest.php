<?php

namespace Tests\Unit;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Material;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_inventory_for_product()
    {
        $product = Product::factory()->create();
        
        $inventory = Inventory::create([
            'item_id' => $product->id,
            'item_type' => Product::class,
            'quantity' => 100,
            'location' => 'Warehouse A',
        ]);

        $this->assertDatabaseHas('inventories', [
            'id' => $inventory->id,
            'item_id' => $product->id,
            'item_type' => Product::class,
            'quantity' => 100,
        ]);
    }

    /** @test */
    public function product_has_inventory_relationship()
    {
        $product = Product::factory()->create();
        $inventory = Inventory::create([
            'item_id' => $product->id,
            'item_type' => Product::class,
            'quantity' => 50,
        ]);

        $this->assertInstanceOf(Inventory::class, $product->inventory);
        $this->assertEquals(50, $product->inventory->quantity);
    }

    /** @test */
    public function material_has_inventory_relationship()
    {
        $material = Material::factory()->create();
        $inventory = Inventory::create([
            'item_id' => $material->id,
            'item_type' => Material::class,
            'quantity' => 200,
        ]);

        $this->assertInstanceOf(Inventory::class, $material->inventory);
        $this->assertEquals(200, $material->inventory->quantity);
    }
    
    /** @test */
    public function inventory_belongs_to_item()
    {
        $product = Product::factory()->create();
        $inventory = Inventory::create([
            'item_id' => $product->id,
            'item_type' => Product::class,
            'quantity' => 10,
        ]);
        
        $this->assertInstanceOf(Product::class, $inventory->item);
        $this->assertEquals($product->id, $inventory->item->id);
    }
}