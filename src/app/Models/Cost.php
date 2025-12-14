<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 原価計算結果モデル
 *
 * @property int $id
 * @property int $manufacturing_order_id 製造指示ID
 * @property int $product_id 製品ID
 * @property float $material_cost 材料費
 * @property float $labor_cost 労務費
 * @property float $overhead_cost 製造経費
 * @property float $total_cost 合計原価
 * @property float $unit_cost 単位原価
 * @property int $quantity 数量
 * @property string $cost_calculation_date 原価計算日
 * @property string $calculation_method 計算方法（actual/standard）
 * @property float $standard_cost_variance 標準原価差異
 * @property string|null $notes 備考
 * @property int|null $calculated_by 計算担当者
 */
class Cost extends Model
{
  use HasFactory;

  /**
   * テーブル名
   *
   * @var string
   */
  protected $table = 'costs';

  /**
   * 計算方法定数
   */
  public const METHOD_ACTUAL = 'actual';
  public const METHOD_STANDARD = 'standard';

  /**
   * 複数代入可能な属性
   *
   * @var array<string>
   */
  protected $fillable = [
    'manufacturing_order_id',
    'product_id',
    'material_cost',
    'labor_cost',
    'overhead_cost',
    'total_cost',
    'unit_cost',
    'quantity',
    'cost_calculation_date',
    'calculation_method',
    'standard_cost_variance',
    'notes',
    'calculated_by',
  ];

  /**
   * キャストする属性
   *
   * @var array<string, string>
   */
  protected $casts = [
    'material_cost' => 'decimal:2',
    'labor_cost' => 'decimal:2',
    'overhead_cost' => 'decimal:2',
    'total_cost' => 'decimal:2',
    'unit_cost' => 'decimal:2',
    'quantity' => 'integer',
    'cost_calculation_date' => 'date',
    'standard_cost_variance' => 'decimal:2',
  ];

  /**
   * 製造指示
   *
   * @return BelongsTo<ManufacturingOrder, Cost>
   */
  public function manufacturingOrder(): BelongsTo
  {
    return $this->belongsTo(ManufacturingOrder::class);
  }

  /**
   * 製品
   *
   * @return BelongsTo<Product, Cost>
   */
  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * 計算担当者
   *
   * @return BelongsTo<User, Cost>
   */
  public function calculator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'calculated_by');
  }

  /**
   * 期間でフィルタするスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<Cost> $query
   * @param string $startDate
   * @param string $endDate
   * @return \Illuminate\Database\Eloquent\Builder<Cost>
   */
  public function scopeInPeriod($query, string $startDate, string $endDate)
  {
    return $query->whereBetween('cost_calculation_date', [$startDate, $endDate]);
  }

  /**
   * 実際原価計算のみ取得するスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<Cost> $query
   * @return \Illuminate\Database\Eloquent\Builder<Cost>
   */
  public function scopeActualCost($query)
  {
    return $query->where('calculation_method', self::METHOD_ACTUAL);
  }

  /**
   * 合計原価を計算して設定
   *
   * @return void
   */
  public function calculateTotalCost(): void
  {
    $this->total_cost = $this->material_cost + $this->labor_cost + $this->overhead_cost;
    if ($this->quantity > 0) {
      $this->unit_cost = $this->total_cost / $this->quantity;
    }
  }

  /**
   * 原価構成比を取得
   *
   * @return array<string, float>
   */
  public function getCostComposition(): array
  {
    if ($this->total_cost == 0) {
      return [
        'material_ratio' => 0,
        'labor_ratio' => 0,
        'overhead_ratio' => 0,
      ];
    }

    return [
      'material_ratio' => ($this->material_cost / $this->total_cost) * 100,
      'labor_ratio' => ($this->labor_cost / $this->total_cost) * 100,
      'overhead_ratio' => ($this->overhead_cost / $this->total_cost) * 100,
    ];
  }

  /**
   * 差異率を計算
   *
   * @return float|null
   */
  public function getVarianceRate(): ?float
  {
    if ($this->total_cost == 0) {
      return null;
    }
    return ($this->standard_cost_variance / $this->total_cost) * 100;
  }
}
