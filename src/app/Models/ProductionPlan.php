<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 生産計画モデル
 *
 * @property int $id
 * @property string $plan_number 計画番号
 * @property string $name 計画名
 * @property string $plan_start_date 計画開始日
 * @property string $plan_end_date 計画終了日
 * @property string $status ステータス (draft/submitted/approved/rejected/completed)
 * @property string|null $description 説明
 * @property string|null $notes 備考
 * @property string|null $submitted_at 申請日時
 * @property string|null $approved_at 承認日時
 * @property int|null $approved_by 承認者
 */
class ProductionPlan extends Model
{
  use HasFactory;
  use SoftDeletes;

  /**
   * テーブル名
   *
   * @var string
   */
  protected $table = 'production_plans';

  /**
   * ステータス定数
   */
  public const STATUS_DRAFT = 'draft';
  public const STATUS_SUBMITTED = 'submitted';
  public const STATUS_APPROVED = 'approved';
  public const STATUS_REJECTED = 'rejected';
  public const STATUS_COMPLETED = 'completed';

  /**
   * 複数代入可能な属性
   *
   * @var array<string>
   */
  protected $fillable = [
    'plan_number',
    'name',
    'plan_start_date',
    'plan_end_date',
    'status',
    'description',
    'notes',
    'submitted_at',
    'approved_at',
    'approved_by',
    'created_by',
    'updated_by',
  ];

  /**
   * キャストする属性
   *
   * @var array<string, string>
   */
  protected $casts = [
    'plan_start_date' => 'date',
    'plan_end_date' => 'date',
    'submitted_at' => 'datetime',
    'approved_at' => 'datetime',
  ];

  /**
   * 生産計画明細
   *
   * @return HasMany<ProductionPlanItem>
   */
  public function items(): HasMany
  {
    return $this->hasMany(ProductionPlanItem::class);
  }

  /**
   * 承認者
   *
   * @return BelongsTo<User, ProductionPlan>
   */
  public function approver(): BelongsTo
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  /**
   * 作成者
   *
   * @return BelongsTo<User, ProductionPlan>
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * 更新者
   *
   * @return BelongsTo<User, ProductionPlan>
   */
  public function updater(): BelongsTo
  {
    return $this->belongsTo(User::class, 'updated_by');
  }

  /**
   * 下書き状態のスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<ProductionPlan> $query
   * @return \Illuminate\Database\Eloquent\Builder<ProductionPlan>
   */
  public function scopeDraft($query)
  {
    return $query->where('status', self::STATUS_DRAFT);
  }

  /**
   * 承認済み状態のスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<ProductionPlan> $query
   * @return \Illuminate\Database\Eloquent\Builder<ProductionPlan>
   */
  public function scopeApproved($query)
  {
    return $query->where('status', self::STATUS_APPROVED);
  }

  /**
   * 期間でフィルタするスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<ProductionPlan> $query
   * @param string $startDate
   * @param string $endDate
   * @return \Illuminate\Database\Eloquent\Builder<ProductionPlan>
   */
  public function scopeInPeriod($query, string $startDate, string $endDate)
  {
    return $query->where(function ($q) use ($startDate, $endDate) {
      $q->whereBetween('plan_start_date', [$startDate, $endDate])
        ->orWhereBetween('plan_end_date', [$startDate, $endDate])
        ->orWhere(function ($q2) use ($startDate, $endDate) {
          $q2->where('plan_start_date', '<=', $startDate)
            ->where('plan_end_date', '>=', $endDate);
        });
    });
  }

  /**
   * 計画が編集可能かどうか
   *
   * @return bool
   */
  public function isEditable(): bool
  {
    return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_REJECTED]);
  }

  /**
   * 計画を申請
   *
   * @return void
   */
  public function submit(): void
  {
    $this->status = self::STATUS_SUBMITTED;
    $this->submitted_at = now();
    $this->save();
  }

  /**
   * 計画を承認
   *
   * @param int $approverId
   * @return void
   */
  public function approve(int $approverId): void
  {
    $this->status = self::STATUS_APPROVED;
    $this->approved_at = now();
    $this->approved_by = $approverId;
    $this->save();
  }

  /**
   * 計画を却下
   *
   * @return void
   */
  public function reject(): void
  {
    $this->status = self::STATUS_REJECTED;
    $this->save();
  }
}
