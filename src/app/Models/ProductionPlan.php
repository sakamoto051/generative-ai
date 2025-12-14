<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionPlan extends Model
{
  use SoftDeletes, HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'plan_number',
    'period_start',
    'period_end',
    'status',
    'creator_id',
    'description',
  ];

  /**
   * Get the user who created the production plan.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'creator_id');
  }

  /**
   * Get the items in the production plan.
   */
  public function items(): HasMany
  {
    return $this->hasMany(ProductionPlanItem::class);
  }
}
