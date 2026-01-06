<?php

namespace Tests\Unit\Services;

use App\Models\Bom;
use App\Models\Product;
use App\Models\Material;
use App\Services\BomService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BomServiceTest extends TestCase
{
    use RefreshDatabase;

    protected BomService $bomService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bomService = new BomService();
    }

    /** @test */
    public function it_detects_direct_circular_reference()
    {
        // A -> A
        $productA = Product::factory()->create();

        $isCircular = $this->bomService->detectCircularReference($productA->id, $productA->id);

        $this->assertTrue($isCircular, 'Should detect A -> A circular reference');
    }

    /** @test */
    public function it_detects_simple_circular_reference_reverse()
    {
        // A -> B exists. Try B -> A.
        $productA = Product::factory()->create();
        $productB = Product::factory()->create();

        Bom::create([
            'parent_id' => $productA->id,
            'parent_type' => Product::class,
            'child_id' => $productB->id,
            'child_type' => Product::class,
            'quantity' => 1,
        ]);

        // Check adding B -> A
        $isCircular = $this->bomService->detectCircularReference($productB->id, $productA->id);

        $this->assertTrue($isCircular, 'Should detect B -> A when A -> B exists');
    }

    /** @test */
    public function it_detects_deep_circular_reference()
    {
        // A -> B -> C. Try C -> A.
        $productA = Product::factory()->create();
        $productB = Product::factory()->create();
        $productC = Product::factory()->create();

        Bom::create([
            'parent_id' => $productA->id,
            'parent_type' => Product::class,
            'child_id' => $productB->id,
            'child_type' => Product::class,
            'quantity' => 1,
        ]);

        Bom::create([
            'parent_id' => $productB->id,
            'parent_type' => Product::class,
            'child_id' => $productC->id,
            'child_type' => Product::class,
            'quantity' => 1,
        ]);

        // Check adding C -> A
        $isCircular = $this->bomService->detectCircularReference($productC->id, $productA->id);

        $this->assertTrue($isCircular, 'Should detect C -> A when A -> B -> C exists');
    }

    /** @test */
    public function it_allows_non_circular_reference()
    {
        // A -> B. Try B -> C.
        $productA = Product::factory()->create();
        $productB = Product::factory()->create();
        $productC = Product::factory()->create();

        Bom::create([
            'parent_id' => $productA->id,
            'parent_type' => Product::class,
            'child_id' => $productB->id,
            'child_type' => Product::class,
            'quantity' => 1,
        ]);

        // Check adding B -> C
        $isCircular = $this->bomService->detectCircularReference($productB->id, $productC->id);

        $this->assertFalse($isCircular, 'Should allow B -> C');
    }

    /** @test */
    public function it_ignores_material_as_child_in_circular_check()
    {
        // A -> A (Material) - should be false as Material cannot be parent
        $productA = Product::factory()->create();
        
        $isCircular = $this->bomService->detectCircularReference($productA->id, $productA->id, Material::class);
        
        $this->assertFalse($isCircular, 'Should return false for material child even if ID matches parent');
    }
}