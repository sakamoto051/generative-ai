<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ManufacturingOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'mo_number',
        'production_plan_detail_id',
        'product_id',
        'quantity',
        'due_date',
        'status',
        'created_by',
    ];

    /**
     * Get the components for the manufacturing order.
     */
    public function components(): HasMany
    {
        return $this->hasMany(MoComponent::class);
    }

    /**
     * Get the product associated with the manufacturing order.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the production plan detail associated with the manufacturing order.
     */
    public function planDetail(): BelongsTo
    {
        return $this->belongsTo(ProductionPlanDetail::class, 'production_plan_detail_id');
    }

    /**
     * Get the user who created the manufacturing order.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the executions for the manufacturing order.
     */
    public function executions(): HasMany
    {
        return $this->hasMany(ManufacturingExecution::class);
    }
}