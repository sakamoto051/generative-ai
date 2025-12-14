<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionPlanItem extends Model
{
  protected $fillable = [
    'production_plan_id',
    'product_id',
    'quantity',
    'planned_start_date',
    'planned_end_date',
  ];

  public function productionPlan(): BelongsTo
  {
    return $this->belongsTo(ProductionPlan::class);
  }

  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }

  public function results(): \Illuminate\Database\Eloquent\Relations\HasMany
  {
    return $this->hasMany(ProductionResult::class);
  }
}
