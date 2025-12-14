@extends('layouts.app')

@section('title', '製品マスタ')

@section('content')
<div class="space-y-6">
  <!-- ヘッダー -->
  <div class="sm:flex sm:items-center sm:justify-between">
    <div>
      <h2 class="text-2xl font-bold text-gray-900">製品マスタ</h2>
      <p class="mt-1 text-sm text-gray-600">製品情報の一覧と管理</p>
    </div>
    @can('products.create')
    <div class="mt-4 sm:mt-0">
      <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
      <form method="GET" action="{{ route('products.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label for="search" class="block text-sm font-medium text-gray-700">検索</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="コード・製品名" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          </div>
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700">カテゴリ</label>
            <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
              <option value="">すべて</option>
              @foreach($categories as $category)
              <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                {{ $category }}
              </option>
              @endforeach
            </select>
          </div>
          <div>
            <label for="is_active" class="block text-sm font-medium text-gray-700">ステータス</label>
            <select name="is_active" id="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
              <option value="">すべて</option>
              <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>有効</option>
              <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>無効</option>
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

  <!-- 製品一覧テーブル -->
  <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">製品コード</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">製品名</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">カテゴリ</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">単位</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">標準原価</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">販売価格</th>
            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">ステータス</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($products as $product)
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->code }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->category }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->unit }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">¥{{ number_format($product->standard_cost) }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
              @if($product->selling_price)
              ¥{{ number_format($product->selling_price) }}
              @else
              <span class="text-gray-400">-</span>
              @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center">
              @if($product->is_active)
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">有効</span>
              @else
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">無効</span>
              @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
              <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:text-indigo-900">詳細</a>
              @can('products.edit')
              <a href="{{ route('products.edit', $product) }}" class="text-yellow-600 hover:text-yellow-900">編集</a>
              @endcan
              @can('products.delete')
              <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('本当に削除しますか？')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900">削除</button>
              </form>
              @endcan
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
              製品データがありません
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- ページネーション -->
    @if($products->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
      {{ $products->links() }}
    </div>
    @endif
  </div>
</div>
@endsection