<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_model_has_expected_fillable_attributes(): void
    {
        $product = new Product();
        $fillable = $product->getFillable();

        $this->assertContains('product_code', $fillable);
        $this->assertContains('name', $fillable);
        $this->assertContains('category', $fillable);
        $this->assertContains('unit', $fillable);
        $this->assertContains('standard_cost', $fillable);
        $this->assertContains('standard_manufacturing_time', $fillable);
        $this->assertContains('lead_time', $fillable);
        $this->assertContains('safety_stock', $fillable);
        $this->assertContains('reorder_point', $fillable);
    }
}
