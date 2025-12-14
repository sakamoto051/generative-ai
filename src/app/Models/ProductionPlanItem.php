<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 生産計画明細モデル
 *
 * @property int $id
 * @property int $production_plan_id 生産計画ID
 * @property int $product_id 製品ID
 * @property int $quantity 生産数量
 * @property string $scheduled_date 予定日
 * @property int $priority 優先度
 * @property int|null $equipment_id 設備ID
 * @property string|null $notes 備考
 */
class ProductionPlanItem extends Model
{
  use HasFactory;

  /**
   * テーブル名
   *
   * @var string
   */
  protected $table = 'production_plan_items';

  /**
   * 複数代入可能な属性
   *
   * @var array<string>
   */
  protected $fillable = [
    'production_plan_id',
    'product_id',
    'quantity',
    'scheduled_date',
    'priority',
    'equipment_id',
    'notes',
  ];

  /**
   * キャストする属性
   *
   * @var array<string, string>
   */
  protected $casts = [
    'quantity' => 'integer',
    'scheduled_date' => 'date',
    'priority' => 'integer',
  ];

  /**
   * 生産計画
   *
   * @return BelongsTo<ProductionPlan, ProductionPlanItem>
   */
  public function productionPlan(): BelongsTo
  {
    return $this->belongsTo(ProductionPlan::class);
  }

  /**
   * 製品
   *
   * @return BelongsTo<Product, ProductionPlanItem>
   */
  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * 設備
   *
   * @return BelongsTo<Equipment, ProductionPlanItem>
   */
  public function equipment(): BelongsTo
  {
    return $this->belongsTo(Equipment::class);
  }

  /**
   * 製造指示
   *
   * @return HasMany<ManufacturingOrder>
   */
  public function manufacturingOrders(): HasMany
  {
    return $this->hasMany(ManufacturingOrder::class);
  }

  /**
   * 優先度順にソートするスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<ProductionPlanItem> $query
   * @param string $direction
   * @return \Illuminate\Database\Eloquent\Builder<ProductionPlanItem>
   */
  public function scopeOrderByPriority($query, string $direction = 'desc')
  {
    return $query->orderBy('priority', $direction);
  }

  /**
   * 予定日でフィルタするスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<ProductionPlanItem> $query
   * @param string $date
   * @return \Illuminate\Database\Eloquent\Builder<ProductionPlanItem>
   */
  public function scopeScheduledOn($query, string $date)
  {
    return $query->where('scheduled_date', $date);
  }
}
