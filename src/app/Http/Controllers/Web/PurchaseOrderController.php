<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
  public function index()
  {
    $purchaseOrders = PurchaseOrder::with('supplier')->latest()->paginate(10);
    return view('purchase-orders.index', compact('purchaseOrders'));
  }

  public function create()
  {
    $suppliers = Supplier::orderBy('name')->get();
    // Generate a draft PO number or let user input. Let's generate one.
    $nextId = (PurchaseOrder::max('id') ?? 0) + 1;
    $suggestedPoNumber = 'PO-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

    return view('purchase-orders.create', compact('suppliers', 'suggestedPoNumber'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'po_number' => 'required|string|unique:purchase_orders,po_number|max:255',
      'supplier_id' => 'required|exists:suppliers,id',
      'order_date' => 'required|date',
      'delivery_due_date' => 'nullable|date|after_or_equal:order_date',
    ]);

    $validated['status'] = 'draft';
    $validated['total_amount'] = 0; // Will be calculated from items

    $po = PurchaseOrder::create($validated);

    return redirect()->route('purchase-orders.show', $po)
      ->with('success', 'Purchase Order created successfully. Please add items.');
  }

  public function show(PurchaseOrder $purchaseOrder)
  {
    $purchaseOrder->load(['supplier', 'items.product']);
    return view('purchase-orders.show', compact('purchaseOrder'));
  }

  public function edit(PurchaseOrder $purchaseOrder)
  {
    if ($purchaseOrder->status !== 'draft') {
      return redirect()->back()->with('error', 'Cannot edit a purchase order that is not in draft status.');
    }
    $suppliers = Supplier::orderBy('name')->get();
    return view('purchase-orders.edit', compact('purchaseOrder', 'suppliers'));
  }

  public function update(Request $request, PurchaseOrder $purchaseOrder)
  {
    if ($purchaseOrder->status !== 'draft') {
      return redirect()->back()->with('error', 'Cannot edit a purchase order that is not in draft status.');
    }

    $validated = $request->validate([
      'po_number' => 'required|string|max:255|unique:purchase_orders,po_number,' . $purchaseOrder->id,
      'supplier_id' => 'required|exists:suppliers,id',
      'order_date' => 'required|date',
      'delivery_due_date' => 'nullable|date|after_or_equal:order_date',
    ]);

    $purchaseOrder->update($validated);

    return redirect()->route('purchase-orders.show', $purchaseOrder)
      ->with('success', 'Purchase Order details updated.');
  }

  public function destroy(PurchaseOrder $purchaseOrder)
  {
    if ($purchaseOrder->status !== 'draft') {
      return redirect()->back()->with('error', 'Only draft purchase orders can be deleted.');
    }

    $purchaseOrder->delete();

    return redirect()->route('purchase-orders.index')
      ->with('success', 'Purchase Order deleted.');
  }

  public function submit(PurchaseOrder $purchaseOrder)
  {
    if ($purchaseOrder->status !== 'draft') {
      return back()->with('error', 'Only draft orders can be submitted.');
    }

    if ($purchaseOrder->items()->count() === 0) {
      return back()->with('error', 'Cannot submit an empty order.');
    }

    // Here one might trigger notifications, PDF generation, etc.
    $purchaseOrder->update(['status' => 'ordered']);

    return redirect()->route('purchase-orders.show', $purchaseOrder)
      ->with('success', 'Purchase Order has been submitted (Ordered).');
  }

  public function receive(PurchaseOrder $purchaseOrder)
  {
    if ($purchaseOrder->status !== 'ordered') {
      return back()->with('error', 'Only submitted (ordered) purchase orders can be received.');
    }

    DB::transaction(function () use ($purchaseOrder) {
      // Update stock levels
      foreach ($purchaseOrder->items as $item) {
        $item->product->increment('current_stock', $item->quantity);
      }

      // Update status
      $purchaseOrder->update(['status' => 'received']);
    });

    return redirect()->route('purchase-orders.show', $purchaseOrder)
      ->with('success', 'Purchase Order received. Stock updated successfully.');
  }
}
