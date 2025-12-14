<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
  protected $fillable = [
    'code',
    'name',
    'contact_person',
    'email',
    'phone',
    'address',
  ];

  public function purchaseOrders(): HasMany
  {
    return $this->hasMany(PurchaseOrder::class);
  }
}
