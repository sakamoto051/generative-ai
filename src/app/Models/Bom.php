<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * BOM（部品表）モデル
 *
 * @property int $id
 * @property int $product_id 製品ID
 * @property int $material_id 材料ID
 * @property float $quantity 使用数量
 * @property float $yield_rate 歩留まり率（%）
 * @property int $sequence 工程順序
 * @property string|null $valid_from 有効開始日
 * @property string|null $valid_to 有効終了日
 * @property string|null $notes 備考
 */
class Bom extends Model
{
  use HasFactory;
  use SoftDeletes;

  /**
   * テーブル名
   *
   * @var string
   */
  protected $table = 'boms';

  /**
   * 複数代入可能な属性
   *
   * @var array<string>
   */
  protected $fillable = [
    'product_id',
    'material_id',
    'quantity',
    'yield_rate',
    'sequence',
    'valid_from',
    'valid_to',
    'notes',
    'created_by',
    'updated_by',
  ];

  /**
   * キャストする属性
   *
   * @var array<string, string>
   */
  protected $casts = [
    'quantity' => 'decimal:4',
    'yield_rate' => 'decimal:2',
    'sequence' => 'integer',
    'valid_from' => 'date',
    'valid_to' => 'date',
  ];

  /**
   * 製品
   *
   * @return BelongsTo<Product, Bom>
   */
  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * 材料
   *
   * @return BelongsTo<Material, Bom>
   */
  public function material(): BelongsTo
  {
    return $this->belongsTo(Material::class);
  }

  /**
   * 作成者
   *
   * @return BelongsTo<User, Bom>
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * 更新者
   *
   * @return BelongsTo<User, Bom>
   */
  public function updater(): BelongsTo
  {
    return $this->belongsTo(User::class, 'updated_by');
  }

  /**
   * 有効なBOMのみ取得するスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<Bom> $query
   * @return \Illuminate\Database\Eloquent\Builder<Bom>
   */
  public function scopeValid($query)
  {
    $now = now()->toDateString();
    return $query->where(function ($q) use ($now) {
      $q->where('valid_from', '<=', $now)
        ->orWhereNull('valid_from');
    })->where(function ($q) use ($now) {
      $q->where('valid_to', '>=', $now)
        ->orWhereNull('valid_to');
    });
  }

  /**
   * 実際に必要な材料数量を計算（歩留まりを考慮）
   *
   * @param float $productQuantity 製品数量
   * @return float
   */
  public function calculateRequiredQuantity(float $productQuantity): float
  {
    return ($this->quantity * $productQuantity) * (100 / $this->yield_rate);
  }
}
