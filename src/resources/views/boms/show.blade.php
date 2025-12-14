@extends('layouts.app')

@section('title', 'BOM詳細')

@section('content')
<div class="max-w-4xl mx-auto">
  <!-- ヘッダー -->
  <div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
      <h2 class="text-2xl font-bold text-gray-900">BOM詳細</h2>
      <p class="mt-1 text-sm text-gray-600">部品表の詳細情報</p>
    </div>
    <div class="mt-4 sm:mt-0 flex space-x-3">
      <a href="{{ route('boms.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        一覧に戻る
      </a>
      @can('boms.edit')
      <a href="{{ route('boms.edit', $bom) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
        編集
      </a>
      @endcan
    </div>
  </div>

  <div class="space-y-6">
    <!-- 製品情報 -->
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">製品情報</h3>
      </div>
      <div class="px-6 py-4">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
          <div>
            <dt class="text-sm font-medium text-gray-500">製品コード</dt>
            <dd class="mt-1 text-sm text-gray-900 font-semibold">
              <a href="{{ route('products.show', $bom->product) }}" class="text-indigo-600 hover:text-indigo-900">
                {{ $bom->product->code }}
              </a>
            </dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">製品名</dt>
            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $bom->product->name }}</dd>
          </div>
        </dl>
      </div>
    </div>

    <!-- 材料情報 -->
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">材料情報</h3>
      </div>
      <div class="px-6 py-4">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
          <div>
            <dt class="text-sm font-medium text-gray-500">材料コード</dt>
            <dd class="mt-1 text-sm text-gray-900 font-semibold">
              <a href="{{ route('materials.show', $bom->material) }}" class="text-indigo-600 hover:text-indigo-900">
                {{ $bom->material->code }}
              </a>
            </dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">材料名</dt>
            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $bom->material->name }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">カテゴリ</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $bom->material->category }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">仕入先</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $bom->material->supplier ?? '-' }}</dd>
          </div>
        </dl>
      </div>
    </div>

    <!-- 使用情報 -->
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">使用情報</h3>
      </div>
      <div class="px-6 py-4">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
          <div>
            <dt class="text-sm font-medium text-gray-500">使用数量</dt>
            <dd class="mt-1 text-lg font-bold text-gray-900">{{ number_format($bom->quantity, 2) }} {{ $bom->unit }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">歩留まり率</dt>
            <dd class="mt-1 text-lg font-bold text-gray-900">{{ $bom->yield_rate }}%</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">順序</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $bom->sequence ?? '-' }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">実使用数量（歩留まり考慮）</dt>
            <dd class="mt-1 text-lg font-bold text-indigo-600">
              {{ number_format($bom->quantity * (100 / $bom->yield_rate), 2) }} {{ $bom->unit }}
            </dd>
          </div>
          @if($bom->notes)
          <div class="md:col-span-2">
            <dt class="text-sm font-medium text-gray-500">備考</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $bom->notes }}</dd>
          </div>
          @endif
        </dl>
      </div>
    </div>

    <!-- 原価情報 -->
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">原価情報</h3>
      </div>
      <div class="px-6 py-4">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
          <div>
            <dt class="text-sm font-medium text-gray-500">材料単価</dt>
            <dd class="mt-1 text-sm text-gray-900">¥{{ number_format($bom->material->unit_price) }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">材料費（1個あたり）</dt>
            <dd class="mt-1 text-lg font-bold text-gray-900">
              ¥{{ number_format($bom->quantity * $bom->material->unit_price * (100 / $bom->yield_rate)) }}
            </dd>
          </div>
        </dl>
      </div>
    </div>

    <!-- システム情報 -->
    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">システム情報</h3>
      </div>
      <div class="px-6 py-4">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
          <div>
            <dt class="text-sm font-medium text-gray-500">作成日時</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $bom->created_at->format('Y-m-d H:i') }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">更新日時</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $bom->updated_at->format('Y-m-d H:i') }}</dd>
          </div>
          @if($bom->creator)
          <div>
            <dt class="text-sm font-medium text-gray-500">作成者</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $bom->creator->name }}</dd>
          </div>
          @endif
          @if($bom->updater)
          <div>
            <dt class="text-sm font-medium text-gray-500">更新者</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $bom->updater->name }}</dd>
          </div>
          @endif
        </dl>
      </div>
    </div>
  </div>
</div>
@endsection