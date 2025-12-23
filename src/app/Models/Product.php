<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'product_code',
        'name',
        'category',
        'unit',
        'standard_cost',
        'standard_manufacturing_time',
        'lead_time',
        'safety_stock',
        'reorder_point',
    ];

    /**
     * Get the BOM entries where this product is the parent (components).
     */
    public function components()
    {
        return $this->morphMany(Bom::class, 'parent');
    }

    /**
     * Get the BOM entries where this product is used as a child (usages).
     */
    public function usages()
    {
        return $this->morphMany(Bom::class, 'child');
    }
}
