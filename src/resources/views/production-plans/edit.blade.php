@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{ __('Edit Production Plan') }}</div>

        <div class="card-body">
          <form method="POST" action="{{ route('production-plans.update', $productionPlan) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label for="period_start" class="form-label">Period Start</label>
              <input type="date" class="form-control @error('period_start') is-invalid @enderror" id="period_start"
                name="period_start" value="{{ old('period_start', $productionPlan->period_start) }}" required>
              @error('period_start')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="period_end" class="form-label">Period End</label>
              <input type="date" class="form-control @error('period_end') is-invalid @enderror" id="period_end"
                name="period_end" value="{{ old('period_end', $productionPlan->period_end) }}" required>
              @error('period_end')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">Description (Optional)</label>
              <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                name="description" rows="3">{{ old('description', $productionPlan->description) }}</textarea>
              @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <hr class="my-4">

            <h5 class="mb-3">Plan Items</h5>
            <div class="mb-3">
              <table class="table" id="items-table">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Planned Start</th>
                    <th>Planned End</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Rows will be added here -->
                </tbody>
              </table>
              <button type="button" class="btn btn-sm btn-success" id="add-item-btn">Add Product</button>
            </div>

            <script>
              document.addEventListener('DOMContentLoaded', function() {
                let itemIndex = 0;
                const products = @json($products);
                const existingItems = @json($productionPlan->items);

                function addRow(data = null) {
                  const tbody = document.querySelector('#items-table tbody');
                  const tr = document.createElement('tr');

                  let options = '<option value="">Select Product</option>';
                  products.forEach(product => {
                    const selected = (data && data.product_id == product.id) ? 'selected' : '';
                    options += `<option value="${product.id}" ${selected}>${product.code} - ${product.name}</option>`;
                  });

                  const quantity = data ? data.quantity : '';
                  const startDate = data ? (data.planned_start_date ? data.planned_start_date.split('T')[0] : '') : '';
                  const endDate = data ? (data.planned_end_date ? data.planned_end_date.split('T')[0] : '') : '';

                  tr.innerHTML = `
                                <td>
                                    <select name="items[${itemIndex}][product_id]" class="form-control" required>
                                        ${options}
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="items[${itemIndex}][quantity]" class="form-control" step="0.01" min="0.01" value="${quantity}" required>
                                </td>
                                 <td>
                                    <input type="date" name="items[${itemIndex}][planned_start_date]" class="form-control" value="${startDate}">
                                </td>
                                 <td>
                                    <input type="date" name="items[${itemIndex}][planned_end_date]" class="form-control" value="${endDate}">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                                </td>
                            `;

                  tbody.appendChild(tr);
                  itemIndex++;
                }

                document.getElementById('add-item-btn').addEventListener('click', () => addRow());

                document.querySelector('#items-table').addEventListener('click', function(e) {
                  if (e.target.classList.contains('remove-row')) {
                    e.target.closest('tr').remove();
                  }
                });

                if (existingItems && existingItems.length > 0) {
                  existingItems.forEach(item => addRow(item));
                } else {
                  addRow();
                }
              });
            </script>

            <button type="submit" class="btn btn-primary">Update Plan</button>
            <a href="{{ route('production-plans.show', $productionPlan) }}" class="btn btn-secondary">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection