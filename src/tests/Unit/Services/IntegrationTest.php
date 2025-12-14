<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\ProductionPlan;
use App\Models\ProductionPlanItem;
use App\Models\Product;
use App\Models\BomItem;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Services\ProductionPlanService;
use App\Services\PurchaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IntegrationTest extends TestCase
{
  use RefreshDatabase;

  private ProductionPlanService $planService;
  private PurchaseService $purchaseService;

  protected function setUp(): void
  {
    parent::setUp();
    $this->planService = new ProductionPlanService();
    $this->purchaseService = new PurchaseService();
  }

  public function test_production_to_purchase_flow()
  {
    // 1. Setup Data
    // Product (Manufacturing Item)
    $product = Product::create([
      'code' => 'FP-100',
      'name' => 'Finished Product',
      'type' => 'product',
      'standard_cost' => 0
    ]);

    // Material A (Buy Item) - supplied by Supplier A
    $materialA = Product::create([
      'code' => 'RM-100',
      'name' => 'Raw Material A',
      'type' => 'material',
      'standard_cost' => 100
    ]);

    // Material B (Buy Item)
    $materialB = Product::create([
      'code' => 'RM-200',
      'name' => 'Raw Material B',
      'type' => 'material',
      'standard_cost' => 50
    ]);

    // Supplier
    $supplier = Supplier::create([
      'code' => 'SUP-TEST',
      'name' => 'Test Supplier'
    ]);

    // BOM: 1 Product requires 2 Material A + 5 Material B
    BomItem::create(['parent_product_id' => $product->id, 'child_product_id' => $materialA->id, 'quantity' => 2]);
    BomItem::create(['parent_product_id' => $product->id, 'child_product_id' => $materialB->id, 'quantity' => 5]);

    // 2. Create Production Plan (Produce 10 units of Product)
    $planData = [
      'period_start' => '2025-01-01',
      'period_end' => '2025-01-31',
      'description' => 'Integration Test Plan',
      'items' => [
        [
          'product_id' => $product->id,
          'quantity' => 10
        ]
      ]
    ];

    $plan = $this->planService->createPlan($planData, 1);

    // 3. Calculate Material Requirements
    // Needed: Mat A = 10 * 2 = 20, Mat B = 10 * 5 = 50
    $requirements = $this->planService->calculateMaterialRequirements($plan);

    $this->assertCount(2, $requirements);
    $this->assertEquals(20, $requirements->firstWhere('code', 'RM-100')['total_quantity']);
    $this->assertEquals(50, $requirements->firstWhere('code', 'RM-200')['total_quantity']);

    // 4. Create Purchase Orders
    $orders = $this->purchaseService->createPurchaseOrdersFromRequirements($requirements);

    // 5. Verify Purchase Orders
    // Since both materials default to the same supplier (Test Supplier), we expect 1 PO
    $this->assertCount(1, $orders);

    $po = $orders->first();
    $this->assertEquals($supplier->id, $po->supplier_id);
    $this->assertEquals('draft', $po->status);

    // Verify Total Amount
    // (20 * 100) + (50 * 50) = 2000 + 2500 = 4500
    $this->assertEquals(4500, $po->total_amount);

    // Verify Items in PO
    $this->assertCount(2, $po->items);
    $this->assertEquals(20, $po->items->where('product_id', $materialA->id)->first()->quantity);
    $this->assertEquals(50, $po->items->where('product_id', $materialB->id)->first()->quantity);
  }
}
