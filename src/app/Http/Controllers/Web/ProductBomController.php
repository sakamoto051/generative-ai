<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BomItem;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductBomController extends Controller
{
  public function index(Product $product)
  {
    $bomItems = $product->bomItems()->with('childProduct')->get();
    return view('products.bom.index', compact('product', 'bomItems'));
  }

  public function create(Product $product)
  {
    // Get potential child products (exclude self and maybe restrict to parts/materials if desired)
    // For simplicity, excluding self.
    $candidates = Product::where('id', '!=', $product->id)
      ->whereIn('type', ['part', 'material', 'product']) // Depending on if sub-assemblies are allowed
      ->orderBy('name')
      ->get();

    return view('products.bom.create', compact('product', 'candidates'));
  }

  public function store(Request $request, Product $product)
  {
    $validated = $request->validate([
      'child_product_id' => 'required|exists:products,id|different:parent_product_id',
      'quantity' => 'required|numeric|min:0.0001',
      'yield_rate' => 'required|numeric|min:0.0001|max:1.0',
    ]);

    // Prevent duplicates
    if ($product->bomItems()->where('child_product_id', $validated['child_product_id'])->exists()) {
      return back()->withErrors(['child_product_id' => 'This product is already in the BOM.']);
    }

    $product->bomItems()->create([
      'child_product_id' => $validated['child_product_id'],
      'quantity' => $validated['quantity'],
      'yield_rate' => $validated['yield_rate'],
    ]);

    return redirect()->route('products.bom.index', $product)
      ->with('success', 'BOM item added successfully.');
  }

  public function edit(Product $product, BomItem $bomItem)
  {
    return view('products.bom.edit', compact('product', 'bomItem'));
  }

  public function update(Request $request, Product $product, BomItem $bomItem)
  {
    $validated = $request->validate([
      'quantity' => 'required|numeric|min:0.0001',
      'yield_rate' => 'required|numeric|min:0.0001|max:1.0',
    ]);

    $bomItem->update($validated);

    return redirect()->route('products.bom.index', $product)
      ->with('success', 'BOM item updated successfully.');
  }

  public function destroy(Product $product, BomItem $bomItem)
  {
    $bomItem->delete();

    return redirect()->route('products.bom.index', $product)
      ->with('success', 'BOM item removed successfully.');
  }
}
