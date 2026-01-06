<?php

namespace App\Services;

use App\Models\Bom;
use App\Models\Material;
use App\Models\Product;

class BomService
{
    /**
     * Detect if adding a relationship (Parent -> Child) would create a circular reference.
     *
     * @param  int  $parentId  The ID of the parent Product.
     * @param  int  $childId  The ID of the child (Product or Material).
     * @param  string  $childType  The type of the child (e.g., App\Models\Product).
     * @return bool True if a circular reference is detected.
     */
    public function detectCircularReference(int $parentId, int $childId, string $childType = Product::class): bool
    {
        // 1. Direct self-reference
        if ($parentId === $childId && $childType === Product::class) {
            return true;
        }

        // 2. If child is a material (not a Product), it cannot be a parent (ancestor)
        if ($childType !== Product::class) {
            return false;
        }

        // 3. Recursive check: Is $childId an ancestor of $parentId?
        // i.e., Does a path exist from $childId -> ... -> $parentId?
        return $this->isAncestor($childId, $parentId);
    }

    /**
     * Check if $potentialAncestorId is an ancestor of $descendantId.
     * Both are assumed to be Products.
     */
    protected function isAncestor(int $potentialAncestorId, int $descendantId, array $visited = []): bool
    {
        if (in_array($descendantId, $visited)) {
            return false;
        }
        $visited[] = $descendantId;

        // Find parents of $descendantId
        // We are looking for BOM entries where child_id = $descendantId AND child_type = Product::class
        $parents = Bom::where('child_id', $descendantId)
            ->where('child_type', Product::class)
            ->get();

        foreach ($parents as $bom) {
            // If the parent of this descendant is the potential ancestor, we found a path.
            if ($bom->parent_id === $potentialAncestorId) {
                return true;
            }

            // Recurse up
            if ($this->isAncestor($potentialAncestorId, $bom->parent_id, $visited)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the fully expanded BOM tree for a product.
     *
     * @param  float  $multiplier  Cumulative quantity multiplier
     * @param  int  $depth  Current recursion depth
     * @param  int  $maxDepth  Maximum allowed depth
     */
    public function getBomTree(int $productId, float $multiplier = 1.0, int $depth = 0, int $maxDepth = 10): array
    {
        if ($depth > $maxDepth) {
            return [];
        }

        $product = Product::find($productId);
        if (! $product) {
            return [];
        }

        $node = [
            'id' => $product->id,
            'code' => $product->product_code,
            'name' => $product->name,
            'type' => 'product',
            'children' => [],
        ];

                // Fetch children relationships
                $boms = Bom::where('parent_id', $productId)
                           ->where('parent_type', Product::class)
                           ->with('child')
                           ->get();
        foreach ($boms as $bom) {
            $child = $bom->child;
            if (!$child) continue;

            $childQty = $bom->quantity;
            $cumulativeQty = $multiplier * $childQty;

            if ($bom->child_type === Product::class && $child instanceof Product) {
                // Recursive call
                $childNode = $this->getBomTree($child->id, $cumulativeQty, $depth + 1, $maxDepth);
                
                if (!empty($childNode)) {
                    $childNode['quantity'] = $childQty;
                    $childNode['total_quantity'] = $cumulativeQty;
                    $node['children'][] = $childNode;
                }
            } elseif ($bom->child_type === Material::class && $child instanceof Material) {
                // Material (Leaf node)
                $node['children'][] = [
                    'id' => $child->id,
                    'code' => $child->material_code,
                    'name' => $child->name,
                    'type' => 'material',
                    'quantity' => $childQty,
                    'total_quantity' => $cumulativeQty,
                    'children' => [],
                ];
            }
        }

        return $node;
    }
}
