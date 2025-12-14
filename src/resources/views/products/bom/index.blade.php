@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>{{ __('部品構成表 (BOM): ') }} <strong>{{ $product->name }}</strong> ({{ $product->code }})</span>
          <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-secondary">製品詳細へ戻る</a>
        </div>

        <div class="card-body">
          <div class="mb-3">
            <a href="{{ route('products.bom.create', $product) }}" class="btn btn-primary">構成部品を追加</a>
          </div>

          <table class="table">
            <thead>
              <tr>
                <th>コード</th>
                <th>品名</th>
                <th>種別</th>
                <th>必要数量</th>
                <th>歩留まり率</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              @forelse($bomItems as $item)
              <tr>
                <td>{{ $item->childProduct->code }}</td>
                <td>{{ $item->childProduct->name }}</td>
                <td>
                  @switch($item->childProduct->type)
                  @case('product') 製品 @break
                  @case('part') 部品 @break
                  @case('material') 原材料 @break
                  @default {{ ucfirst($item->childProduct->type) }}
                  @endswitch
                </td>
                <td>{{ number_format($item->quantity, 4) }}</td>
                <td>{{ number_format($item->yield_rate, 4) }}</td>
                <td>
                  <a href="{{ route('products.bom.edit', [$product, $item]) }}" class="btn btn-sm btn-warning">編集</a>
                  <form action="{{ route('products.bom.destroy', [$product, $item]) }}" method="POST" class="d-inline" onsubmit="return confirm('この部品構成を削除しますか？');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">削除</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6">構成部品が登録されていません。</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection