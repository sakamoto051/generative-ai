<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'po_number',
    'supplier_id',
    'status',
    'order_date',
    'delivery_due_date',
    'total_amount',
  ];

  protected $casts = [
    'order_date' => 'date',
    'delivery_due_date' => 'date',
    'total_amount' => 'decimal:2',
  ];

  public function supplier(): BelongsTo
  {
    return $this->belongsTo(Supplier::class);
  }

  public function items(): HasMany
  {
    return $this->hasMany(PurchaseOrderItem::class);
  }
}
