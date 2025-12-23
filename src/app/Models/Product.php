<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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
}
