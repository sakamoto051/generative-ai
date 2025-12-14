<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
  protected $fillable = [
    'purchase_order_id',
    'product_id',
    'quantity',
    'unit_price',
    'subtotal',
  ];

  protected $casts = [
    'quantity' => 'decimal:4',
    'unit_price' => 'decimal:2',
    'subtotal' => 'decimal:2',
  ];

  public function purchaseOrder(): BelongsTo
  {
    return $this->belongsTo(PurchaseOrder::class);
  }

  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }
}
