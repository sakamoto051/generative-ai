@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">{{ __('Products') }}</div>

        <div class="card-body">
          <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Create New Product</a>

          <table class="table">
            <thead>
              <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Type</th>
                <th>Standard Cost</th>
                <th>Current Stock</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($products as $product)
              <tr>
                <td>{{ $product->code }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ ucfirst($product->type) }}</td>
                <td>{{ number_format($product->standard_cost, 2) }}</td>
                <td>{{ $product->current_stock }}</td>
                <td>
                  <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">View</a>
                  <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                  <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6">No products found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>

          {{ $products->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection