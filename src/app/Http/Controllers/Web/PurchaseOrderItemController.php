<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class PurchaseOrderItemController extends Controller
{
  public function create(PurchaseOrder $purchaseOrder)
  {
    if ($purchaseOrder->status !== 'draft') {
      return redirect()->route('purchase-orders.show', $purchaseOrder)
        ->with('error', 'Cannot add items to a finalized order.');
    }

    $products = Product::whereIn('type', ['part', 'material'])
      ->orderBy('name')
      ->get();

    return view('purchase-orders.items.create', compact('purchaseOrder', 'products'));
  }

  public function store(Request $request, PurchaseOrder $purchaseOrder)
  {
    if ($purchaseOrder->status !== 'draft') {
      return back()->with('error', 'Cannot modify items of a finalized order.');
    }

    $validated = $request->validate([
      'product_id' => 'required|exists:products,id',
      'quantity' => 'required|numeric|min:0.0001',
      'unit_price' => 'required|numeric|min:0',
    ]);

    $subtotal = $validated['quantity'] * $validated['unit_price'];

    $purchaseOrder->items()->create([
      'product_id' => $validated['product_id'],
      'quantity' => $validated['quantity'],
      'unit_price' => $validated['unit_price'],
      'subtotal' => $subtotal,
    ]);

    $this->updateOrderTotal($purchaseOrder);

    return redirect()->route('purchase-orders.show', $purchaseOrder)
      ->with('success', 'Item added to purchase order.');
  }

  public function edit(PurchaseOrder $purchaseOrder, PurchaseOrderItem $item)
  {
    if ($purchaseOrder->status !== 'draft') {
      return back()->with('error', 'Cannot edit items of a finalized order.');
    }

    return view('purchase-orders.items.edit', compact('purchaseOrder', 'item'));
  }

  public function update(Request $request, PurchaseOrder $purchaseOrder, PurchaseOrderItem $item)
  {
    if ($purchaseOrder->status !== 'draft') {
      return back()->with('error', 'Cannot edit items of a finalized order.');
    }

    $validated = $request->validate([
      'quantity' => 'required|numeric|min:0.0001',
      'unit_price' => 'required|numeric|min:0',
    ]);

    $subtotal = $validated['quantity'] * $validated['unit_price'];

    $item->update([
      'quantity' => $validated['quantity'],
      'unit_price' => $validated['unit_price'],
      'subtotal' => $subtotal,
    ]);

    $this->updateOrderTotal($purchaseOrder);

    return redirect()->route('purchase-orders.show', $purchaseOrder)
      ->with('success', 'Item updated.');
  }

  public function destroy(PurchaseOrder $purchaseOrder, PurchaseOrderItem $item)
  {
    if ($purchaseOrder->status !== 'draft') {
      return back()->with('error', 'Cannot remove items from a finalized order.');
    }

    $item->delete();
    $this->updateOrderTotal($purchaseOrder);

    return redirect()->route('purchase-orders.show', $purchaseOrder)
      ->with('success', 'Item removed.');
  }

  private function updateOrderTotal(PurchaseOrder $purchaseOrder)
  {
    $total = $purchaseOrder->items()->sum('subtotal');
    $purchaseOrder->update(['total_amount' => $total]);
  }
}
