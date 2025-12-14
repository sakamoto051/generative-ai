<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 材料払出モデル
 *
 * @property int $id
 * @property int $manufacturing_order_id 製造指示ID
 * @property int $material_id 材料ID
 * @property float $quantity 払出数量
 * @property string|null $lot_number ロット番号
 * @property string|null $issued_at 払出日時
 * @property int|null $issued_by 払出担当者
 * @property string|null $notes 備考
 */
class MaterialIssue extends Model
{
  use HasFactory;

  /**
   * テーブル名
   *
   * @var string
   */
  protected $table = 'material_issues';

  /**
   * 複数代入可能な属性
   *
   * @var array<string>
   */
  protected $fillable = [
    'manufacturing_order_id',
    'material_id',
    'quantity',
    'lot_number',
    'issued_at',
    'issued_by',
    'notes',
  ];

  /**
   * キャストする属性
   *
   * @var array<string, string>
   */
  protected $casts = [
    'quantity' => 'decimal:4',
    'issued_at' => 'datetime',
  ];

  /**
   * 製造指示
   *
   * @return BelongsTo<ManufacturingOrder, MaterialIssue>
   */
  public function manufacturingOrder(): BelongsTo
  {
    return $this->belongsTo(ManufacturingOrder::class);
  }

  /**
   * 材料
   *
   * @return BelongsTo<Material, MaterialIssue>
   */
  public function material(): BelongsTo
  {
    return $this->belongsTo(Material::class);
  }

  /**
   * 払出担当者
   *
   * @return BelongsTo<User, MaterialIssue>
   */
  public function issuer(): BelongsTo
  {
    return $this->belongsTo(User::class, 'issued_by');
  }

  /**
   * ロット番号でフィルタするスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<MaterialIssue> $query
   * @param string $lotNumber
   * @return \Illuminate\Database\Eloquent\Builder<MaterialIssue>
   */
  public function scopeByLot($query, string $lotNumber)
  {
    return $query->where('lot_number', $lotNumber);
  }

  /**
   * 期間でフィルタするスコープ
   *
   * @param \Illuminate\Database\Eloquent\Builder<MaterialIssue> $query
   * @param string $startDate
   * @param string $endDate
   * @return \Illuminate\Database\Eloquent\Builder<MaterialIssue>
   */
  public function scopeInPeriod($query, string $startDate, string $endDate)
  {
    return $query->whereBetween('issued_at', [$startDate, $endDate]);
  }
}
