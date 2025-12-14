<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 作業実績モデル
 *
 * @property int $id
 * @property int $manufacturing_order_id 製造指示ID
 * @property int|null $worker_id 作業者ID
 * @property string|null $process 工程
 * @property int $completed_quantity 完了数量
 * @property int $defect_quantity 不良数量
 * @property string|null $work_start_at 作業開始日時
 * @property string|null $work_end_at 作業終了日時
 * @property int $work_minutes 作業時間（分）
 * @property int|null $equipment_id 使用設備ID
 * @property string|null $defect_details 不良詳細
 * @property string|null $notes 備考
 */
class WorkResult extends Model
{
  use HasFactory;

  /**
   * テーブル名
   *
   * @var string
   */
  protected $table = 'work_results';

  /**
   * 複数代入可能な属性
   *
   * @var array<string>
   */
  protected $fillable = [
    'manufacturing_order_id',
    'worker_id',
    'process',
    'completed_quantity',
    'defect_quantity',
    'work_start_at',
    'work_end_at',
    'work_minutes',
    'equipment_id',
    'defect_details',
    'notes',
  ];

  /**
   * キャストする属性
   *
   * @var array<string, string>
   */
  protected $casts = [
    'completed_quantity' => 'integer',
    'defect_quantity' => 'integer',
    'work_start_at' => 'datetime',
    'work_end_at' => 'datetime',
    'work_minutes' => 'integer',
  ];

  /**
   * 製造指示
   *
   * @return BelongsTo<ManufacturingOrder, WorkResult>
   */
  public function manufacturingOrder(): BelongsTo
  {
    return $this->belongsTo(ManufacturingOrder::class);
  }

  /**
   * 作業者
   *
   * @return BelongsTo<Worker, WorkResult>
   */
  public function worker(): BelongsTo
  {
    return $this->belongsTo(Worker::class);
  }

  /**
   * 使用設備
   *
   * @return BelongsTo<Equipment, WorkResult>
   */
  public function equipment(): BelongsTo
  {
    return $this->belongsTo(Equipment::class);
  }

  /**
   * 作業者でフィルタするスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<WorkResult> $query
   * @param int $workerId
   * @return \Illuminate\Database\Eloquent\Builder<WorkResult>
   */
  public function scopeByWorker($query, int $workerId)
  {
    return $query->where('worker_id', $workerId);
  }

  /**
   * 期間でフィルタするスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<WorkResult> $query
   * @param string $startDate
   * @param string $endDate
   * @return \Illuminate\Database\Eloquent\Builder<WorkResult>
   */
  public function scopeInPeriod($query, string $startDate, string $endDate)
  {
    return $query->whereBetween('work_start_at', [$startDate, $endDate]);
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
   * 不良率を計算
   *
   * @return float
   */
  public function getDefectRate(): float
  {
    if ($this->completed_quantity === 0) {
      return 0;
    }
    return ($this->defect_quantity / $this->completed_quantity) * 100;
  }

  /**
   * 作業時間を自動計算して保存
   *
   * @return void
   */
  public function calculateWorkTime(): void
  {
    if ($this->work_start_at && $this->work_end_at) {
      $this->work_minutes = $this->work_start_at->diffInMinutes($this->work_end_at);
    }
  }
}
