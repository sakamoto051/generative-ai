<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductionResultRequest;
use App\Models\ProductionPlanItem;
use App\Models\ProductionResult;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionResultController extends Controller
{
  public function create(ProductionPlanItem $item)
  {
    $item->load('product', 'productionPlan');
    return view('production-results.create', compact('item'));
  }

  public function store(StoreProductionResultRequest $request)
  {
    $data = $request->validated();

    DB::transaction(function () use ($data) {
      // 1. Record Result
      // Ensure we don't double dip if we run logic here.
      $result = ProductionResult::create($data);

      // 2. Inventory Movements
      $item = ProductionPlanItem::with('product.bomItems.childProduct')->find($data['production_plan_item_id']);

      // Gross Quantity = Good + Defective
      $grossQty = $data['quantity'] + ($data['defective_quantity'] ?? 0);

      if ($grossQty > 0) {
        // Deduct Materials (consume based on Total Produced)
        $this->deductMaterials($item->product, $grossQty);
      }

      // Add Good Quantity to Stock
      if ($data['quantity'] > 0) {
        $item->product->increment('current_stock', $data['quantity']);
      }
    });

    // Retrieve item again for redirection (or use ID from data)
    $planId = ProductionPlanItem::find($data['production_plan_item_id'])->production_plan_id;

    return redirect()->route('production-plans.show', $planId)
      ->with('success', 'Production result recorded. Inventory updated (Materials consumed, Product stocked).');
  }

  /**
   * Deduct materials based on BOM.
   * Assumes all BOM items are stocked items (no recursive phantom explosion for now).
   */
  private function deductMaterials(Product $product, float $quantityProduced)
  {
    foreach ($product->bomItems as $bomItem) {
      $requiredQty = ($quantityProduced * $bomItem->quantity) / $bomItem->yield_rate;
      $bomItem->childProduct->decrement('current_stock', $requiredQty);
    }
  }
}
