<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\Material;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaterialController extends Controller
{
    /**
     * 材料一覧を表示
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $this->authorize('materials.view');

        $query = Material::query();

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

        if ($request->filled('supplier')) {
            $query->where('supplier', $request->input('supplier'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->boolean('low_stock')) {
            $query->lowStock();
        }

        $materials = $query->orderBy('code')->paginate(15);
        $categories = Material::distinct()->pluck('category')->filter();
        $suppliers = Material::distinct()->pluck('supplier')->filter();

        return view('materials.index', compact('materials', 'categories', 'suppliers'));
    }

    /**
     * 材料登録フォームを表示
     *
     * @return View
     */
    public function create(): View
    {
        $this->authorize('materials.create');

        return view('materials.create');
    }

    /**
     * 材料を登録
     *
     * @param StoreMaterialRequest $request
     * @return RedirectResponse
     */
    public function store(StoreMaterialRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $data['is_active'] = $request->boolean('is_active', true);

        Material::create($data);

        return redirect()
            ->route('materials.index')
            ->with('success', '材料を登録しました。');
    }

    /**
     * 材料詳細を表示
     *
     * @param Material $material
     * @return View
     */
    public function show(Material $material): View
    {
        $this->authorize('materials.view');

        $material->load(['boms.product', 'creator', 'updater']);

        return view('materials.show', compact('material'));
    }

    /**
     * 材料編集フォームを表示
     *
     * @param Material $material
     * @return View
     */
    public function edit(Material $material): View
    {
        $this->authorize('materials.edit');

        return view('materials.edit', compact('material'));
    }

    /**
     * 材料を更新
     *
     * @param UpdateMaterialRequest $request
     * @param Material $material
     * @return RedirectResponse
     */
    public function update(UpdateMaterialRequest $request, Material $material): RedirectResponse
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();
        $data['is_active'] = $request->boolean('is_active', $material->is_active);

        $material->update($data);

        return redirect()
            ->route('materials.index')
            ->with('success', '材料を更新しました。');
    }

    /**
     * 材料を削除
     *
     * @param Material $material
     * @return RedirectResponse
     */
    public function destroy(Material $material): RedirectResponse
    {
        $this->authorize('materials.delete');

        // 関連データがある場合は削除を防ぐ
        if ($material->boms()->exists() || $material->materialIssues()->exists()) {
            return redirect()
                ->route('materials.index')
                ->with('error', 'この材料は使用中のため削除できません。');
        }

        $material->delete();

        return redirect()
            ->route('materials.index')
            ->with('success', '材料を削除しました。');
    }
}
