@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">{{ __('製品管理') }}</div>

        <div class="card-body">
          <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">新規製品登録</a>

          <table class="table">
            <thead>
              <tr>
                <th>コード</th>
                <th>品名</th>
                <th>種別</th>
                <th>標準原価</th>
                <th>現在庫</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              @forelse($products as $product)
              <tr>
                <td>{{ $product->code }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ ucfirst($product->type) }}</td>
                <td>¥{{ number_format($product->standard_cost, 2) }}</td>
                <td>{{ $product->current_stock }}</td>
                <td>
                  <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">詳細</a>
                  <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">編集</a>
                  <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('本当に削除しますか?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">削除</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6">製品が見つかりません。</td>
              </tr>
              @endforelse
            </tbody>
          </table>

          {{ $products->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection