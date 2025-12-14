<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
  use HasFactory;
  protected $fillable = [
    'code',
    'name',
    'type',
    'standard_cost',
    'lead_time_days',
    'minimum_stock_level',
    'current_stock',
  ];

  public function bomItems(): HasMany
  {
    return $this->hasMany(BomItem::class, 'parent_product_id');
  }
}
