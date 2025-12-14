@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{ __('製品詳細') }}</div>

        <div class="card-body">
          <div class="mb-3">
            <strong>コード:</strong> {{ $product->code }}
          </div>
          <div class="mb-3">
            <strong>品名:</strong> {{ $product->name }}
          </div>
          <div class="mb-3">
            <strong>種別:</strong>
            @switch($product->type)
            @case('product') 製品 @break
            @case('part') 部品 @break
            @case('material') 原材料 @break
            @default {{ ucfirst($product->type) }}
            @endswitch
          </div>
          <div class="mb-3">
            <strong>標準原価:</strong> ¥{{ number_format($product->standard_cost, 2) }}
          </div>
          <div class="mb-3">
            <strong>リードタイム:</strong> {{ $product->lead_time_days }} 日
          </div>
          <div class="mb-3">
            <strong>最低在庫数:</strong> {{ $product->minimum_stock_level }}
          </div>
          <div class="mb-3">
            <strong>現在庫数:</strong> {{ $product->current_stock }}
          </div>

          <div class="mt-3">
            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">編集</a>
            <a href="{{ route('products.bom.index', $product) }}" class="btn btn-info">BOM(部品構成)管理</a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">一覧へ戻る</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection