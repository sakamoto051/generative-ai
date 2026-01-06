<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Material;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Update or create inventory record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|integer',
            'item_type' => 'required|string|in:product,material,App\Models\Product,App\Models\Material',
            'quantity' => 'required|numeric|min:0',
            'location' => 'nullable|string',
        ]);

        $itemType = $validated['item_type'];
        if (strtolower($itemType) === 'product') {
            $itemType = Product::class;
        } elseif (strtolower($itemType) === 'material') {
            $itemType = Material::class;
        }

        $inventory = Inventory::updateOrCreate(
            [
                'item_id' => $validated['item_id'],
                'item_type' => $itemType,
            ],
            [
                'quantity' => $validated['quantity'],
                'location' => $validated['location'] ?? null,
            ]
        );

        return response()->json([
            'message' => 'Inventory updated successfully',
            'data' => $inventory
        ], 200);
    }
}