<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductionPlanRequest;
use App\Http\Requests\UpdateProductionPlanRequest;
use App\Models\Product;
use App\Models\ProductionPlan;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductionPlanController extends Controller
{
  use AuthorizesRequests;

  /**
   * 生産計画一覧を表示
   *
   * @param Request $request
   * @return View
   */
  public function index(Request $request): View
  {
    $this->authorize('production-plans.view');

    $query = ProductionPlan::with(['items.product']);

    // 検索フィルタ
    if ($request->filled('search')) {
      $search = $request->input('search');
      $query->where(function ($q) use ($search) {
        $q->where('plan_number', 'like', "%{$search}%")
          ->orWhere('plan_name', 'like', "%{$search}%");
      });
    }

    if ($request->filled('status')) {
      $query->where('status', $request->input('status'));
    }

    if ($request->filled('start_date')) {
      $query->where('start_date', '>=', $request->input('start_date'));
    }

    if ($request->filled('end_date')) {
      $query->where('end_date', '<=', $request->input('end_date'));
    }

    $plans = $query->orderBy('start_date', 'desc')->paginate(15);
    $statuses = ['draft' => '下書き', 'confirmed' => '確定', 'in_progress' => '進行中', 'completed' => '完了', 'cancelled' => 'キャンセル'];

    return view('production-plans.index', compact('plans', 'statuses'));
  }

  /**
   * 生産計画登録フォームを表示
   *
   * @return View
   */
  public function create(): View
  {
    $this->authorize('production-plans.create');

    $products = Product::where('is_active', true)->orderBy('code')->get();

    return view('production-plans.create', compact('products'));
  }

  /**
   * 生産計画を登録
   *
   * @param StoreProductionPlanRequest $request
   * @return RedirectResponse
   */
  public function store(StoreProductionPlanRequest $request): RedirectResponse
  {
    $data = $request->validated();
    $data['created_by'] = auth()->user()->id;

    // 生産計画を作成
    $plan = ProductionPlan::create([
      'plan_number' => $data['plan_number'],
      'plan_name' => $data['plan_name'],
      'start_date' => $data['start_date'],
      'end_date' => $data['end_date'],
      'status' => $data['status'] ?? 'draft',
      'notes' => $data['notes'] ?? null,
      'created_by' => $data['created_by'],
    ]);

    // 生産計画明細を作成
    if (isset($data['items']) && is_array($data['items'])) {
      foreach ($data['items'] as $item) {
        if (!empty($item['product_id']) && !empty($item['quantity'])) {
          $plan->items()->create([
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'scheduled_date' => $item['scheduled_date'] ?? $data['start_date'],
            'priority' => $item['priority'] ?? 5,
            'notes' => $item['notes'] ?? null,
          ]);
        }
      }
    }

    return redirect()
      ->route('production-plans.show', $plan)
      ->with('success', '生産計画を登録しました。');
  }

  /**
   * 生産計画詳細を表示
   *
   * @param ProductionPlan $productionPlan
   * @return View
   */
  public function show(ProductionPlan $productionPlan): View
  {
    $this->authorize('production-plans.view');

    $productionPlan->load(['items.product', 'creator', 'updater']);

    return view('production-plans.show', compact('productionPlan'));
  }

  /**
   * 生産計画編集フォームを表示
   *
   * @param ProductionPlan $productionPlan
   * @return View
   */
  public function edit(ProductionPlan $productionPlan): View
  {
    $this->authorize('production-plans.edit');

    $productionPlan->load('items.product');
    $products = Product::where('is_active', true)->orderBy('code')->get();

    return view('production-plans.edit', compact('productionPlan', 'products'));
  }

  /**
   * 生産計画を更新
   *
   * @param UpdateProductionPlanRequest $request
   * @param ProductionPlan $productionPlan
   * @return RedirectResponse
   */
  public function update(UpdateProductionPlanRequest $request, ProductionPlan $productionPlan): RedirectResponse
  {
    $data = $request->validated();
    $data['updated_by'] = auth()->user()->id;

    // 生産計画を更新
    $productionPlan->update([
      'plan_number' => $data['plan_number'],
      'plan_name' => $data['plan_name'],
      'start_date' => $data['start_date'],
      'end_date' => $data['end_date'],
      'status' => $data['status'],
      'notes' => $data['notes'] ?? null,
      'updated_by' => $data['updated_by'],
    ]);

    // 既存の明細を削除して再作成
    $productionPlan->items()->delete();

    if (isset($data['items']) && is_array($data['items'])) {
      foreach ($data['items'] as $item) {
        if (!empty($item['product_id']) && !empty($item['quantity'])) {
          $productionPlan->items()->create([
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'scheduled_date' => $item['scheduled_date'] ?? $data['start_date'],
            'priority' => $item['priority'] ?? 5,
            'notes' => $item['notes'] ?? null,
          ]);
        }
      }
    }

    return redirect()
      ->route('production-plans.show', $productionPlan)
      ->with('success', '生産計画を更新しました。');
  }

  /**
   * 生産計画を削除
   *
   * @param ProductionPlan $productionPlan
   * @return RedirectResponse
   */
  public function destroy(ProductionPlan $productionPlan): RedirectResponse
  {
    $this->authorize('production-plans.delete');

    // 関連データがある場合は削除を防ぐ
    if ($productionPlan->manufacturingOrders()->exists()) {
      return redirect()
        ->route('production-plans.index')
        ->with('error', 'この生産計画は製造指示が発行されているため削除できません。');
    }

    $productionPlan->items()->delete();
    $productionPlan->delete();

    return redirect()
      ->route('production-plans.index')
      ->with('success', '生産計画を削除しました。');
  }
}
