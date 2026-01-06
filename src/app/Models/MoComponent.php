<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MoComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'manufacturing_order_id',
        'item_id',
        'item_type',
        'required_quantity',
        'unit',
    ];

    /**
     * Get the manufacturing order that owns the component.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(ManufacturingOrder::class, 'manufacturing_order_id');
    }

    /**
     * Get the owning item model (Product or Material).
     */
    public function item(): MorphTo
    {
        return $this->morphTo();
    }
}