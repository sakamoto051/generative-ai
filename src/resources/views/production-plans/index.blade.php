@extends('layouts.app')

@section('title', '生産計画')

@section('content')
<div class="space-y-6">
  <!-- ヘッダー -->
  <div class="sm:flex sm:items-center sm:justify-between">
    <div>
      <h2 class="text-2xl font-bold text-gray-900">生産計画</h2>
      <p class="mt-1 text-sm text-gray-600">生産計画の一覧と管理</p>
    </div>
    @can('production-plans.create')
    <div class="mt-4 sm:mt-0">
      <a href="{{ route('production-plans.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        新規作成
      </a>
    </div>
    @endcan
  </div>

  <!-- 検索フィルター -->
  <div class="bg-white shadow-sm sm:rounded-lg">
    <div class="p-6">
      <form method="GET" action="{{ route('production-plans.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
          <div>
            <label for="search" class="block text-sm font-medium text-gray-700">検索</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="計画番号・計画名" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          </div>
          <div>
            <label for="status" class="block text-sm font-medium text-gray-700">ステータス</label>
            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
              <option value="">すべて</option>
              @foreach($statuses as $key => $label)
              <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700">開始日（以降）</label>
            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          </div>
          <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700">終了日（以前）</label>
            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          </div>
          <div class="flex items-end">
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
              検索
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- 生産計画一覧テーブル -->
  <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">計画番号</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">計画名</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">期間</th>
            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">製品数</th>
            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">ステータス</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($plans as $plan)
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $plan->plan_number }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $plan->plan_name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ $plan->start_date->format('Y/m/d') }} 〜 {{ $plan->end_date->format('Y/m/d') }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">{{ $plan->items->count() }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-center">
              @if($plan->status === 'draft')
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">下書き</span>
              @elseif($plan->status === 'confirmed')
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">確定</span>
              @elseif($plan->status === 'in_progress')
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">進行中</span>
              @elseif($plan->status === 'completed')
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">完了</span>
              @else
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">キャンセル</span>
              @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
              <a href="{{ route('production-plans.show', $plan) }}" class="text-indigo-600 hover:text-indigo-900">詳細</a>
              @can('production-plans.edit')
              <a href="{{ route('production-plans.edit', $plan) }}" class="text-yellow-600 hover:text-yellow-900">編集</a>
              @endcan
              @can('production-plans.delete')
              <form action="{{ route('production-plans.destroy', $plan) }}" method="POST" class="inline" onsubmit="return confirm('本当に削除しますか？')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900">削除</button>
              </form>
              @endcan
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
              生産計画がありません
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- ページネーション -->
    @if($plans->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
      {{ $plans->links() }}
    </div>
    @endif
  </div>
</div>
@endsection