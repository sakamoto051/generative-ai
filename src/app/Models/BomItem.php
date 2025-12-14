<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BomItem extends Model
{
  protected $fillable = [
    'parent_product_id',
    'child_product_id',
    'quantity',
    'yield_rate',
  ];

  public function parentProduct(): BelongsTo
  {
    return $this->belongsTo(Product::class, 'parent_product_id');
  }

  public function childProduct(): BelongsTo
  {
    return $this->belongsTo(Product::class, 'child_product_id');
  }
}
