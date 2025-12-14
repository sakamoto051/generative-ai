<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkerRequest;
use App\Http\Requests\UpdateWorkerRequest;
use App\Models\Worker;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkerController extends Controller
{
  use AuthorizesRequests;

  /**
   * 作業者一覧を表示
   *
   * @param Request $request
   * @return View
   */
  public function index(Request $request): View
  {
    $this->authorize('workers.view');

    $query = Worker::query();

    // 検索フィルタ
    if ($request->filled('search')) {
      $search = $request->input('search');
      $query->where(function ($q) use ($search) {
        $q->where('code', 'like', "%{$search}%")
          ->orWhere('name', 'like', "%{$search}%");
      });
    }

    if ($request->filled('skill_level')) {
      $query->where('skill_level', $request->input('skill_level'));
    }

    if ($request->filled('is_active')) {
      $query->where('is_active', $request->boolean('is_active'));
    }

    $workers = $query->orderBy('code')->paginate(15);
    $skillLevels = ['初級', '中級', '上級', '熟練'];

    return view('workers.index', compact('workers', 'skillLevels'));
  }

  /**
   * 作業者登録フォームを表示
   *
   * @return View
   */
  public function create(): View
  {
    $this->authorize('workers.create');

    return view('workers.create');
  }

  /**
   * 作業者を登録
   *
   * @param StoreWorkerRequest $request
   * @return RedirectResponse
   */
  public function store(StoreWorkerRequest $request): RedirectResponse
  {
    $data = $request->validated();
    $data['created_by'] = auth()->user()->id;
    $data['is_active'] = $request->boolean('is_active', true);

    Worker::create($data);

    return redirect()
      ->route('workers.index')
      ->with('success', '作業者を登録しました。');
  }

  /**
   * 作業者詳細を表示
   *
   * @param Worker $worker
   * @return View
   */
  public function show(Worker $worker): View
  {
    $this->authorize('workers.view');

    $worker->load(['creator', 'updater']);

    return view('workers.show', compact('worker'));
  }

  /**
   * 作業者編集フォームを表示
   *
   * @param Worker $worker
   * @return View
   */
  public function edit(Worker $worker): View
  {
    $this->authorize('workers.edit');

    return view('workers.edit', compact('worker'));
  }

  /**
   * 作業者を更新
   *
   * @param UpdateWorkerRequest $request
   * @param Worker $worker
   * @return RedirectResponse
   */
  public function update(UpdateWorkerRequest $request, Worker $worker): RedirectResponse
  {
    $data = $request->validated();
    $data['updated_by'] = auth()->user()->id;
    $data['is_active'] = $request->boolean('is_active', $worker->is_active);

    $worker->update($data);

    return redirect()
      ->route('workers.index')
      ->with('success', '作業者を更新しました。');
  }

  /**
   * 作業者を削除
   *
   * @param Worker $worker
   * @return RedirectResponse
   */
  public function destroy(Worker $worker): RedirectResponse
  {
    $this->authorize('workers.delete');

    // 関連データがある場合は削除を防ぐ
    if ($worker->workResults()->exists()) {
      return redirect()
        ->route('workers.index')
        ->with('error', 'この作業者は使用中のため削除できません。');
    }

    $worker->delete();

    return redirect()
      ->route('workers.index')
      ->with('success', '作業者を削除しました。');
  }
}
