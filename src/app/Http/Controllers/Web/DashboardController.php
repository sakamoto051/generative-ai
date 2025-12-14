<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductionPlan;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  public function index()
  {
    // 1. Production Metrics
    $activePlansCount = ProductionPlan::whereIn('status', ['approved', 'pending_approval'])->count();

    $recentPlans = ProductionPlan::with('items.results')
      ->latest()
      ->take(5)
      ->get();

    // Calculate progress for recent plans
    $recentPlans->each(function ($plan) {
      $totalQty = $plan->items->sum('quantity');
      $completedQty = 0;
      foreach ($plan->items as $item) {
        $completedQty += $item->results->sum('quantity');
      }
      $plan->progress = $totalQty > 0 ? ($completedQty / $totalQty) * 100 : 0;
    });

    // 2. Inventory Health
    $lowStockProducts = Product::whereColumn('current_stock', '<=', 'minimum_stock_level')
      ->where('minimum_stock_level', '>', 0)
      ->take(5)
      ->get();

    $lowStockCount = Product::whereColumn('current_stock', '<=', 'minimum_stock_level')
      ->where('minimum_stock_level', '>', 0)
      ->count();

    // 3. Procurement Status
    $purchaseOrdersCount = PurchaseOrder::where('status', 'ordered')->count(); // Pending Receipt
    $draftPosCount = PurchaseOrder::where('status', 'draft')->count();
    $pendingReceiptAmount = PurchaseOrder::where('status', 'ordered')->sum('total_amount');

    return view('dashboard', compact(
      'activePlansCount',
      'recentPlans',
      'lowStockProducts',
      'lowStockCount',
      'purchaseOrdersCount',
      'draftPosCount',
      'pendingReceiptAmount'
    ));
  }
}
