@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>
            {{ __('Purchase Order Details') }} - <strong>{{ $purchaseOrder->po_number }}</strong>
            @if($purchaseOrder->status === 'draft')
            <span class="badge bg-secondary ms-2">Draft</span>
            @elseif($purchaseOrder->status === 'ordered')
            <span class="badge bg-primary ms-2">Ordered</span>
            @endif
          </span>
          <div>
            @if($purchaseOrder->status === 'draft')
            <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="btn btn-sm btn-warning">Edit Header</a>
            @endif
            <a href="{{ route('purchase-orders.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
          </div>
        </div>

        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-4">
              <strong>Supplier:</strong> {{ $purchaseOrder->supplier->name }}
            </div>
            <div class="col-md-4">
              <strong>Order Date:</strong> {{ $purchaseOrder->order_date->format('Y-m-d') }}
            </div>
            <div class="col-md-4">
              <strong>Delivery Due:</strong> {{ $purchaseOrder->delivery_due_date ? $purchaseOrder->delivery_due_date->format('Y-m-d') : '-' }}
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <h3>Total Amount: {{ number_format($purchaseOrder->total_amount, 2) }}</h3>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>{{ __('Order Items') }}</span>
          @if($purchaseOrder->status === 'draft')
          <a href="{{ route('purchase-orders.items.create', $purchaseOrder) }}" class="btn btn-sm btn-primary">Add Item</a>
          @endif
        </div>

        <div class="card-body">
          <table class="table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
                @if($purchaseOrder->status === 'draft')
                <th>Actions</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @forelse($purchaseOrder->items as $item)
              <tr>
                <td>{{ $item->product->name }} ({{ $item->product->code }})</td>
                <td>{{ number_format($item->quantity, 2) }}</td>
                <td>{{ number_format($item->unit_price, 2) }}</td>
                <td>{{ number_format($item->subtotal, 2) }}</td>
                @if($purchaseOrder->status === 'draft')
                <td>
                  <a href="{{ route('purchase-orders.items.edit', [$purchaseOrder, $item]) }}" class="btn btn-sm btn-warning">Edit</a>
                  <form action="{{ route('purchase-orders.items.destroy', [$purchaseOrder, $item]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this item?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                  </form>
                </td>
                @endif
              </tr>
              @empty
              <tr>
                <td colspan="5">No items in this order.</td>
              </tr>
              @endforelse
            </tbody>
          </table>

          @if($purchaseOrder->status === 'draft' && $purchaseOrder->items->count() > 0)
          <div class="mt-4 border-top pt-3">
            <form action="{{ route('purchase-orders.submit', $purchaseOrder) }}" method="POST" onsubmit="return confirm('Submit this order? Status will change to Ordered and cannot be edited.');">
              @csrf
              <button type="submit" class="btn btn-success btn-lg w-100">Submit Order</button>
            </form>
          </div>
          @endif

          @if($purchaseOrder->status === 'ordered')
          <div class="mt-4 border-top pt-3">
            <form action="{{ route('purchase-orders.receive', $purchaseOrder) }}" method="POST" onsubmit="return confirm('Confirm receipt of goods? Current stock will be updated.');">
              @csrf
              <button type="submit" class="btn btn-success btn-lg w-100">Receive Order</button>
            </form>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection