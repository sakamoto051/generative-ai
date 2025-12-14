<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 材料マス

タモデル
 *
 * @property int $id
 * @property string $code 材料コード
 * @property string $name 材料名
 * @property string|null $category カテゴリ
 * @property string|null $description 説明
 * @property string $unit 単位
 * @property float $unit_price 単価
 * @property string|null $supplier 仕入先
 * @property int $lead_time_days リードタイム（日）
 * @property int $current_stock 現在庫数
 * @property int $safety_stock 安全在庫数
 * @property string $lot_management ロット管理（none/fifo/lifo）
 * @property bool $is_active 有効フラグ
 */
class Material extends Model
{
  use HasFactory;
  use SoftDeletes;

  /**
   * テーブル名
   *
   * @var string
   */
  protected $table = 'materials';

  /**
   * 複数代入可能な属性
   *
   * @var array<string>
   */
  protected $fillable = [
    'code',
    'name',
    'category',
    'description',
    'unit',
    'unit_price',
    'supplier',
    'lead_time_days',
    'current_stock',
    'safety_stock',
    'lot_management',
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
    'unit_price' => 'decimal:2',
    'lead_time_days' => 'integer',
    'current_stock' => 'integer',
    'safety_stock' => 'integer',
    'is_active' => 'boolean',
  ];

  /**
   * BOM（部品表）
   *
   * @return HasMany<Bom>
   */
  public function boms(): HasMany
  {
    return $this->hasMany(Bom::class);
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
   * 作成者
   *
   * @return BelongsTo<User, Material>
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * 更新者
   *
   * @return BelongsTo<User, Material>
   */
  public function updater(): BelongsTo
  {
    return $this->belongsTo(User::class, 'updated_by');
  }

  /**
   * 有効な材料のみ取得するスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<Material> $query
   * @return \Illuminate\Database\Eloquent\Builder<Material>
   */
  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  /**
   * 在庫不足の材料を取得するスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<Material> $query
   * @return \Illuminate\Database\Eloquent\Builder<Material>
   */
  public function scopeLowStock($query)
  {
    return $query->whereColumn('current_stock', '<=', 'safety_stock');
  }

  /**
   * 仕入先でフィルタするスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<Material> $query
   * @param string $supplier
   * @return \Illuminate\Database\Eloquent\Builder<Material>
   */
  public function scopeBySupplier($query, string $supplier)
  {
    return $query->where('supplier', $supplier);
  }
}
