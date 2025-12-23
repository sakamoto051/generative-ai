<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
