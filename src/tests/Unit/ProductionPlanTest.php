<?php

namespace Tests\Unit;

use App\Models\ProductionPlan;
use App\Models\ProductionPlanDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionPlanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_production_plan_can_have_many_details()
    {
        $plan = ProductionPlan::factory()->create();
        $details = ProductionPlanDetail::factory()->count(3)->create([
            'production_plan_id' => $plan->id,
        ]);

        $this->assertCount(3, $plan->details);
        $this->assertInstanceOf(ProductionPlanDetail::class, $plan->details->first());
    }

    /** @test */
    public function a_production_plan_belongs_to_a_creator()
    {
        $user = User::factory()->create();
        $plan = ProductionPlan::factory()->create([
            'created_by' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $plan->creator);
        $this->assertEquals($user->id, $plan->creator->id);
    }

    /** @test */
    public function a_production_plan_detail_belongs_to_a_plan()
    {
        $detail = ProductionPlanDetail::factory()->create();

        $this->assertInstanceOf(ProductionPlan::class, $detail->plan);
    }

    /** @test */
    public function a_production_plan_detail_belongs_to_a_product()
    {
        $detail = ProductionPlanDetail::factory()->create();

        $this->assertInstanceOf(Product::class, $detail->product);
    }
}