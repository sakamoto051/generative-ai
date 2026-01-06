<?php

namespace App\Services;

use App\Models\Bom;
use App\Models\Inventory;
use App\Models\Product;

class MrpService
{
    /**
     * Track available stock during a calculation session to avoid double-counting.
     */
    protected array $sessionStock = [];

    /**
     * Calculate total and net requirements recursively for a given product and quantity.
     *
     * @param  int  $productId
     * @param  float  $quantity
     * @param  array  $results  Accumulator for results
     * @param  bool  $isRoot  Internal flag to identify the root call
     * @return array
     */
    public function calculateRequirements(int $productId, float $quantity, array &$results = [], bool $isRoot = true): array
    {
        if ($isRoot) {
            $this->sessionStock = [];
        }

        // Fetch children relationships
        $boms = Bom::where('parent_id', $productId)
            ->where('parent_type', Product::class)
            ->get();

        foreach ($boms as $bom) {
            $totalReq = $bom->quantity * $quantity;

            $itemKey = "{$bom->child_type}_{$bom->child_id}";

            // Initialize session stock if not already cached
            if (! isset($this->sessionStock[$itemKey])) {
                $inventory = Inventory::where('item_id', $bom->child_id)
                    ->where('item_type', $bom->child_type)
                    ->first();
                $this->sessionStock[$itemKey] = $inventory ? (float) $inventory->quantity : 0.0;
            }

            $availableStock = $this->sessionStock[$itemKey];
            $applied = min($totalReq, $availableStock);
            $netReq = max(0.0, $totalReq - $applied);

            // Update session stock for this item
            $this->sessionStock[$itemKey] -= $applied;

            // Add or update entry in results
            $this->addToResults($results, $bom->child_id, $bom->child_type, $totalReq, $applied, $netReq);

            if ($bom->child_type === Product::class && $netReq > 0) {
                // Recursively calculate for remaining net requirement
                $this->calculateRequirements($bom->child_id, $netReq, $results, false);
            }
        }

        // Return indexed array for consistent format at the end
        return $isRoot ? array_values($results) : $results;
    }

    /**
     * Helper to accumulate requirements.
     */
    protected function addToResults(array &$results, int $id, string $type, float $total, float $applied, float $net)
    {
        $key = "{$type}_{$id}";
        if (isset($results[$key])) {
            $results[$key]['total_requirement'] += $total;
            $results[$key]['inventory_applied'] += $applied;
            $results[$key]['net_requirement'] += $net;
        } else {
            $results[$key] = [
                'item_id' => $id,
                'item_type' => $type,
                'total_requirement' => $total,
                'inventory_applied' => $applied,
                'net_requirement' => $net,
            ];
        }
    }
}
