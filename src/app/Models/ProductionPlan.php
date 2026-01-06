<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_code',
        'name',
        'start_date',
        'end_date',
        'status',
        'created_by',
    ];

    /**
     * Get the details for the production plan.
     */
    public function details(): HasMany
    {
        return $this->hasMany(ProductionPlanDetail::class);
    }

    /**
     * Get the user who created the production plan.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}