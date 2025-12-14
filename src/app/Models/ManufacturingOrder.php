<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 製造指示モデル
 *
 * @property int $id
 * @property string $order_number 製造番号
 * @property int|null $production_plan_item_id 生産計画明細ID
 * @property int $product_id 製品ID
 * @property int $order_quantity 製造指示数量
 * @property int $completed_quantity 完了数量
 * @property int $defect_quantity 不良数量
 * @property string $status ステータス (pending/in_progress/completed/cancelled)
 * @property string $scheduled_start_date 予定開始日
 * @property string $scheduled_end_date 予定完了日
 * @property string|null $actual_start_at 実績開始日時
 * @property string|null $actual_end_at 実績完了日時
 * @property int|null $equipment_id 設備ID
 * @property string|null $notes 備考
 * @property string|null $qr_code QRコード
 */
class ManufacturingOrder extends Model
{
  use HasFactory;
  use SoftDeletes;

  /**
   * テーブル名
   *
   * @var string
   */
  protected $table = 'manufacturing_orders';

  /**
   * ステータス定数
   */
  public const STATUS_PENDING = 'pending';
  public const STATUS_IN_PROGRESS = 'in_progress';
  public const STATUS_COMPLETED = 'completed';
  public const STATUS_CANCELLED = 'cancelled';

  /**
   * 複数代入可能な属性
   *
   * @var array<string>
   */
  protected $fillable = [
    'order_number',
    'production_plan_item_id',
    'product_id',
    'order_quantity',
    'completed_quantity',
    'defect_quantity',
    'status',
    'scheduled_start_date',
    'scheduled_end_date',
    'actual_start_at',
    'actual_end_at',
    'equipment_id',
    'notes',
    'qr_code',
    'created_by',
    'updated_by',
  ];

  /**
   * キャストする属性
   *
   * @var array<string, string>
   */
  protected $casts = [
    'order_quantity' => 'integer',
    'completed_quantity' => 'integer',
    'defect_quantity' => 'integer',
    'scheduled_start_date' => 'date',
    'scheduled_end_date' => 'date',
    'actual_start_at' => 'datetime',
    'actual_end_at' => 'datetime',
  ];

  /**
   * 生産計画明細
   *
   * @return BelongsTo<ProductionPlanItem, ManufacturingOrder>
   */
  public function productionPlanItem(): BelongsTo
  {
    return $this->belongsTo(ProductionPlanItem::class);
  }

  /**
   * 製品
   *
   * @return BelongsTo<Product, ManufacturingOrder>
   */
  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * 設備
   *
   * @return BelongsTo<Equipment, ManufacturingOrder>
   */
  public function equipment(): BelongsTo
  {
    return $this->belongsTo(Equipment::class);
  }

  /**
   * 材料払出
   *
   * @return HasMany<MaterialIssue>
   */
  public function materialIssues(): HasMany
  {
    return $this->hasMany(MaterialIssue::class);
  }

  /**
   * 作業実績
   *
   * @return HasMany<WorkResult>
   */
  public function workResults(): HasMany
  {
    return $this->hasMany(WorkResult::class);
  }

  /**
   * 原価計算結果
   *
   * @return HasMany<Cost>
   */
  public function costs(): HasMany
  {
    return $this->hasMany(Cost::class);
  }

  /**
   * 作成者
   *
   * @return BelongsTo<User, ManufacturingOrder>
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * 更新者
   *
   * @return BelongsTo<User, ManufacturingOrder>
   */
  public function updater(): BelongsTo
  {
    return $this->belongsTo(User::class, 'updated_by');
  }

  /**
   * 進行中の製造指示を取得するスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<ManufacturingOrder> $query
   * @return \Illuminate\Database\Eloquent\Builder<ManufacturingOrder>
   */
  public function scopeInProgress($query)
  {
    return $query->where('status', self::STATUS_IN_PROGRESS);
  }

  /**
   * 完了した製造指示を取得するスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<ManufacturingOrder> $query
   * @return \Illuminate\Database\Eloquent\Builder<ManufacturingOrder>
   */
  public function scopeCompleted($query)
  {
    return $query->where('status', self::STATUS_COMPLETED);
  }

  /**
   * 進捗率を計算
   *
   * @return float
   */
  public function getProgressPercentage(): float
  {
    if ($this->order_quantity === 0) {
      return 0;
    }
    return ($this->completed_quantity / $this->order_quantity) * 100;
  }

  /**
   * 良品数量を計算
   *
   * @return int
   */
  public function getGoodQuantity(): int
  {
    return $this->completed_quantity - $this->defect_quantity;
  }

  /**
   * 作業開始
   *
   * @return void
   */
  public function start(): void
  {
    $this->status = self::STATUS_IN_PROGRESS;
    $this->actual_start_at = now();
    $this->save();
  }

  /**
   * 作業完了
   *
   * @return void
   */
  public function complete(): void
  {
    $this->status = self::STATUS_COMPLETED;
    $this->actual_end_at = now();
    $this->save();
  }

  /**
   * 作業中止
   *
   * @return void
   */
  public function cancel(): void
  {
    $this->status = self::STATUS_CANCELLED;
    $this->save();
  }
}
