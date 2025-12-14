<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Models\Equipment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipmentController extends Controller
{
  use AuthorizesRequests;

  /**
   * 設備一覧を表示
   *
   * @param Request $request
   * @return View
   */
  public function index(Request $request): View
  {
    $this->authorize('equipment.view');

    $query = Equipment::query();

    // 検索フィルタ
    if ($request->filled('search')) {
      $search = $request->input('search');
      $query->where(function ($q) use ($search) {
        $q->where('code', 'like', "%{$search}%")
          ->orWhere('name', 'like', "%{$search}%");
      });
    }

    if ($request->filled('category')) {
      $query->where('category', $request->input('category'));
    }

    if ($request->filled('is_active')) {
      $query->where('is_active', $request->boolean('is_active'));
    }

    $equipment = $query->orderBy('code')->paginate(15);
    $categories = Equipment::distinct()->pluck('category')->filter();

    return view('equipment.index', compact('equipment', 'categories'));
  }

  /**
   * 設備登録フォームを表示
   *
   * @return View
   */
  public function create(): View
  {
    $this->authorize('equipment.create');

    return view('equipment.create');
  }

  /**
   * 設備を登録
   *
   * @param StoreEquipmentRequest $request
   * @return RedirectResponse
   */
  public function store(StoreEquipmentRequest $request): RedirectResponse
  {
    $data = $request->validated();
    $data['created_by'] = auth()->user()->id;

    Equipment::create($data);

    return redirect()
      ->route('equipment.index')
      ->with('success', '設備を登録しました。');
  }

  /**
   * 設備詳細を表示
   *
   * @param Equipment $equipment
   * @return View
   */
  public function show(Equipment $equipment): View
  {
    $this->authorize('equipment.view');

    $equipment->load(['creator', 'updater']);

    return view('equipment.show', compact('equipment'));
  }

  /**
   * 設備編集フォームを表示
   *
   * @param Equipment $equipment
   * @return View
   */
  public function edit(Equipment $equipment): View
  {
    $this->authorize('equipment.edit');

    return view('equipment.edit', compact('equipment'));
  }

  /**
   * 設備を更新
   *
   * @param UpdateEquipmentRequest $request
   * @param Equipment $equipment
   * @return RedirectResponse
   */
  public function update(UpdateEquipmentRequest $request, Equipment $equipment): RedirectResponse
  {
    $data = $request->validated();
    $data['updated_by'] = auth()->user()->id;

    $equipment->update($data);

    return redirect()
      ->route('equipment.index')
      ->with('success', '設備を更新しました。');
  }

  /**
   * 設備を削除
   *
   * @param Equipment $equipment
   * @return RedirectResponse
   */
  public function destroy(Equipment $equipment): RedirectResponse
  {
    $this->authorize('equipment.delete');

    // 関連データがある場合は削除を防ぐ
    if ($equipment->workResults()->exists()) {
      return redirect()
        ->route('equipment.index')
        ->with('error', 'この設備は使用中のため削除できません。');
    }

    $equipment->delete();

    return redirect()
      ->route('equipment.index')
      ->with('success', '設備を削除しました。');
  }
}
