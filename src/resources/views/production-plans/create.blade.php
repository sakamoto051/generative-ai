@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">{{ __('新規生産計画作成') }}</div>

        <div class="card-body">
          <form method="POST" action="{{ route('production-plans.store') }}">
            @csrf

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="period_start" class="form-label">開始日</label>
                <input type="date" class="form-control @error('period_start') is-invalid @enderror" id="period_start" name="period_start" value="{{ old('period_start', date('Y-m-01')) }}" required>
                @error('period_start')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="period_end" class="form-label">終了日</label>
                <input type="date" class="form-control @error('period_end') is-invalid @enderror" id="period_end" name="period_end" value="{{ old('period_end', date('Y-m-t')) }}" required>
                @error('period_end')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">説明 (オプション)</label>
              <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2">{{ old('description') }}</textarea>
              @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <hr>
            <h5>計画品目</h5>

            <div id="plan-items">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>製品</th>
                    <th style="width: 150px;">数量</th>
                    <th>日付 (オプション)</th>
                    <th style="width: 50px;"></th>
                  </tr>
                </thead>
                <tbody id="items-body">
                  {{-- Initial Row --}}
                  <tr>
                    <td>
                      <select name="items[0][product_id]" class="form-select" required>
                        <option value="">製品を選択</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->code }})</option>
                        @endforeach
                      </select>
                    </td>
                    <td>
                      <input type="number" name="items[0][quantity]" class="form-control" min="1" required>
                    </td>
                    <td>
                      <div class="input-group">
                        <input type="date" name="items[0][planned_start_date]" class="form-control">
                        <input type="date" name="items[0][planned_end_date]" class="form-control">
                      </div>
                    </td>
                    <td>
                      <button type="button" class="btn btn-danger btn-sm remove-item" disabled>x</button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <button type="button" class="btn btn-sm btn-success" id="add-item">品目を追加</button>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-primary">計画を作成</button>
              <a href="{{ route('production-plans.index') }}" class="btn btn-secondary">キャンセル</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    const itemsBody = document.getElementById('items-body');
    const addItemBtn = document.getElementById('add-item');

    addItemBtn.addEventListener('click', function() {
      const row = document.createElement('tr');
      row.innerHTML = `
                <td>
                    <select name="items[${itemIndex}][product_id]" class="form-select" required>
                        <option value="">製品を選択</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->code }})</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][quantity]" class="form-control" min="1" required>
                </td>
                <td>
                    <div class="input-group">
                        <input type="date" name="items[${itemIndex}][planned_start_date]" class="form-control">
                        <input type="date" name="items[${itemIndex}][planned_end_date]" class="form-control">
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-item">x</button>
                </td>
            `;
      itemsBody.appendChild(row);
      itemIndex++;
      updateRemoveButtons();
    });

    itemsBody.addEventListener('click', function(e) {
      if (e.target.classList.contains('remove-item')) {
        e.target.closest('tr').remove();
        updateRemoveButtons();
      }
    });

    function updateRemoveButtons() {
      const buttons = itemsBody.querySelectorAll('.remove-item');
      buttons.forEach(btn => btn.disabled = buttons.length === 1);
    }
  });
</script>
@endsection