@extends('layouts.app')

@section('title', 'BOMマスタ')

@section('content')
<div class="space-y-6">
  <!-- ヘッダー -->
  <div class="sm:flex sm:items-center sm:justify-between">
    <div>
      <h2 class="text-2xl font-bold text-gray-900">BOMマスタ</h2>
      <p class="mt-1 text-sm text-gray-600">部品表（Bill of Materials）の一覧と管理</p>
    </div>
    @can('boms.create')
    <div class="mt-4 sm:mt-0">
      <a href="{{ route('boms.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
      <form method="GET" action="{{ route('boms.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label for="product_id" class="block text-sm font-medium text-gray-700">製品</label>
            <select name="product_id" id="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
              <option value="">すべて</option>
              @foreach($products as $product)
              <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                {{ $product->code }} - {{ $product->name }}
              </option>
              @endforeach
            </select>
          </div>
          <div>
            <label for="material_id" class="block text-sm font-medium text-gray-700">材料</label>
            <select name="material_id" id="material_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
              <option value="">すべて</option>
              @foreach($materials as $material)
              <option value="{{ $material->id }}" {{ request('material_id') == $material->id ? 'selected' : '' }}>
                {{ $material->code }} - {{ $material->name }}
              </option>
              @endforeach
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

  <!-- BOM一覧テーブル -->
  <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">製品コード</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">製品名</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">材料コード</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">材料名</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">使用数量</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">歩留まり率</th>
            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">順序</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($boms as $bom)
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $bom->product->code }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bom->product->name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $bom->material->code }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bom->material->name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ number_format($bom->quantity, 2) }} {{ $bom->unit }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ $bom->yield_rate }}%</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $bom->sequence ?? '-' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
              <a href="{{ route('boms.show', $bom) }}" class="text-indigo-600 hover:text-indigo-900">詳細</a>
              @can('boms.edit')
              <a href="{{ route('boms.edit', $bom) }}" class="text-yellow-600 hover:text-yellow-900">編集</a>
              @endcan
              @can('boms.delete')
              <form action="{{ route('boms.destroy', $bom) }}" method="POST" class="inline" onsubmit="return confirm('本当に削除しますか？')">
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
              BOMデータがありません
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- ページネーション -->
    @if($boms->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
      {{ $boms->links() }}
    </div>
    @endif
  </div>
</div>
@endsection