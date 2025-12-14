<?php

namespace App\Services;

use App\Models\ProductionPlan;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ProductionPlanService
{
  /**
   * Create a new production plan.
   *
   * @param array $data
   * @param int $creatorId
   * @return ProductionPlan
   */
  public function createPlan(array $data, int $creatorId): ProductionPlan
  {
    return DB::transaction(function () use ($data, $creatorId) {
      $planNumber = $this->generatePlanNumber($data['period_start']);

      $plan = ProductionPlan::create([
        'plan_number' => $planNumber,
        'period_start' => $data['period_start'],
        'period_end' => $data['period_end'],
        'description' => $data['description'] ?? null,
        'creator_id' => $creatorId,
        'status' => 'draft',
      ]);

      // Save items if provided
      if (!empty($data['items'])) {
        foreach ($data['items'] as $item) {
          $plan->items()->create([
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'planned_start_date' => $item['planned_start_date'] ?? null,
            'planned_end_date' => $item['planned_end_date'] ?? null,
          ]);
        }
      }

      return $plan;
    });
  }

  /**
   * Update a production plan.
   *
   * @param ProductionPlan $plan
   * @param array $data
   * @return ProductionPlan
   */
  public function updatePlan(ProductionPlan $plan, array $data): ProductionPlan
  {
    return DB::transaction(function () use ($plan, $data) {
      $plan->update([
        'period_start' => $data['period_start'],
        'period_end' => $data['period_end'],
        'description' => $data['description'] ?? null,
      ]);

      // Sync items: delete all and recreate
      // Assumption: Plan is in draft, so no related production results exist yet.
      $plan->items()->delete();

      if (!empty($data['items'])) {
        foreach ($data['items'] as $item) {
          $plan->items()->create([
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'planned_start_date' => $item['planned_start_date'] ?? null,
            'planned_end_date' => $item['planned_end_date'] ?? null,
          ]);
        }
      }

      return $plan->refresh();
    });
  }

  /**
   * Calculate total standard cost for the plan.
   *
   * @param ProductionPlan $plan
   * @return float
   */
  public function calculateTotalStandardCost(ProductionPlan $plan): float
  {
    // Load items.product if not loaded
    $plan->loadMissing('items.product');

    return $plan->items->sum(function ($item) {
      return $item->quantity * $item->product->standard_cost;
    });
  }

  /**
   * Calculate material requirements for a given production plan.
   * Returns a collection of required materials with total quantity.
   *
   * @param ProductionPlan $plan
   * @return Collection
   */
  public function calculateMaterialRequirements(ProductionPlan $plan): Collection
  {
    $requirements = collect();

    // Eager load items and their product with BOM
    $plan->load('items.product.bomItems.childProduct');

    foreach ($plan->items as $planItem) {
      $this->explodeBom($planItem->product, $planItem->quantity, $requirements);
    }

    // Group by material code and sum quantities
    return $requirements->groupBy('code')->map(function ($items) {
      $first = $items->first();
      return [
        'product_id' => $first['product_id'],
        'code' => $first['code'],
        'name' => $first['name'],
        'type' => $first['type'],
        'unit_cost' => $first['unit_cost'],
        'total_quantity' => $items->sum('quantity'),
      ];
    })->values();
  }

  /**
   * Recursively explode BOM to calculate requirements.
   *
   * @param Product $product
   * @param float $quantityNeeded
   * @param Collection $requirements accumulator
   * @return void
   */
  private function explodeBom(Product $product, float $quantityNeeded, Collection $requirements): void
  {
    $bomItems = $product->bomItems;

    if ($bomItems->isEmpty()) {
      return;
    }

    foreach ($bomItems as $bomItem) {
      $requiredQty = ($quantityNeeded * $bomItem->quantity) / $bomItem->yield_rate;
      $childProduct = $bomItem->childProduct;

      // If the child product is a material or part, add to requirements
      if (in_array($childProduct->type, ['material', 'part'])) {
        $requirements->push([
          'product_id' => $childProduct->id,
          'code' => $childProduct->code,
          'name' => $childProduct->name,
          'type' => $childProduct->type,
          'unit_cost' => $childProduct->standard_cost,
          'quantity' => $requiredQty,
        ]);
      }

      // Recursively process if the child product also has a BOM (sub-assembly)
      // Even if it was added to requirements (e.g. a sub-assembly might be stocked),
      // if we are treating it as 'make' we need to explode it.
      // For simplicity here, we assume 'product' types in BOM are always exploded,
      // and 'material'/'part' are leaf nodes.
      if ($childProduct->type === 'product') {
        $this->explodeBom($childProduct, $requiredQty, $requirements);
      }
    }
  }

  /**
   * Generate a unique plan number.
   * Format: PP-YYYYMM-XXX
   *
   * @param string $periodStart
   * @return string
   */
  private function generatePlanNumber(string $periodStart): string
  {
    $date = Carbon::parse($periodStart);
    $prefix = 'PP-' . $date->format('Ym');

    // Find the last plan number for this month
    $lastPlan = ProductionPlan::where('plan_number', 'like', $prefix . '-%')
      ->orderBy('plan_number', 'desc')
      ->first();

    if (!$lastPlan) {
      return $prefix . '-001';
    }

    // Increment the sequence number
    $lastSequence = (int) substr($lastPlan->plan_number, -3);
    $newSequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);

    return $prefix . '-' . $newSequence;
  }
}
