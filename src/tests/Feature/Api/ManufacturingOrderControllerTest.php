<?php

namespace Tests\Feature\Api;

use App\Models\Bom;
use App\Models\ManufacturingOrder;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductionPlan;
use App\Models\ProductionPlanDetail;
use App\Models\User;
use App\Models\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ManufacturingOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $manager;
    protected $leader;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        
        $factory = Factory::factory()->create();
        
        $this->admin = User::factory()->create([
            'role_id' => 1, // System Administrator
            'factory_id' => $factory->id,
        ]);

        $this->manager = User::factory()->create([
            'role_id' => 2, // Production Manager
            'factory_id' => $factory->id,
        ]);

        $this->leader = User::factory()->create([
            'role_id' => 3, // Manufacturing Leader
            'factory_id' => $factory->id,
        ]);
    }

    /** @test */
    public function it_can_release_mo_from_approved_production_plan()
    {
        // Setup: Product with BOM
        $product = Product::factory()->create();
        $material = Material::factory()->create(['unit' => 'kg']);
        Bom::create([
            'parent_id' => $product->id,
            'parent_type' => Product::class,
            'child_id' => $material->id,
            'child_type' => Material::class,
            'quantity' => 2,
        ]);

        // Setup: Approved plan
        $plan = ProductionPlan::factory()->create(['status' => 'Approved']);
        ProductionPlanDetail::factory()->create([
            'production_plan_id' => $plan->id,
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        Sanctum::actingAs($this->admin);

        $response = $this->postJson("/api/production-plans/{$plan->id}/release");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.status', 'Planned')
            ->assertJsonPath('data.0.components.0.item_id', $material->id)
            ->assertJsonPath('data.0.components.0.required_quantity', 20); // 10 * 2
    }

    /** @test */
    public function it_can_list_manufacturing_orders()
    {
        ManufacturingOrder::factory()->count(3)->create();

        Sanctum::actingAs($this->leader);

        $response = $this->getJson('/api/manufacturing-orders');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_update_mo_status_with_correct_transition()
    {
        $mo = ManufacturingOrder::factory()->create(['status' => 'Planned']);

        Sanctum::actingAs($this->leader);

        // Transition: Planned -> Released
        $response = $this->patchJson("/api/manufacturing-orders/{$mo->id}/status", [
            'status' => 'Released'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('manufacturing_orders', [
            'id' => $mo->id,
            'status' => 'Released'
        ]);
    }

    /** @test */
    public function it_fails_on_invalid_status_transition()
    {
        $mo = ManufacturingOrder::factory()->create(['status' => 'Planned']);

        Sanctum::actingAs($this->leader);

        // Transition: Planned -> Completed (Invalid, must go through Released -> In Progress)
        $response = $this->patchJson("/api/manufacturing-orders/{$mo->id}/status", [
            'status' => 'Completed'
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function only_managers_and_admins_can_release_orders()
    {
        $plan = ProductionPlan::factory()->create(['status' => 'Approved']);

        // Manufacturing Leader should NOT be able to release orders (only Managers and Admins)
        Sanctum::actingAs($this->leader);

        $response = $this->postJson("/api/production-plans/{$plan->id}/release");

        $response->assertStatus(403);
    }
}