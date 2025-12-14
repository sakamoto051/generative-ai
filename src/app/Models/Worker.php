<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 作業者マスタモデル
 *
 * @property int $id
 * @property string $employee_number 社員番号
 * @property string $name 氏名
 * @property string|null $department 所属部門
 * @property string|null $job_title 職種
 * @property string|null $grade 等級
 * @property float $hourly_rate 時間単価（円/時間）
 * @property string|null $skills 保有スキル
 * @property string $work_pattern 勤務パターン
 * @property bool $is_active 有効フラグ
 * @property int|null $user_id ユーザーID
 */
class Worker extends Model
{
  use HasFactory;
  use SoftDeletes;

  /**
   * テーブル名
   *
   * @var string
   */
  protected $table = 'workers';

  /**
   * 複数代入可能な属性
   *
   * @var array<string>
   */
  protected $fillable = [
    'employee_number',
    'name',
    'department',
    'job_title',
    'grade',
    'hourly_rate',
    'skills',
    'work_pattern',
    'is_active',
    'user_id',
    'created_by',
    'updated_by',
  ];

  /**
   * キャストする属性
   *
   * @var array<string, string>
   */
  protected $casts = [
    'hourly_rate' => 'decimal:2',
    'is_active' => 'boolean',
  ];

  /**
   * 関連するユーザー
   *
   * @return BelongsTo<User, Worker>
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
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
   * @return BelongsTo<User, Worker>
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * 更新者
   *
   * @return BelongsTo<User, Worker>
   */
  public function updater(): BelongsTo
  {
    return $this->belongsTo(User::class, 'updated_by');
  }

  /**
   * 有効な作業者のみ取得するスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<Worker> $query
   * @return \Illuminate\Database\Eloquent\Builder<Worker>
   */
  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  /**
   * 部門でフィルタするスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<Worker> $query
   * @param string $department
   * @return \Illuminate\Database\Eloquent\Builder<Worker>
   */
  public function scopeByDepartment($query, string $department)
  {
    return $query->where('department', $department);
  }

  /**
   * 労務費を計算
   *
   * @param int $minutes 作業時間（分）
   * @return float
   */
  public function calculateLaborCost(int $minutes): float
  {
    return ($minutes / 60) * (float) $this->hourly_rate;
  }
}
