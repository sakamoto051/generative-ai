<?php

namespace Tests\Unit;

use App\Models\Bom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BomTest extends TestCase
{
    use RefreshDatabase;

    public function test_bom_model_has_expected_fillable_attributes(): void
    {
        $bom = new Bom;
        $fillable = $bom->getFillable();

        $this->assertContains('parent_id', $fillable);
        $this->assertContains('parent_type', $fillable);
        $this->assertContains('child_id', $fillable);
        $this->assertContains('child_type', $fillable);
        $this->assertContains('quantity', $fillable);
        $this->assertContains('yield_rate', $fillable);
        $this->assertContains('valid_from', $fillable);
        $this->assertContains('valid_until', $fillable);
    }

    public function test_bom_has_parent_relationship(): void
    {
        $bom = new Bom;
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphTo::class, $bom->parent());
    }

    public function test_bom_has_child_relationship(): void
    {
        $bom = new Bom;
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphTo::class, $bom->child());
    }
}
