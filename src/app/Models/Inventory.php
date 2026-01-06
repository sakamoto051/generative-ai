<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Inventory extends Model
{
    protected $fillable = [
        'item_id',
        'item_type',
        'quantity',
        'location',
    ];

    /**
     * Get the owning item model (Product or Material).
     */
    public function item(): MorphTo
    {
        return $this->morphTo();
    }
}