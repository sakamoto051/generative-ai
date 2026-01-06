<?php

namespace App\Services;

use App\Models\Bom;

class BomService
{
    /**
     * Detect if adding a relationship (Parent -> Child) would create a circular reference.
     *
     * @param int $parentId The ID of the parent Product.
     * @param int $childId The ID of the child (Product or Material).
     * @param string $childType The type of the child ('product' or 'material').
     * @return bool True if a circular reference is detected.
     */
    public function detectCircularReference(int $parentId, int $childId, string $childType = 'product'): bool
    {
        // 1. Direct self-reference
        if ($parentId === $childId && $childType === 'product') {
            return true;
        }

        // 2. If child is a material, it cannot be a parent (ancestor), so no cycle possible
        // unless we strictly forbid material-material links (but parent is always product).
        if ($childType !== 'product') {
            return false;
        }

        // 3. Recursive check: Is $childId an ancestor of $parentId?
        // i.e., Does a path exist from $childId -> ... -> $parentId?
        return $this->isAncestor($childId, $parentId);
    }

    /**
     * Check if $potentialAncestorId is an ancestor of $descendantId.
     * Both are assumed to be Products.
     *
     * @param int $potentialAncestorId
     * @param int $descendantId
     * @param array $visited
     * @return bool
     */
    protected function isAncestor(int $potentialAncestorId, int $descendantId, array $visited = []): bool
    {
        if (in_array($descendantId, $visited)) {
            return false;
        }
        $visited[] = $descendantId;

        // Find parents of $descendantId
        // We are looking for BOM entries where child_id = $descendantId AND child_type = 'product'
        $parents = Bom::where('child_id', $descendantId)
                      ->where('child_type', 'product')
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
}
