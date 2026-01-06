<?php

namespace Tests\Feature\Api;

use App\Models\Bom;
use App\Models\Factory;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MrpControllerTest extends TestCase
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

    public function test_can_calculate_mrp()
    {
        // Setup: A -> 2x B
        $productA = Product::factory()->create(['product_code' => 'A']);
        $productB = Product::factory()->create(['product_code' => 'B']);

        Bom::create([
            'parent_id' => $productA->id,
            'parent_type' => Product::class,
            'child_id' => $productB->id,
            'child_type' => Product::class,
            'quantity' => 2,
        ]);

        // Inventory B: 5 units
        Inventory::create([
            'item_id' => $productB->id,
            'item_type' => Product::class,
            'quantity' => 5,
        ]);

        Sanctum::actingAs($this->admin);

        // Request 10 of Product A
        // Total B needed: 10 * 2 = 20
        // Inventory B: 5
        // Net B needed: 15

        $response = $this->postJson('/api/mrp/calculate', [
            'product_id' => $productA->id,
            'quantity' => 10,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.item_code', 'B')
            ->assertJsonPath('data.0.total_requirement', 20)
            ->assertJsonPath('data.0.inventory_applied', 5)
            ->assertJsonPath('data.0.net_requirement', 15);
    }

    public function test_fails_with_invalid_input()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson('/api/mrp/calculate', [
            'product_id' => 9999, // Non-existent
            'quantity' => -1,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['product_id', 'quantity']);
    }
}
