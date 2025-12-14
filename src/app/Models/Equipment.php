<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 設備マスタモデル
 *
 * @property int $id
 * @property string $code 設備コード
 * @property string $name 設備名
 * @property string|null $category 設備カテゴリ
 * @property string|null $process 工程
 * @property int $capacity_per_hour 生産能力（個/時間）
 * @property int $setup_time_minutes 段取り時間（分）
 * @property float $hourly_rate 時間チャージ（円/時間）
 * @property int $maintenance_interval_days メンテナンス周期（日）
 * @property string|null $location 設置場所
 * @property string|null $notes 備考
 * @property bool $is_active 有効フラグ
 */
class Equipment extends Model
{
  use HasFactory;
  use SoftDeletes;

  /**
   * テーブル名
   *
   * @var string
   */
  protected $table = 'equipment';

  /**
   * 複数代入可能な属性
   *
   * @var array<string>
   */
  protected $fillable = [
    'code',
    'name',
    'category',
    'process',
    'capacity_per_hour',
    'setup_time_minutes',
    'hourly_rate',
    'maintenance_interval_days',
    'location',
    'notes',
    'is_active',
    'created_by',
    'updated_by',
  ];

  /**
   * キャストする属性
   *
   * @var array<string, string>
   */
  protected $casts = [
    'capacity_per_hour' => 'integer',
    'setup_time_minutes' => 'integer',
    'hourly_rate' => 'decimal:2',
    'maintenance_interval_days' => 'integer',
    'is_active' => 'boolean',
  ];

  /**
   * 生産計画明細
   *
   * @return HasMany<ProductionPlanItem>
   */
  public function productionPlanItems(): HasMany
  {
    return $this->hasMany(ProductionPlanItem::class);
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
   * 作業実績
   *
   * @return HasMany<WorkResult>
   */
  public function workResults(): HasMany
  {
    return $this->hasMany(WorkResult::class);
  }

  /**
   * 作成者
   *
   * @return BelongsTo<User, Equipment>
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * 更新者
   *
   * @return BelongsTo<User, Equipment>
   */
  public function updater(): BelongsTo
  {
    return $this->belongsTo(User::class, 'updated_by');
  }

  /**
   * 有効な設備のみ取得するスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<Equipment> $query
   * @return \Illuminate\Database\Eloquent\Builder<Equipment>
   */
  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  /**
   * 工程でフィルタするスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<Equipment> $query
   * @param string $process
   * @return \Illuminate\Database\Eloquent\Builder<Equipment>
   */
  public function scopeByProcess($query, string $process)
  {
    return $query->where('process', $process);
  }

  /**
   * 時間あたりの生産可能数量を計算
   *
   * @param int $hours 稼働時間
   * @return int
   */
  public function calculateCapacity(int $hours): int
  {
    return $this->capacity_per_hour * $hours;
  }
}
