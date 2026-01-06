<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionPlanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_plan_id',
        'product_id',
        'quantity',
        'due_date',
        'priority',
        'remarks',
    ];

    /**
     * Get the production plan that owns the detail.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(ProductionPlan::class, 'production_plan_id');
    }

    /**
     * Get the product for the production plan detail.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}