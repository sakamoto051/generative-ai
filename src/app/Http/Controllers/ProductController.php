<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * 製品一覧を表示
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $this->authorize('products.view');

        $query = Product::query();

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

        $products = $query->orderBy('code')->paginate(15);
        $categories = Product::distinct()->pluck('category')->filter();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * 製品登録フォームを表示
     *
     * @return View
     */
    public function create(): View
    {
        $this->authorize('products.create');

        return view('products.create');
    }

    /**
     * 製品を登録
     *
     * @param StoreProductRequest $request
     * @return RedirectResponse
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $data['is_active'] = $request->boolean('is_active', true);

        Product::create($data);

        return redirect()
            ->route('products.index')
            ->with('success', '製品を登録しました。');
    }

    /**
     * 製品詳細を表示
     *
     * @param Product $product
     * @return View
     */
    public function show(Product $product): View
    {
        $this->authorize('products.view');

        $product->load(['boms.material', 'creator', 'updater']);

        return view('products.show', compact('product'));
    }

    /**
     * 製品編集フォームを表示
     *
     * @param Product $product
     * @return View
     */
    public function edit(Product $product): View
    {
        $this->authorize('products.edit');

        return view('products.edit', compact('product'));
    }

    /**
     * 製品を更新
     *
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();
        $data['is_active'] = $request->boolean('is_active', $product->is_active);

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', '製品を更新しました。');
    }

    /**
     * 製品を削除
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('products.delete');

        // 関連データがある場合は削除を防ぐ
        if ($product->boms()->exists() || $product->productionPlanItems()->exists()) {
            return redirect()
                ->route('products.index')
                ->with('error', 'この製品は使用中のため削除できません。');
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', '製品を削除しました。');
    }
}
