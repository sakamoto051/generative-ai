<?php

namespace Tests\Feature\Api;

use App\Models\ProductionPlan;
use App\Models\Product;
use App\Models\User;
use App\Models\Factory;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductionPlanControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $factory = Factory::factory()->create();
        $this->admin = User::factory()->create([
            'role_id' => 1, // System Administrator
            'factory_id' => $factory->id,
        ]);
    }

    /** @test */
    public function it_can_list_production_plans()
    {
        ProductionPlan::factory()->count(3)->create();

        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/production-plans');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_show_a_production_plan_with_details()
    {
        $plan = ProductionPlan::factory()->hasDetails(2)->create();

        Sanctum::actingAs($this->admin);

        $response = $this->getJson("/api/production-plans/{$plan->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $plan->id)
            ->assertJsonCount(2, 'data.details');
    }

    /** @test */
    public function it_can_create_a_production_plan_with_details()
    {
        $product = Product::factory()->create();

        Sanctum::actingAs($this->admin);

        $data = [
            'plan_code' => 'PLAN-2026-01',
            'name' => 'January 2026 Plan',
            'start_date' => '2026-01-01',
            'end_date' => '2026-01-31',
            'details' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 100,
                    'due_date' => '2026-01-15',
                    'priority' => 1,
                    'remarks' => 'High priority',
                ]
            ],
        ];

        $response = $this->postJson('/api/production-plans', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('production_plans', ['plan_code' => 'PLAN-2026-01']);
        $this->assertDatabaseHas('production_plan_details', ['quantity' => 100]);
    }

    /** @test */
    public function it_can_update_a_draft_production_plan()
    {
        $plan = ProductionPlan::factory()->create(['status' => 'Draft', 'name' => 'Old Name']);

        Sanctum::actingAs($this->admin);

        $response = $this->putJson("/api/production-plans/{$plan->id}", [
            'name' => 'New Name',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('production_plans', ['id' => $plan->id, 'name' => 'New Name']);
    }

    /** @test */
    public function it_cannot_update_a_non_draft_production_plan()
    {
        $plan = ProductionPlan::factory()->create(['status' => 'Approved']);

        Sanctum::actingAs($this->admin);

        $response = $this->putJson("/api/production-plans/{$plan->id}", [
            'name' => 'New Name',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_can_delete_a_draft_production_plan()
    {
        $plan = ProductionPlan::factory()->create(['status' => 'Draft']);

        Sanctum::actingAs($this->admin);

        $response = $this->deleteJson("/api/production-plans/{$plan->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('production_plans', ['id' => $plan->id]);
    }
}