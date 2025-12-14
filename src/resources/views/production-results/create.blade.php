@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{ __('生産実績報告') }}</div>

        <div class="card-body">
          <div class="mb-4">
            <h5>計画詳細</h5>
            <table class="table table-sm table-bordered">
              <tr>
                <th class="bg-light" style="width: 30%">計画番号</th>
                <td>{{ $item->productionPlan->plan_number }}</td>
              </tr>
              <tr>
                <th class="bg-light">製品</th>
                <td>{{ $item->product->name }} ({{ $item->product->code }})</td>
              </tr>
              <tr>
                <th class="bg-light">計画数量</th>
                <td>{{ $item->quantity }}</td>
              </tr>
              <tr>
                <th class="bg-light">これまでの実績</th>
                <td>{{ $item->results()->sum('quantity') }}</td>
              </tr>
            </table>
          </div>

          <hr>

          <form method="POST" action="{{ route('production-results.store') }}">
            @csrf
            <input type="hidden" name="production_plan_item_id" value="{{ $item->id }}">

            <div class="mb-3">
              <label for="result_date" class="form-label">実績日</label>
              <input type="date" class="form-control @error('result_date') is-invalid @enderror" id="result_date"
                name="result_date" value="{{ date('Y-m-d') }}" required>
              @error('result_date')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="quantity" class="form-label">製造数 (良品)</label>
              <input type="number" step="0.01" class="form-control @error('quantity') is-invalid @enderror"
                id="quantity" name="quantity" required>
              @error('quantity')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="defective_quantity" class="form-label">不良数</label>
              <input type="number" step="0.01" class="form-control @error('defective_quantity') is-invalid @enderror"
                id="defective_quantity" name="defective_quantity" value="0">
              @error('defective_quantity')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="remarks" class="form-label">備考</label>
              <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks"
                rows="2"></textarea>
              @error('remarks')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-primary">報告する</button>
            <a href="{{ route('production-plans.show', $item->production_plan_id) }}"
              class="btn btn-secondary">キャンセル</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection