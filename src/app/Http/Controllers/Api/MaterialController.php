<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Material::paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_code' => 'required|unique:materials,material_code',
            'name' => 'required|string',
            'category' => 'nullable|string',
            'unit' => 'nullable|string',
            'standard_price' => 'nullable|numeric',
            'lead_time' => 'nullable|numeric',
            'minimum_order_quantity' => 'nullable|numeric',
            'safety_stock' => 'nullable|numeric',
            'is_lot_managed' => 'nullable|boolean',
            'has_expiry_management' => 'nullable|boolean',
        ]);

        $material = Material::create($validated);

        return response()->json([
            'message' => 'Material created successfully',
            'data' => $material
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        return response()->json([
            'data' => $material
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'material_code' => 'sometimes|required|unique:materials,material_code,' . $material->id,
            'name' => 'sometimes|required|string',
            'category' => 'nullable|string',
            'unit' => 'nullable|string',
            'standard_price' => 'nullable|numeric',
            'lead_time' => 'nullable|numeric',
            'minimum_order_quantity' => 'nullable|numeric',
            'safety_stock' => 'nullable|numeric',
            'is_lot_managed' => 'nullable|boolean',
            'has_expiry_management' => 'nullable|boolean',
        ]);

        $material->update($validated);

        return response()->json([
            'message' => 'Material updated successfully',
            'data' => $material
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        $material->delete();

        return response()->json(null, 204);
    }
}
