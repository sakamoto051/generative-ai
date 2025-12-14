@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{ __('明細編集: ') }} {{ $item->product->name }}</div>

        <div class="card-body">
          <form method="POST" action="{{ route('purchase-orders.items.update', [$purchaseOrder, $item]) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label class="form-label">製品</label>
              <input type="text" class="form-control" value="{{ $item->product->name }} ({{ $item->product->code }})" disabled>
            </div>

            <div class="mb-3">
              <label for="quantity" class="form-label">数量</label>
              <input type="number" step="0.0001" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $item->quantity) }}" required>
              @error('quantity')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="unit_price" class="form-label">単価</label>
              <input type="number" step="0.01" class="form-control @error('unit_price') is-invalid @enderror" id="unit_price" name="unit_price" value="{{ old('unit_price', $item->unit_price) }}" required>
              @error('unit_price')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-0">
              <button type="submit" class="btn btn-primary">
                {{ __('更新') }}
              </button>
              <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="btn btn-secondary">
                {{ __('キャンセル') }}
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection