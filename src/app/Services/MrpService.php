<?php

namespace App\Services;

use App\Models\Bom;
use App\Models\Product;

class MrpService
{
    /**
     * Calculate total requirements recursively for a given product and quantity.
     * 
     * @param int $productId
     * @param float $quantity
     * @param array $results Accumulator for results
     * @return array
     */
    public function calculateRequirements(int $productId, float $quantity, array &$results = []): array
    {
        // Fetch children relationships
        $boms = Bom::where('parent_id', $productId)
                   ->where('parent_type', Product::class)
                   ->get();

        foreach ($boms as $bom) {
            $childQty = $bom->quantity * $quantity;
            
            // Add or update entry in results
            $this->addToResults($results, $bom->child_id, $bom->child_type, $childQty);

            if ($bom->child_type === Product::class) {
                // Recursively calculate for semi-finished products
                $this->calculateRequirements($bom->child_id, $childQty, $results);
            }
        }

        // Return indexed array for consistent format in tests
        return array_values($results);
    }

    /**
     * Helper to accumulate requirements.
     */
    protected function addToResults(array &$results, int $id, string $type, float $qty)
    {
        $key = "{$type}_{$id}";
        if (isset($results[$key])) {
            $results[$key]['total_requirement'] += $qty;
        } else {
            $results[$key] = [
                'item_id' => $id,
                'item_type' => $type,
                'total_requirement' => $qty,
            ];
        }
    }
}