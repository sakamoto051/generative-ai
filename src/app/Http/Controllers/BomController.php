<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreBomRequest;
use App\Http\Requests\UpdateBomRequest;
use App\Models\Bom;
use App\Models\Material;
use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BomController extends Controller
{
  use AuthorizesRequests;
  /**
   * BOM一覧を表示
   *
   * @param Request $request
   * @return View
   */
  public function index(Request $request): View
  {
    $this->authorize('boms.view');

    $query = Bom::with(['product', 'material']);

    // 検索フィルタ
    if ($request->filled('product_id')) {
      $query->where('product_id', $request->input('product_id'));
    }

    if ($request->filled('material_id')) {
      $query->where('material_id', $request->input('material_id'));
    }

    $boms = $query->orderBy('product_id')->orderBy('sequence')->paginate(15);
    $products = Product::where('is_active', true)->orderBy('code')->get();
    $materials = Material::where('is_active', true)->orderBy('code')->get();

    return view('boms.index', compact('boms', 'products', 'materials'));
  }

  /**
   * BOM登録フォームを表示
   *
   * @return View
   */
  public function create(): View
  {
    $this->authorize('boms.create');

    $products = Product::where('is_active', true)->orderBy('code')->get();
    $materials = Material::where('is_active', true)->orderBy('code')->get();

    return view('boms.create', compact('products', 'materials'));
  }

  /**
   * BOMを登録
   *
   * @param StoreBomRequest $request
   * @return RedirectResponse
   */
  public function store(StoreBomRequest $request): RedirectResponse
  {
    $data = $request->validated();
    $data['created_by'] = auth()->user()->id;

    Bom::create($data);

    return redirect()
      ->route('boms.index')
      ->with('success', 'BOMを登録しました。');
  }

  /**
   * BOM詳細を表示
   *
   * @param Bom $bom
   * @return View
   */
  public function show(Bom $bom): View
  {
    $this->authorize('boms.view');

    $bom->load(['product', 'material', 'creator', 'updater']);

    return view('boms.show', compact('bom'));
  }

  /**
   * BOM編集フォームを表示
   *
   * @param Bom $bom
   * @return View
   */
  public function edit(Bom $bom): View
  {
    $this->authorize('boms.edit');

    $products = Product::where('is_active', true)->orderBy('code')->get();
    $materials = Material::where('is_active', true)->orderBy('code')->get();

    return view('boms.edit', compact('bom', 'products', 'materials'));
  }

  /**
   * BOMを更新
   *
   * @param UpdateBomRequest $request
   * @param Bom $bom
   * @return RedirectResponse
   */
  public function update(UpdateBomRequest $request, Bom $bom): RedirectResponse
  {
    $data = $request->validated();
    $data['updated_by'] = auth()->user()->id;

    $bom->update($data);

    return redirect()
      ->route('boms.index')
      ->with('success', 'BOMを更新しました。');
  }

  /**
   * BOMを削除
   *
   * @param Bom $bom
   * @return RedirectResponse
   */
  public function destroy(Bom $bom): RedirectResponse
  {
    $this->authorize('boms.delete');

    $bom->delete();

    return redirect()
      ->route('boms.index')
      ->with('success', 'BOMを削除しました。');
  }
}
