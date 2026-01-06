<?php

namespace Tests\Unit;

use App\Models\ManufacturingOrder;
use App\Models\MoComponent;
use App\Models\Product;
use App\Models\ProductionPlanDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManufacturingOrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_components()
    {
        $mo = ManufacturingOrder::factory()->create();
        MoComponent::factory()->count(3)->create(['manufacturing_order_id' => $mo->id]);

        $this->assertCount(3, $mo->components);
        $this->assertInstanceOf(MoComponent::class, $mo->components->first());
    }

    /** @test */
    public function it_belongs_to_a_product()
    {
        $product = Product::factory()->create();
        $mo = ManufacturingOrder::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $mo->product);
        $this->assertEquals($product->id, $mo->product->id);
    }

    /** @test */
    public function it_belongs_to_a_plan_detail()
    {
        $planDetail = ProductionPlanDetail::factory()->create();
        $mo = ManufacturingOrder::factory()->create(['production_plan_detail_id' => $planDetail->id]);

        $this->assertInstanceOf(ProductionPlanDetail::class, $mo->planDetail);
        $this->assertEquals($planDetail->id, $mo->planDetail->id);
    }

    /** @test */
    public function it_belongs_to_a_creator()
    {
        $user = User::factory()->create();
        $mo = ManufacturingOrder::factory()->create(['created_by' => $user->id]);

        $this->assertInstanceOf(User::class, $mo->creator);
        $this->assertEquals($user->id, $mo->creator->id);
    }
}