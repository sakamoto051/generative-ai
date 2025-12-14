@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>{{ __('発注詳細') }}</span>
          <span class="badge {{ $purchaseOrder->status === 'ordered' || $purchaseOrder->status === 'received' ? 'bg-success' : ($purchaseOrder->status === 'draft' ? 'bg-secondary' : 'bg-danger') }}">
            @switch($purchaseOrder->status)
            @case('draft') ドラフト @break
            @case('ordered') 発注済 @break
            @case('received') 受入済 @break
            @case('cancelled') キャンセル @break
            @default {{ $purchaseOrder->status }}
            @endswitch
          </span>
        </div>

        <div class="card-body">
          <div class="row mb-4">
            <div class="col-sm-6">
              <h6 class="mb-3">発注情報:</h6>
              <div><strong>発注番号:</strong> {{ $purchaseOrder->po_number }}</div>
              <div><strong>発注日:</strong> {{ $purchaseOrder->order_date->format('Y/m/d') }}</div>
              <div><strong>納期:</strong> {{ $purchaseOrder->delivery_due_date ? $purchaseOrder->delivery_due_date->format('Y/m/d') : '未定' }}</div>
            </div>
            <div class="col-sm-6">
              <h6 class="mb-3">取引先:</h6>
              <div><strong>会社名:</strong> {{ $purchaseOrder->supplier->name }}</div>
              <div><strong>コード:</strong> {{ $purchaseOrder->supplier->code }}</div>
              <div><strong>担当者:</strong> {{ $purchaseOrder->supplier->contact_person }}</div>
              <div><strong>Email:</strong> {{ $purchaseOrder->supplier->email }}</div>
            </div>
          </div>

          <hr>

          <h5 class="mb-3">発注明細</h5>
          @if($purchaseOrder->status === 'draft')
          <a href="{{ route('purchase-orders.items.create', $purchaseOrder) }}" class="btn btn-primary btn-sm mb-3">明細追加</a>
          @endif

          <table class="table table-striped">
            <thead>
              <tr>
                <th>製品</th>
                <th>数量</th>
                <th>単価</th>
                <th>小計</th>
                @if($purchaseOrder->status === 'draft')
                <th>操作</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($purchaseOrder->items as $item)
              <tr>
                <td>{{ $item->product->name }} ({{ $item->product->code }})</td>
                <td>{{ $item->quantity }}</td>
                <td>¥{{ number_format($item->unit_price, 2) }}</td>
                <td>¥{{ number_format($item->subtotal, 2) }}</td>
                @if($purchaseOrder->status === 'draft')
                <td>
                  <a href="{{ route('purchase-orders.items.edit', [$purchaseOrder, $item]) }}" class="btn btn-sm btn-warning">編集</a>
                  <form action="{{ route('purchase-orders.items.destroy', [$purchaseOrder, $item]) }}" method="POST" class="d-inline" onsubmit="return confirm('削除しますか?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">削除</button>
                  </form>
                </td>
                @endif
              </tr>
              @endforeach
              <tr>
                <td colspan="3" class="text-end"><strong>合計金額:</strong></td>
                <td colspan="2"><strong>¥{{ number_format($purchaseOrder->total_amount, 2) }}</strong></td>
              </tr>
            </tbody>
          </table>

          <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">一覧へ戻る</a>

            @if($purchaseOrder->status === 'draft')
            <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="btn btn-warning">基本情報編集</a>
            @endif
          </div>

          @if($purchaseOrder->status === 'draft' && $purchaseOrder->items->count() > 0)
          <div class="mt-4 border-top pt-3">
            <form action="{{ route('purchase-orders.submit', $purchaseOrder) }}" method="POST" onsubmit="return confirm('発注を確定しますか？ステータスが「発注済」になり、編集できなくなります。');">
              @csrf
              <button type="submit" class="btn btn-success btn-lg w-100">発注確定 (Submit)</button>
            </form>
          </div>
          @endif

          @if($purchaseOrder->status === 'ordered')
          <div class="mt-4 border-top pt-3">
            <form action="{{ route('purchase-orders.receive', $purchaseOrder) }}" method="POST" onsubmit="return confirm('入荷検収を行いますか？在庫数量が更新されます。');">
              @csrf
              <button type="submit" class="btn btn-success btn-lg w-100">入荷受入 (Receive)</button>
            </form>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection