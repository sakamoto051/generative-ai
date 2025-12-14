<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseService
{
  /**
   * Create purchase orders from calculated requirements.
   * This logic assumes a single supplier for all materials for simplicity,
   * or could be refined to group by supplier.
   *
   * @param Collection $materialRequirements
   * @return Collection<PurchaseOrder>
   */
  public function createPurchaseOrdersFromRequirements(Collection $materialRequirements): Collection
  {
    // 1. Group materials by supplier (assuming product has a preferred supplier, 
    // but for now, we'll assign a default supplier or just one PO if we don't have supplier mapping)
    // Since we didn't implement Product-Supplier link yet, let's fetch the first supplier.
    $defaultSupplier = Supplier::first();

    if (!$defaultSupplier) {
      throw new \Exception("No supplier found in the system.");
    }

    // Group by Supplier ID (To be implemented fully later, now all go to default)
    $groupedRequirements = $materialRequirements->groupBy(function ($item) use ($defaultSupplier) {
      return $defaultSupplier->id;
    });

    $orders = collect();

    DB::transaction(function () use ($groupedRequirements, &$orders) {
      foreach ($groupedRequirements as $supplierId => $items) {
        $supplier = Supplier::find($supplierId);

        // Calculate total
        $totalAmount = $items->sum(function ($item) {
          return $item['quantity'] * $item['unit_cost'];
        });

        // Create PO
        $po = PurchaseOrder::create([
          'po_number' => $this->generatePoNumber(),
          'supplier_id' => $supplier->id,
          'status' => 'draft',
          'order_date' => now(),
          'delivery_due_date' => now()->addDays(7), // Default delivery time
          'total_amount' => $totalAmount,
        ]);

        // Create Items
        foreach ($items as $item) {
          $po->items()->create([
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'unit_price' => $item['unit_cost'],
            'subtotal' => $item['quantity'] * $item['unit_cost'],
          ]);
        }

        $orders->push($po);
      }
    });

    return $orders;
  }

  private function generatePoNumber(): string
  {
    $prefix = 'PO-' . now()->format('Ym');
    $lastPo = PurchaseOrder::where('po_number', 'like', $prefix . '-%')
      ->orderBy('po_number', 'desc')
      ->first();

    if (!$lastPo) {
      return $prefix . '-001';
    }

    $lastSequence = (int) substr($lastPo->po_number, -3);
    $newSequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);

    return $prefix . '-' . $newSequence;
  }
}
