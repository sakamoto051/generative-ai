<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductionResultRequest;
use App\Models\ProductionPlanItem;
use App\Models\ProductionResult;
use Illuminate\Http\Request;

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

    // 1. Record Result
    $result = ProductionResult::create($data);

    // 2. Deduct Materials from Inventory (Backflush)
    $item = ProductionPlanItem::with('product.bomItems.childProduct')->find($data['production_plan_item_id']);

    if ($data['quantity'] > 0) {
      $this->deductMaterials($item->product, $data['quantity']);
    }

    return redirect()->route('production-plans.show', $item->production_plan_id)
      ->with('success', 'Production result recorded and materials deducted successfully.');
  }

  private function deductMaterials(\App\Models\Product $product, float $quantityProduced)
  {
    foreach ($product->bomItems as $bomItem) {
      $requiredQty = ($quantityProduced * $bomItem->quantity) / $bomItem->yield_rate;
      $childProduct = $bomItem->childProduct;

      // If child is material/part, deduct stock
      if (in_array($childProduct->type, ['material', 'part'])) {
        $childProduct->decrement('current_stock', $requiredQty);
      }
      // If child is sub-assembly (product), we might need to explode further or assume it was picked.
      // For this simplifiction, we assume sub-assemblies are also 'stocked' or we explode them.
      // Let's explode deeper if it's a 'product' type (Phantom BOM logic)
      elseif ($childProduct->type === 'product') {
        $this->deductMaterials($childProduct, $requiredQty);
      }
    }
  }
}
