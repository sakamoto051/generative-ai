<?php

namespace App\Services;

use App\Models\ProductionPlan;
use App\Models\ProductionPlanItem;
use App\Models\Product;

class CostService
{
  /**
   * Calculate cost variance for a production plan.
   *
   * @param ProductionPlan $plan
   * @return array
   */
  public function calculatePlanVariance(ProductionPlan $plan): array
  {
    $plan->load('items.product', 'items.results');

    $totalPlannedCost = 0;
    $totalActualCost = 0;
    $items = [];

    foreach ($plan->items as $item) {
      $product = $item->product;
      $stdCost = $product->standard_cost;

      // Planned
      $plannedQty = $item->quantity;
      $plannedCost = $plannedQty * $stdCost;

      // Actual (Based on results)
      $actualQty = $item->results->sum('quantity');
      $actualCost = $actualQty * $stdCost;

      $variance = $plannedCost - $actualCost; // Positive means under budget (or simply not done yet)

      // In a real manufacturing context:
      // Variance usually compares "Actual Cost incurred" vs "Standard Cost allowed for Actual Production".
      // But here, let's compare "Total Planned Budget" vs "Current Actual Output Value".

      $totalPlannedCost += $plannedCost;
      $totalActualCost += $actualCost;

      $items[] = [
        'product_name' => $product->name,
        'planned_qty' => $plannedQty,
        'actual_qty' => $actualQty,
        'standard_unit_cost' => $stdCost,
        'planned_cost' => $plannedCost,
        'actual_cost' => $actualCost,
        'variance' => $variance,
      ];
    }

    return [
      'total_planned_cost' => $totalPlannedCost,
      'total_actual_cost' => $totalActualCost,
      'total_variance' => $totalPlannedCost - $totalActualCost,
      'items' => $items,
    ];
  }
}
