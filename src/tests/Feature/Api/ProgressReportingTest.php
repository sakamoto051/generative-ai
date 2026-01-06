<?php

namespace Tests\Feature\Api;

use App\Models\ManufacturingOrder;
use App\Models\Product;
use App\Models\User;
use App\Models\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProgressReportingTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $operator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        
        $factory = Factory::factory()->create();
        
        $this->admin = User::factory()->create([
            'role_id' => 1, // System Administrator
            'factory_id' => $factory->id,
        ]);

        $this->operator = User::factory()->create([
            'role_id' => 3, // Manufacturing Leader
            'factory_id' => $factory->id,
        ]);
    }

    /** @test */
    public function it_can_report_progress_and_increment_inventory()
    {
        $product = Product::factory()->create();
        $mo = ManufacturingOrder::factory()->create([
            'product_id' => $product->id,
            'quantity' => 10,
            'status' => 'Released',
        ]);

        Sanctum::actingAs($this->operator);

        $response = $this->postJson("/api/manufacturing-orders/{$mo->id}/execute", [
            'good_quantity' => 4,
            'scrap_quantity' => 1,
            'actual_duration' => 60,
        ]);

        // JSON resource returns 201 for new records
        $response->assertStatus(201);
        
        // Check Inventory
        $this->assertDatabaseHas('inventories', [
            'item_id' => $product->id,
            'item_type' => Product::class,
            'quantity' => 4,
        ]);

        // Check MO Status
        $this->assertEquals('In Progress', $mo->fresh()->status);

        // Report remaining 6
        $this->postJson("/api/manufacturing-orders/{$mo->id}/execute", [
            'good_quantity' => 6,
            'operator_id' => $this->operator->id, // Should be passed or from auth
        ]);

        $this->assertEquals('Completed', $mo->fresh()->status);
        $this->assertEquals(10, $product->inventory->fresh()->quantity);
    }

    /** @test */
    public function it_can_get_execution_history()
    {
        $mo = ManufacturingOrder::factory()->create();
        $mo->executions()->create([
            'good_quantity' => 5,
            'operator_id' => $this->operator->id,
            'reported_at' => now(),
        ]);

        Sanctum::actingAs($this->operator);

        $response = $this->getJson("/api/manufacturing-orders/{$mo->id}/executions");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }
}