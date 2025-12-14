@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{ __('構成部品の追加: ') }} {{ $product->name }}</div>

        <div class="card-body">
          <form method="POST" action="{{ route('products.bom.store', $product) }}">
            @csrf

            <div class="mb-3">
              <label for="child_product_id" class="form-label">構成部品 (子品目)</label>
              <select class="form-select @error('child_product_id') is-invalid @enderror" id="child_product_id" name="child_product_id" required>
                <option value="">選択してください</option>
                @foreach($candidates as $candidate)
                <option value="{{ $candidate->id }}" {{ old('child_product_id') == $candidate->id ? 'selected' : '' }}>
                  {{ $candidate->code }} - {{ $candidate->name }} (
                  @switch($candidate->type)
                  @case('product') 製品 @break
                  @case('part') 部品 @break
                  @case('material') 原材料 @break
                  @default {{ ucfirst($candidate->type) }}
                  @endswitch
                  )
                </option>
                @endforeach
              </select>
              @error('child_product_id')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="quantity" class="form-label">必要数量</label>
              <input type="number" step="0.0001" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity') }}" required>
              @error('quantity')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="yield_rate" class="form-label">歩留まり率 (0.0001 - 1.0)</label>
              <input type="number" step="0.0001" max="1.0" class="form-control @error('yield_rate') is-invalid @enderror" id="yield_rate" name="yield_rate" value="{{ old('yield_rate', 1.0) }}" required>
              <div class="form-text">例: 1.0 は 100% (ロスなし), 0.95 は 5% のロスを意味します。</div>
              @error('yield_rate')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-0">
              <button type="submit" class="btn btn-primary">
                {{ __('追加') }}
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