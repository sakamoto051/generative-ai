@extends('layouts.app')

@section('title', '作業者マスタ')

@section('content')
<div class="space-y-6">
  <!-- ヘッダー -->
  <div class="sm:flex sm:items-center sm:justify-between">
    <div>
      <h2 class="text-2xl font-bold text-gray-900">作業者マスタ</h2>
      <p class="mt-1 text-sm text-gray-600">作業者情報の一覧と管理</p>
    </div>
    @can('workers.create')
    <div class="mt-4 sm:mt-0">
      <a href="{{ route('workers.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        新規登録
      </a>
    </div>
    @endcan
  </div>

  <!-- 検索フィルター -->
  <div class="bg-white shadow-sm sm:rounded-lg">
    <div class="p-6">
      <form method="GET" action="{{ route('workers.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label for="search" class="block text-sm font-medium text-gray-700">検索</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="コード・氏名" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          </div>
          <div>
            <label for="skill_level" class="block text-sm font-medium text-gray-700">スキルレベル</label>
            <select name="skill_level" id="skill_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
              <option value="">すべて</option>
              @foreach($skillLevels as $level)
              <option value="{{ $level }}" {{ request('skill_level') == $level ? 'selected' : '' }}>
                {{ $level }}
              </option>
              @endforeach
            </select>
          </div>
          <div>
            <label for="is_active" class="block text-sm font-medium text-gray-700">ステータス</label>
            <select name="is_active" id="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
              <option value="">すべて</option>
              <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>在籍中</option>
              <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>退職</option>
            </select>
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

  <!-- 作業者一覧テーブル -->
  <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">作業者コード</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">氏名</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">所属部署</th>
            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">スキルレベル</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">時給</th>
            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">ステータス</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($workers as $worker)
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $worker->code }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $worker->name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $worker->department ?? '-' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-center">
              @if($worker->skill_level === '熟練')
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">{{ $worker->skill_level }}</span>
              @elseif($worker->skill_level === '上級')
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $worker->skill_level }}</span>
              @elseif($worker->skill_level === '中級')
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $worker->skill_level }}</span>
              @else
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $worker->skill_level }}</span>
              @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">¥{{ number_format($worker->hourly_wage) }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-center">
              @if($worker->is_active)
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">在籍中</span>
              @else
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">退職</span>
              @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
              <a href="{{ route('workers.show', $worker) }}" class="text-indigo-600 hover:text-indigo-900">詳細</a>
              @can('workers.edit')
              <a href="{{ route('workers.edit', $worker) }}" class="text-yellow-600 hover:text-yellow-900">編集</a>
              @endcan
              @can('workers.delete')
              <form action="{{ route('workers.destroy', $worker) }}" method="POST" class="inline" onsubmit="return confirm('本当に削除しますか？')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900">削除</button>
              </form>
              @endcan
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
              作業者データがありません
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- ページネーション -->
    @if($workers->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
      {{ $workers->links() }}
    </div>
    @endif
  </div>
</div>
@endsection