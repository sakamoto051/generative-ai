<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ProductionPlan;
use Illuminate\Http\Request;

class ProductionPlanController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $plans = ProductionPlan::latest()->paginate(10);
    return view('production-plans.index', compact('plans'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $products = \App\Models\Product::where('type', 'product')->get();
    return view('production-plans.create', compact('products'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(\App\Http\Requests\StoreProductionPlanRequest $request, \App\Services\ProductionPlanService $service)
  {
    $service->createPlan($request->validated(), auth()->id());
    return redirect()->route('production-plans.index')->with('success', 'Production plan created successfully.');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(ProductionPlan $productionPlan)
  {
    if ($productionPlan->status !== 'draft') {
      return redirect()->route('production-plans.show', $productionPlan)
        ->with('error', 'Only draft plans can be edited.');
    }
    $productionPlan->load('items');
    $products = \App\Models\Product::where('type', 'product')->get();
    return view('production-plans.edit', compact('productionPlan', 'products'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(\App\Http\Requests\UpdateProductionPlanRequest $request, ProductionPlan $productionPlan, \App\Services\ProductionPlanService $service)
  {
    if ($productionPlan->status !== 'draft') {
      return back()->with('error', 'Only draft plans can be edited.');
    }
    $service->updatePlan($productionPlan, $request->validated());
    return redirect()->route('production-plans.show', $productionPlan)->with('success', 'Production plan updated successfully.');
  }

  /**
   * Display the specified resource.
   */
  public function show(ProductionPlan $productionPlan, \App\Services\ProductionPlanService $service, \App\Services\CostService $costService)
  {
    $estimatedCost = $service->calculateTotalStandardCost($productionPlan);
    $materialRequirements = $service->calculateMaterialRequirements($productionPlan);
    $costData = $costService->calculatePlanVariance($productionPlan);

    return view('production-plans.show', compact('productionPlan', 'estimatedCost', 'materialRequirements', 'costData'));
  }

  public function submit(ProductionPlan $productionPlan)
  {
    if ($productionPlan->status !== 'draft') {
      return back()->with('error', 'Only draft plans can be submitted.');
    }
    $productionPlan->update(['status' => 'pending_approval']);
    return back()->with('success', 'Plan submitted for approval.');
  }

  public function approve(ProductionPlan $productionPlan)
  {
    if ($productionPlan->status !== 'pending_approval') {
      return back()->with('error', 'Plan is not pending approval.');
    }
    $productionPlan->update(['status' => 'approved']);
    return back()->with('success', 'Plan approved.');
  }

  public function reject(ProductionPlan $productionPlan)
  {
    if ($productionPlan->status !== 'pending_approval') {
      return back()->with('error', 'Plan is not pending approval.');
    }
    $productionPlan->update(['status' => 'rejected']);
    return back()->with('success', 'Plan rejected.');
  }

  public function generatePurchaseOrders(ProductionPlan $productionPlan, \App\Services\ProductionPlanService $planService, \App\Services\PurchaseService $purchaseService)
  {
    if ($productionPlan->status !== 'approved') {
      return back()->with('error', 'Purchase orders can only be generated for approved plans.');
    }

    $requirements = $planService->calculateMaterialRequirements($productionPlan);

    if ($requirements->isEmpty()) {
      return back()->with('info', 'No material requirements found for this plan.');
    }

    $orders = $purchaseService->createPurchaseOrdersFromRequirements($requirements);

    return back()->with('success', "Generated {$orders->count()} purchase orders.");
  }
}
