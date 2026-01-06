<?php

namespace Tests\Unit\Services;

use App\Models\Bom;
use App\Models\ManufacturingOrder;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductionPlan;
use App\Models\ProductionPlanDetail;
use App\Models\User;
use App\Services\MoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoServiceTest extends TestCase
{
    use RefreshDatabase;

    protected MoService $moService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->moService = new MoService();
    }

    /** @test */
    public function it_can_generate_mo_from_plan_detail_with_bom_snapshot()
    {
        // 1. Setup: User, Product, Material, and BOM
        $user = User::factory()->create();
        $parentProduct = Product::factory()->create(['unit' => 'pcs']);
        $childMaterial = Material::factory()->create(['unit' => 'kg']);
        
        Bom::create([
            'parent_id' => $parentProduct->id,
            'parent_type' => Product::class,
            'child_id' => $childMaterial->id,
            'child_type' => Material::class,
            'quantity' => 0.5, // 0.5kg per pcs
        ]);

        // 2. Setup: Approved Production Plan and Detail
        $plan = ProductionPlan::factory()->create([
            'status' => 'Approved',
            'created_by' => $user->id,
        ]);
        $detail = ProductionPlanDetail::factory()->create([
            'production_plan_id' => $plan->id,
            'product_id' => $parentProduct->id,
            'quantity' => 10, // Make 10 pcs
        ]);

        // 3. Action: Generate MO
        $mo = $this->moService->generateFromPlanDetail($detail, $user);

        // 4. Assertions: MO created
        $this->assertInstanceOf(ManufacturingOrder::class, $mo);
        $this->assertDatabaseHas('manufacturing_orders', [
            'id' => $mo->id,
            'mo_number' => $mo->mo_number,
            'product_id' => $parentProduct->id,
            'quantity' => 10,
            'status' => 'Planned',
        ]);

        // 5. Assertions: BOM Snapshot (Components) created
        $this->assertCount(1, $mo->components);
        $this->assertDatabaseHas('mo_components', [
            'manufacturing_order_id' => $mo->id,
            'item_id' => $childMaterial->id,
            'item_type' => Material::class,
            'required_quantity' => 5.0, // 10 pcs * 0.5kg/pcs
            'unit' => 'kg',
        ]);
    }
}