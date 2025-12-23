<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_code' => 'required|unique:products,product_code',
            'name' => 'required|string',
            'category' => 'nullable|string',
            'unit' => 'nullable|string',
            'standard_cost' => 'nullable|numeric',
            'standard_manufacturing_time' => 'nullable|numeric',
            'lead_time' => 'nullable|numeric',
            'safety_stock' => 'nullable|numeric',
            'reorder_point' => 'nullable|numeric',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json([
            'data' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_code' => 'sometimes|required|unique:products,product_code,' . $product->id,
            'name' => 'sometimes|required|string',
            'category' => 'nullable|string',
            'unit' => 'nullable|string',
            'standard_cost' => 'nullable|numeric',
            'standard_manufacturing_time' => 'nullable|numeric',
            'lead_time' => 'nullable|numeric',
            'safety_stock' => 'nullable|numeric',
            'reorder_point' => 'nullable|numeric',
        ]);

        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}
