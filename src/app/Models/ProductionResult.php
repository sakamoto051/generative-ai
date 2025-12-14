<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionResult extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'production_plan_item_id',
    'result_date',
    'quantity',
    'defective_quantity',
    'remarks',
  ];

  protected $casts = [
    'result_date' => 'date',
    'quantity' => 'decimal:4',
    'defective_quantity' => 'decimal:4',
  ];

  public function productionPlanItem(): BelongsTo
  {
    return $this->belongsTo(ProductionPlanItem::class);
  }
}
