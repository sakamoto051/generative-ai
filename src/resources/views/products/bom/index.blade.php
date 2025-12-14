@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>{{ __('Bill of Materials (BOM) for: ') }} <strong>{{ $product->name }}</strong> ({{ $product->code }})</span>
          <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-secondary">Back to Product</a>
        </div>

        <div class="card-body">
          <div class="mb-3">
            <a href="{{ route('products.bom.create', $product) }}" class="btn btn-primary">Add BOM Item</a>
          </div>

          <table class="table">
            <thead>
              <tr>
                <th>Component Code</th>
                <th>Component Name</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Yield Rate</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($bomItems as $item)
              <tr>
                <td>{{ $item->childProduct->code }}</td>
                <td>{{ $item->childProduct->name }}</td>
                <td>{{ ucfirst($item->childProduct->type) }}</td>
                <td>{{ number_format($item->quantity, 4) }}</td>
                <td>{{ number_format($item->yield_rate, 4) }}</td>
                <td>
                  <a href="{{ route('products.bom.edit', [$product, $item]) }}" class="btn btn-sm btn-warning">Edit</a>
                  <form action="{{ route('products.bom.destroy', [$product, $item]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this item from BOM?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6">No items defined in BOM.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection