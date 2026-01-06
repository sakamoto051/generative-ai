<?php

namespace Tests\Unit;

use App\Http\Requests\StoreProductionPlanRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreProductionPlanRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_validates_required_fields()
    {
        $request = new StoreProductionPlanRequest();
        $validator = Validator::make([], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('plan_code', $validator->errors()->toArray());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertArrayHasKey('start_date', $validator->errors()->toArray());
        $this->assertArrayHasKey('end_date', $validator->errors()->toArray());
        $this->assertArrayHasKey('details', $validator->errors()->toArray());
    }

    /** @test */
    public function it_validates_date_logic()
    {
        $data = [
            'plan_code' => 'PLAN-001',
            'name' => 'Test Plan',
            'start_date' => '2026-01-10',
            'end_date' => '2026-01-01', // Before start
            'details' => [
                [
                    'product_id' => 1,
                    'quantity' => 10,
                    'due_date' => '2026-02-01', // Outside range
                ]
            ],
        ];

        $request = new StoreProductionPlanRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('end_date', $validator->errors()->toArray());
        $this->assertArrayHasKey('details.0.due_date', $validator->errors()->toArray());
    }
}