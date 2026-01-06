<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'material_code',
        'name',
        'category',
        'unit',
        'standard_price',
        'lead_time',
        'minimum_order_quantity',
        'safety_stock',
        'is_lot_managed',
        'has_expiry_management',
    ];

    /**
     * Get the BOM entries where this material is the parent (rare, for semi-finished).
     */
    public function components()
    {
        return $this->morphMany(Bom::class, 'parent');
    }

    /**
     * Get the BOM entries where this material is used as a child (usages).
     */
    public function usages()
    {
        return $this->morphMany(Bom::class, 'child');
    }

    /**
     * Get the inventory record for the material.
     */
    public function inventory()
    {
        return $this->morphOne(Inventory::class, 'item');
    }
}
