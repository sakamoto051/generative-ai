<?php

namespace Tests\Unit\Services;

use App\Models\Inventory;
use App\Models\ManufacturingOrder;
use App\Models\Product;
use App\Models\User;
use App\Services\ExecutionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExecutionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ExecutionService $executionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->executionService = new ExecutionService();
    }

    /** @test */
    public function it_records_execution_and_updates_inventory_and_mo_status()
    {
        // 1. Setup: Product, MO, and User
        $product = Product::factory()->create();
        $user = User::factory()->create();
        $mo = ManufacturingOrder::factory()->create([
            'product_id' => $product->id,
            'quantity' => 100,
            'status' => 'Released',
        ]);

        // 2. Action: Report 40 units
        $execution = $this->executionService->reportProgress($mo, [
            'good_quantity' => 40,
            'scrap_quantity' => 2,
            'actual_duration' => 120,
            'operator_id' => $user->id,
        ]);

        // 3. Assertions: Execution recorded
        $this->assertDatabaseHas('manufacturing_executions', [
            'id' => $execution->id,
            'good_quantity' => 40,
            'manufacturing_order_id' => $mo->id,
        ]);

        // 4. Assertions: Inventory updated
        $this->assertDatabaseHas('inventories', [
            'item_id' => $product->id,
            'item_type' => Product::class,
            'quantity' => 40,
        ]);

        // 5. Assertions: MO status updated to In Progress
        $this->assertEquals('In Progress', $mo->fresh()->status);

        // 6. Action: Report remaining 60 units
        $this->executionService->reportProgress($mo, [
            'good_quantity' => 60,
            'scrap_quantity' => 0,
            'actual_duration' => 180,
            'operator_id' => $user->id,
        ]);

        // 7. Assertions: Inventory total is 100
        $this->assertEquals(100, $product->inventory->fresh()->quantity);

        // 8. Assertions: MO status updated to Completed
        $this->assertEquals('Completed', $mo->fresh()->status);
    }
}