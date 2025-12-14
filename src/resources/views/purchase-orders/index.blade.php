@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">{{ __('発注一覧') }}</div>

        <div class="card-body">
          <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary mb-3">新規発注作成</a>

          <table class="table">
            <thead>
              <tr>
                <th>発注番号</th>
                <th>取引先</th>
                <th>ステータス</th>
                <th>発注日</th>
                <th>納期</th>
                <th>合計金額</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              @forelse($purchaseOrders as $po)
              <tr>
                <td>{{ $po->po_number }}</td>
                <td>{{ $po->supplier->name }}</td>
                <td>
                  @switch($po->status)
                  @case('draft') <span class="badge bg-secondary">ドラフト</span> @break
                  @case('ordered') <span class="badge bg-primary">発注済</span> @break
                  @case('received') <span class="badge bg-success">受入済</span> @break
                  @case('cancelled') <span class="badge bg-danger">キャンセル</span> @break
                  @default {{ $po->status }}
                  @endswitch
                </td>
                <td>{{ $po->order_date->format('Y/m/d') }}</td>
                <td>{{ $po->delivery_due_date ? $po->delivery_due_date->format('Y/m/d') : '-' }}</td>
                <td>¥{{ number_format($po->total_amount, 2) }}</td>
                <td>
                  <a href="{{ route('purchase-orders.show', $po) }}" class="btn btn-sm btn-info">詳細</a>
                  @if($po->status === 'draft')
                  <a href="{{ route('purchase-orders.edit', $po) }}" class="btn btn-sm btn-warning">編集</a>
                  <form action="{{ route('purchase-orders.destroy', $po) }}" method="POST" class="d-inline" onsubmit="return confirm('本当に削除しますか?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">削除</button>
                  </form>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7">発注データが見つかりません。</td>
              </tr>
              @endforelse
            </tbody>
          </table>

          {{ $purchaseOrders->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection