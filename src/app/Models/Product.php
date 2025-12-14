<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 製品マスタモデル
 *
 * @property int $id
 * @property string $code 製品コード
 * @property string $name 製品名
 * @property string|null $category カテゴリ
 * @property string|null $description 説明
 * @property string $unit 単位
 * @property float $standard_cost 標準原価
 * @property float|null $selling_price 販売価格
 * @property int $lead_time_days リードタイム（日）
 * @property int $safety_stock 安全在庫数
 * @property bool $is_active 有効フラグ
 */
class Product extends Model
{
  use HasFactory;
  use SoftDeletes;

  /**
   * テーブル名
   *
   * @var string
   */
  protected $table = 'products';

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
    'standard_cost',
    'selling_price',
    'lead_time_days',
    'safety_stock',
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
    'standard_cost' => 'decimal:2',
    'selling_price' => 'decimal:2',
    'lead_time_days' => 'integer',
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
   * @return BelongsTo<User, Product>
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * 更新者
   *
   * @return BelongsTo<User, Product>
   */
  public function updater(): BelongsTo
  {
    return $this->belongsTo(User::class, 'updated_by');
  }

  /**
   * 有効な製品のみ取得するスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<Product> $query
   * @return \Illuminate\Database\Eloquent\Builder<Product>
   */
  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  /**
   * カテゴリでフィルタするスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<Product> $query
   * @param string $category
   * @return \Illuminate\Database\Eloquent\Builder<Product>
   */
  public function scopeByCategory($query, string $category)
  {
    return $query->where('category', $category);
  }
}
