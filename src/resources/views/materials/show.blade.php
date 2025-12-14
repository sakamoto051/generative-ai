@extends('layouts.app')

@section('title', '材料詳細')

@section('content')
<div class="max-w-4xl mx-auto">
  <!-- ヘッダー -->
  <div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
      <h2 class="text-2xl font-bold text-gray-900">材料詳細</h2>
      <p class="mt-1 text-sm text-gray-600">材料コード: {{ $material->code }}</p>
    </div>
    <div class="mt-4 sm:mt-0 flex space-x-3">
      <a href="{{ route('materials.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        一覧に戻る
      </a>
      @can('materials.edit')
      <a href="{{ route('materials.edit', $material) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        編集
      </a>
      @endcan
    </div>
  </div>

  <div class="space-y-6">
    <!-- 基本情報 -->
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">基本情報</h3>
      </div>
      <div class="px-6 py-4">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
          <div>
            <dt class="text-sm font-medium text-gray-500">材料コード</dt>
            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $material->code }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">材料名</dt>
            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $material->name }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">カテゴリ</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $material->category }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">単位</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $material->unit }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">仕入先</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $material->supplier ?? '-' }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">ステータス</dt>
            <dd class="mt-1">
              @if($material->is_active)
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">有効</span>
              @else
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">無効</span>
              @endif
            </dd>
          </div>
          @if($material->description)
          <div class="md:col-span-2">
            <dt class="text-sm font-medium text-gray-500">説明</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $material->description }}</dd>
          </div>
          @endif
        </dl>
      </div>
    </div>

    <!-- 価格・在庫情報 -->
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">価格・在庫情報</h3>
      </div>
      <div class="px-6 py-4">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
          <div>
            <dt class="text-sm font-medium text-gray-500">単価</dt>
            <dd class="mt-1 text-lg font-bold text-gray-900">¥{{ number_format($material->unit_price) }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">リードタイム</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $material->lead_time_days }} 日</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">現在在庫数</dt>
            <dd class="mt-1 text-lg font-bold {{ $material->current_stock <= $material->safety_stock ? 'text-red-600' : 'text-gray-900' }}">
              {{ number_format($material->current_stock) }} {{ $material->unit }}
            </dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">安全在庫数</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ number_format($material->safety_stock) }} {{ $material->unit }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">発注点</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ number_format($material->reorder_point) }} {{ $material->unit }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">在庫状況</dt>
            <dd class="mt-1">
              @if($material->current_stock <= $material->safety_stock)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">在庫不足</span>
                @elseif($material->current_stock <= $material->reorder_point)
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">発注推奨</span>
                  @else
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">在庫充分</span>
                  @endif
            </dd>
          </div>
        </dl>
      </div>
    </div>

    <!-- 使用製品情報 -->
    @if($material->boms->count() > 0)
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">使用製品</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">製品コード</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">製品名</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">使用数量</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">歩留まり率</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($material->boms as $bom)
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                <a href="{{ route('products.show', $bom->product) }}" class="text-indigo-600 hover:text-indigo-900">
                  {{ $bom->product->code }}
                </a>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bom->product->name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ $bom->quantity }} {{ $material->unit }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ $bom->yield_rate }}%</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

    <!-- システム情報 -->
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">システム情報</h3>
      </div>
      <div class="px-6 py-4">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
          <div>
            <dt class="text-sm font-medium text-gray-500">作成日時</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $material->created_at->format('Y-m-d H:i') }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">更新日時</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $material->updated_at->format('Y-m-d H:i') }}</dd>
          </div>
          @if($material->creator)
          <div>
            <dt class="text-sm font-medium text-gray-500">作成者</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $material->creator->name }}</dd>
          </div>
          @endif
          @if($material->updater)
          <div>
            <dt class="text-sm font-medium text-gray-500">更新者</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $material->updater->name }}</dd>
          </div>
          @endif
        </dl>
      </div>
    </div>
  </div>
</div>
@endsection