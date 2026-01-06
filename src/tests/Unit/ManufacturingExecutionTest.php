<?php

namespace Tests\Unit;

use App\Models\ManufacturingExecution;
use App\Models\ManufacturingOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManufacturingExecutionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_an_order()
    {
        $mo = ManufacturingOrder::factory()->create();
        $execution = ManufacturingExecution::factory()->create(['manufacturing_order_id' => $mo->id]);

        $this->assertInstanceOf(ManufacturingOrder::class, $execution->order);
        $this->assertEquals($mo->id, $execution->order->id);
    }

    /** @test */
    public function it_belongs_to_an_operator()
    {
        $user = User::factory()->create();
        $execution = ManufacturingExecution::factory()->create(['operator_id' => $user->id]);

        $this->assertInstanceOf(User::class, $execution->operator);
        $this->assertEquals($user->id, $execution->operator->id);
    }

    /** @test */
    public function it_can_be_created_with_quantities()
    {
        $execution = ManufacturingExecution::factory()->create([
            'good_quantity' => 10.5,
            'scrap_quantity' => 1.2,
        ]);

        $this->assertDatabaseHas('manufacturing_executions', [
            'id' => $execution->id,
            'good_quantity' => 10.5,
            'scrap_quantity' => 1.2,
        ]);
    }
}