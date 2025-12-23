<?php

namespace Tests\Unit;

use App\Models\Material;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MaterialTest extends TestCase
{
    use RefreshDatabase;

    public function test_material_model_has_expected_fillable_attributes(): void
    {
        $material = new Material();
        $fillable = $material->getFillable();

        $this->assertContains('material_code', $fillable);
        $this->assertContains('name', $fillable);
        $this->assertContains('category', $fillable);
        $this->assertContains('unit', $fillable);
        $this->assertContains('standard_price', $fillable);
        $this->assertContains('lead_time', $fillable);
        $this->assertContains('minimum_order_quantity', $fillable);
        $this->assertContains('safety_stock', $fillable);
        $this->assertContains('is_lot_managed', $fillable);
        $this->assertContains('has_expiry_management', $fillable);
    }
}
