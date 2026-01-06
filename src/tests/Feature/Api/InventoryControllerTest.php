<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\Material;
use App\Models\User;
use App\Models\Factory;
use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class InventoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $factory = Factory::create(['name' => 'Main Factory']);
        $this->admin = User::factory()->create([
            'role_id' => 1, // System Administrator
            'factory_id' => $factory->id,
        ]);
    }

    public function test_can_set_inventory_for_product()
    {
        $product = Product::factory()->create();

        Sanctum::actingAs($this->admin);

        $response = $this->postJson('/api/inventories', [
            'item_id' => $product->id,
            'item_type' => 'product',
            'quantity' => 100,
            'location' => 'A-1',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('inventories', [
            'item_id' => $product->id,
            'item_type' => Product::class,
            'quantity' => 100,
            'location' => 'A-1',
        ]);
    }

    public function test_can_update_existing_inventory()
    {
        $product = Product::factory()->create();
        Inventory::create([
            'item_id' => $product->id,
            'item_type' => Product::class,
            'quantity' => 50,
        ]);

        Sanctum::actingAs($this->admin);

        $response = $this->postJson('/api/inventories', [
            'item_id' => $product->id,
            'item_type' => 'product',
            'quantity' => 75,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('inventories', [
            'item_id' => $product->id,
            'quantity' => 75,
        ]);
        // Ensure count is still 1
        $this->assertEquals(1, Inventory::count());
    }
}
