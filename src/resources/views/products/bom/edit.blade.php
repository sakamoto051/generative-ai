@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{ __('Edit BOM Item: ') }} {{ $bomItem->childProduct->name }}</div>

        <div class="card-body">
          <form method="POST" action="{{ route('products.bom.update', [$product, $bomItem]) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label class="form-label">Component</label>
              <input type="text" class="form-control" value="{{ $bomItem->childProduct->code }} - {{ $bomItem->childProduct->name }}" disabled>
            </div>

            <div class="mb-3">
              <label for="quantity" class="form-label">Quantity Required</label>
              <input type="number" step="0.0001" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $bomItem->quantity) }}" required>
              @error('quantity')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="yield_rate" class="form-label">Yield Rate (0.0001 - 1.0)</label>
              <input type="number" step="0.0001" max="1.0" class="form-control @error('yield_rate') is-invalid @enderror" id="yield_rate" name="yield_rate" value="{{ old('yield_rate', $bomItem->yield_rate) }}" required>
              <div class="form-text">Example: 1.0 means 100% yield (no waste). 0.95 means 5% waste.</div>
              @error('yield_rate')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-0">
              <button type="submit" class="btn btn-primary">
                {{ __('Update BOM Item') }}
              </button>
              <a href="{{ route('products.bom.index', $product) }}" class="btn btn-secondary">
                {{ __('Cancel') }}
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection