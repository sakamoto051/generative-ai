<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $parent_id
 * @property string $parent_type
 * @property int $child_id
 * @property string $child_type
 * @property float $quantity
 * @property float|null $yield_rate
 * @property string|null $valid_from
 * @property string|null $valid_until
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\App\Models\Product|\App\Models\Material|null $child
 * @property-read Model|\App\Models\Product|\App\Models\Material|null $parent
 */
class Bom extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'parent_id',
        'parent_type',
        'child_id',
        'child_type',
        'quantity',
        'yield_rate',
        'valid_from',
        'valid_until',
    ];

    /**
     * Get the parent model (Product or Material).
     */
    public function parent()
    {
        return $this->morphTo();
    }

    /**
     * Get the child model (Product or Material).
     */
    public function child()
    {
        return $this->morphTo();
    }
}
