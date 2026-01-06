<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManufacturingExecution extends Model
{
    use HasFactory;

    protected $fillable = [
        'manufacturing_order_id',
        'good_quantity',
        'scrap_quantity',
        'actual_duration',
        'operator_id',
        'reported_at',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
    ];

    /**
     * Get the manufacturing order that owns the execution.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(ManufacturingOrder::class, 'manufacturing_order_id');
    }

    /**
     * Get the user (operator) who reported the execution.
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}