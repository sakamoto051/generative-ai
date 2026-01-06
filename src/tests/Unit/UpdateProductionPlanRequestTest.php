<?php

namespace Tests\Unit;

use App\Http\Requests\UpdateProductionPlanRequest;
use App\Models\ProductionPlan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProductionPlanRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_authorizes_draft_plans_only()
    {
        $plan = ProductionPlan::factory()->create(['status' => 'Draft']);
        
        $request = new class extends UpdateProductionPlanRequest {
            public $mockPlan;
            public function route($param = null, $default = null) {
                return $this->mockPlan;
            }
        };
        $request->mockPlan = $plan;

        $this->assertTrue($request->authorize());

        $plan->status = 'Approved';
        $this->assertFalse($request->authorize());
    }
}