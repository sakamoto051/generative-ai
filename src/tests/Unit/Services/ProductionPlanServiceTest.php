<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\ProductionPlan;
use App\Models\ProductionPlanItem;
use App\Models\Product;
use App\Models\BomItem;
use App\Models\User;
use App\Services\ProductionPlanService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductionPlanServiceTest extends TestCase
{
  use RefreshDatabase;

  private ProductionPlanService $service;

  protected function setUp(): void
  {
    parent::setUp();
    $this->service = new ProductionPlanService();
  }

  public function test_calculate_material_requirements_simple()
  {
    $user = User::factory()->create();

    // 1. Create Products
    $fp = Product::create(['code' => 'FP-01', 'name' => 'Finished Product', 'type' => 'product']);
    $rm = Product::create(['code' => 'RM-01', 'name' => 'Raw Material', 'type' => 'material']);

    // 2. Create BOM (1 FP requires 2 RM)
    BomItem::create([
      'parent_product_id' => $fp->id,
      'child_product_id' => $rm->id,
      'quantity' => 2,
      'yield_rate' => 1.0,
    ]);

    // 3. Create Plan
    $plan = ProductionPlan::create([
      'plan_number' => 'TEST-001',
      'period_start' => now(),
      'period_end' => now()->addMonth(),
      'creator_id' => $user->id,
    ]);

    // 4. Create Plan Item (Produce 10 FP)
    ProductionPlanItem::create([
      'production_plan_id' => $plan->id,
      'product_id' => $fp->id,
      'quantity' => 10,
    ]);

    // 5. Execute Calculation
    $requirements = $this->service->calculateMaterialRequirements($plan);

    // 6. Assertions
    $this->assertCount(1, $requirements);
    $this->assertEquals('RM-01', $requirements[0]['code']);
    $this->assertEquals(20, $requirements[0]['total_quantity']); // 10 * 2 = 20
  }

  public function test_calculate_material_requirements_with_yield()
  {
    $user = User::factory()->create();

    // 1. Create Products
    $fp = Product::create(['code' => 'FP-02', 'name' => 'Finished Product 2', 'type' => 'product']);
    $rm = Product::create(['code' => 'RM-02', 'name' => 'Raw Material 2', 'type' => 'material']);

    // 2. Create BOM (1 FP requires 2 RM, yield 0.5 - huge waste case)
    BomItem::create([
      'parent_product_id' => $fp->id,
      'child_product_id' => $rm->id,
      'quantity' => 2,
      'yield_rate' => 0.5,
    ]);

    // 3. Create Plan
    $plan = ProductionPlan::create([
      'plan_number' => 'TEST-002',
      'period_start' => now(),
      'period_end' => now()->addMonth(),
      'creator_id' => $user->id,
    ]);

    ProductionPlanItem::create([
      'production_plan_id' => $plan->id,
      'product_id' => $fp->id,
      'quantity' => 10,
    ]);

    $requirements = $this->service->calculateMaterialRequirements($plan);

    $this->assertCount(1, $requirements);
    // Needed = (PlanQty * BomQty) / Yield
    // Needed = (10 * 2) / 0.5 = 40
    // Needed = (10 * 2) / 0.5 = 40
    $this->assertEquals(40, $requirements[0]['total_quantity']);
  }

  public function test_update_plan()
  {
    $user = User::factory()->create();

    $fp1 = Product::create(['code' => 'FP-01', 'name' => 'FP 1', 'type' => 'product']);
    $fp2 = Product::create(['code' => 'FP-02', 'name' => 'FP 2', 'type' => 'product']);

    // Create Initial Plan
    $planService = new ProductionPlanService();
    $planData = [
      'period_start' => '2023-01-01',
      'period_end' => '2023-01-31',
      'description' => 'Initial Plan',
      'items' => [
        ['product_id' => $fp1->id, 'quantity' => 100]
      ]
    ];
    $plan = $planService->createPlan($planData, $user->id);

    $this->assertEquals('Initial Plan', $plan->description);
    $this->assertCount(1, $plan->items);
    $this->assertEquals(100, $plan->items->first()->quantity);

    // Update Plan
    $updateData = [
      'period_start' => '2023-02-01',
      'period_end' => '2023-02-28',
      'description' => 'Updated Plan',
      'items' => [
        ['product_id' => $fp2->id, 'quantity' => 200]
      ]
    ];

    $updatedPlan = $planService->updatePlan($plan, $updateData);

    $this->assertEquals('Updated Plan', $updatedPlan->description);
    $this->assertEquals('2023-02-01', $updatedPlan->period_start);
    $this->assertCount(1, $updatedPlan->items);
    $this->assertEquals($fp2->id, $updatedPlan->items->first()->product_id);
    $this->assertEquals(200, $updatedPlan->items->first()->quantity);
  }
}
