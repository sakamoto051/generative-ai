@extends('layouts.app')

@section('title', '製品詳細')

@section('content')
<div class="max-w-4xl mx-auto">
  <!-- ヘッダー -->
  <div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
      <h2 class="text-2xl font-bold text-gray-900">製品詳細</h2>
      <p class="mt-1 text-sm text-gray-600">製品コード: {{ $product->code }}</p>
    </div>
    <div class="mt-4 sm:mt-0 flex space-x-3">
      <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        一覧に戻る
      </a>
      @can('products.edit')
      <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
            <dt class="text-sm font-medium text-gray-500">製品コード</dt>
            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $product->code }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">製品名</dt>
            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $product->name }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">カテゴリ</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $product->category ?? '-' }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">単位</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $product->unit }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">ステータス</dt>
            <dd class="mt-1">
              @if($product->is_active)
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">有効</span>
              @else
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">無効</span>
              @endif
            </dd>
          </div>
          @if($product->description)
          <div class="md:col-span-2">
            <dt class="text-sm font-medium text-gray-500">説明</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $product->description }}</dd>
          </div>
          @endif
        </dl>
      </div>
    </div>

    <!-- 原価・価格情報 -->
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">原価・価格情報</h3>
      </div>
      <div class="px-6 py-4">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
          <div>
            <dt class="text-sm font-medium text-gray-500">標準原価</dt>
            <dd class="mt-1 text-lg font-bold text-gray-900">¥{{ number_format($product->standard_cost) }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">販売価格</dt>
            <dd class="mt-1 text-lg font-bold text-gray-900">
              @if($product->selling_price)
              ¥{{ number_format($product->selling_price) }}
              @else
              <span class="text-gray-400">未設定</span>
              @endif
            </dd>
          </div>
          @if($product->selling_price)
          <div class="md:col-span-2">
            <dt class="text-sm font-medium text-gray-500">利益額</dt>
            <dd class="mt-1 text-lg font-bold text-indigo-600">
              ¥{{ number_format($product->selling_price - $product->standard_cost) }}
              <span class="text-sm text-gray-500 font-normal">
                ({{ number_format((($product->selling_price - $product->standard_cost) / $product->selling_price) * 100, 1) }}%)
              </span>
            </dd>
          </div>
          @endif
        </dl>
      </div>
    </div>

    <!-- 在庫・生産情報 -->
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">在庫・生産情報</h3>
      </div>
      <div class="px-6 py-4">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
          <div>
            <dt class="text-sm font-medium text-gray-500">リードタイム</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $product->lead_time_days }} 日</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">安全在庫数</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ number_format($product->safety_stock) }} {{ $product->unit }}</dd>
          </div>
        </dl>
      </div>
    </div>

    <!-- BOM情報 -->
    @if($product->boms->count() > 0)
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">BOM（部品表）</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">材料コード</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">材料名</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">使用数量</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">歩留まり率</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($product->boms as $bom)
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $bom->material->code }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bom->material->name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ $bom->quantity }} {{ $bom->material->unit }}</td>
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
            <dd class="mt-1 text-sm text-gray-900">{{ $product->created_at->format('Y-m-d H:i') }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">更新日時</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $product->updated_at->format('Y-m-d H:i') }}</dd>
          </div>
          @if($product->creator)
          <div>
            <dt class="text-sm font-medium text-gray-500">作成者</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $product->creator->name }}</dd>
          </div>
          @endif
          @if($product->updater)
          <div>
            <dt class="text-sm font-medium text-gray-500">更新者</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $product->updater->name }}</dd>
          </div>
          @endif
        </dl>
      </div>
    </div>
  </div>
</div>
@endsection