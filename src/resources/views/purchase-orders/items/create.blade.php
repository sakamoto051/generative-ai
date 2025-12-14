@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{ __('Add Item to PO: ') }} {{ $purchaseOrder->po_number }}</div>

        <div class="card-body">
          <form method="POST" action="{{ route('purchase-orders.items.store', $purchaseOrder) }}">
            @csrf

            <div class="mb-3">
              <label for="product_id" class="form-label">Product (Material/Part)</label>
              <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                <option value="">Select Product</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" data-cost="{{ $product->standard_cost }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                  {{ $product->name }} ({{ $product->code }}) - Cost: {{ $product->standard_cost }}
                </option>
                @endforeach
              </select>
              @error('product_id')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="quantity" class="form-label">Quantity</label>
              <input type="number" step="0.0001" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity') }}" required>
              @error('quantity')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="unit_price" class="form-label">Unit Price</label>
              <input type="number" step="0.01" class="form-control @error('unit_price') is-invalid @enderror" id="unit_price" name="unit_price" value="{{ old('unit_price') }}" required>
              <div class="form-text">Defaults to standard cost but can be overridden.</div>
              @error('unit_price')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-0">
              <button type="submit" class="btn btn-primary">
                {{ __('Add Item') }}
              </button>
              <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="btn btn-secondary">
                {{ __('Cancel') }}
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const unitPriceInput = document.getElementById('unit_price');

    productSelect.addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      const cost = selectedOption.getAttribute('data-cost');
      if (cost) {
        unitPriceInput.value = cost;
      }
    });
  });
</script>
@endsection