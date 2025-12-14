<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
  public function index()
  {
    $suppliers = Supplier::latest()->paginate(10);
    return view('suppliers.index', compact('suppliers'));
  }

  public function create()
  {
    return view('suppliers.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'code' => 'required|string|unique:suppliers,code|max:255',
      'name' => 'required|string|max:255',
      'contact_person' => 'nullable|string|max:255',
      'email' => 'nullable|email|max:255',
      'phone' => 'nullable|string|max:20',
      'address' => 'nullable|string|max:1000',
    ]);

    Supplier::create($validated);

    return redirect()->route('suppliers.index')
      ->with('success', 'Supplier created successfully.');
  }

  public function show(Supplier $supplier)
  {
    return view('suppliers.show', compact('supplier'));
  }

  public function edit(Supplier $supplier)
  {
    return view('suppliers.edit', compact('supplier'));
  }

  public function update(Request $request, Supplier $supplier)
  {
    $validated = $request->validate([
      'code' => 'required|string|max:255|unique:suppliers,code,' . $supplier->id,
      'name' => 'required|string|max:255',
      'contact_person' => 'nullable|string|max:255',
      'email' => 'nullable|email|max:255',
      'phone' => 'nullable|string|max:20',
      'address' => 'nullable|string|max:1000',
    ]);

    $supplier->update($validated);

    return redirect()->route('suppliers.index')
      ->with('success', 'Supplier updated successfully.');
  }

  public function destroy(Supplier $supplier)
  {
    $supplier->delete();

    return redirect()->route('suppliers.index')
      ->with('success', 'Supplier deleted successfully.');
  }
}
