<?php

namespace Tests\Unit\Services;

use App\Models\Bom;
use App\Models\Material;
use App\Models\Product;
use App\Services\MrpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MrpServiceTest extends TestCase
{
    use RefreshDatabase;

    protected MrpService $mrpService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mrpService = new MrpService();
    }

    /** @test */
    public function it_calculates_total_requirements_recursively_without_inventory()
    {
        // Setup: A -> 2x B -> 3x C
        $productA = Product::factory()->create(['name' => 'Product A']);
        $productB = Product::factory()->create(['name' => 'Product B']);
        $materialC = Material::factory()->create(['name' => 'Material C']);

        Bom::create([
            'parent_id' => $productA->id,
            'parent_type' => Product::class,
            'child_id' => $productB->id,
            'child_type' => Product::class,
            'quantity' => 2,
        ]);

        Bom::create([
            'parent_id' => $productB->id,
            'parent_type' => Product::class,
            'child_id' => $materialC->id,
            'child_type' => Material::class,
            'quantity' => 3,
        ]);

        // Request 5 of Product A
        $results = $this->mrpService->calculateRequirements($productA->id, 5);

        // Expected:
        // Product B: 5 * 2 = 10
        // Material C: 10 * 3 = 30

        $this->assertCount(2, $results, 'Should have 2 requirement entries (B and C)');

        $requirementB = collect($results)->firstWhere('item_id', $productB->id);
        $this->assertNotNull($requirementB);
        $this->assertEquals(10, $requirementB['total_requirement']);
        $this->assertEquals(Product::class, $requirementB['item_type']);

        $requirementC = collect($results)->firstWhere('item_id', $materialC->id);
        $this->assertNotNull($requirementC);
        $this->assertEquals(30, $requirementC['total_requirement']);
        $this->assertEquals(Material::class, $requirementC['item_type']);
    }
}
