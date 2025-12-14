@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{ __('構成部品の編集: ') }} {{ $bomItem->childProduct->name }}</div>

        <div class="card-body">
          <form method="POST" action="{{ route('products.bom.update', [$product, $bomItem]) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label class="form-label">構成部品 (子品目)</label>
              <input type="text" class="form-control" value="{{ $bomItem->childProduct->code }} - {{ $bomItem->childProduct->name }}" disabled>
            </div>

            <div class="mb-3">
              <label for="quantity" class="form-label">必要数量</label>
              <input type="number" step="0.0001" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $bomItem->quantity) }}" required>
              @error('quantity')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="yield_rate" class="form-label">歩留まり率 (0.0001 - 1.0)</label>
              <input type="number" step="0.0001" max="1.0" class="form-control @error('yield_rate') is-invalid @enderror" id="yield_rate" name="yield_rate" value="{{ old('yield_rate', $bomItem->yield_rate) }}" required>
              <div class="form-text">例: 1.0 は 100% (ロスなし), 0.95 は 5% のロスを意味します。</div>
              @error('yield_rate')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-0">
              <button type="submit" class="btn btn-primary">
                {{ __('更新') }}
              </button>
              <a href="{{ route('products.bom.index', $product) }}" class="btn btn-secondary">
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