@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{ __('Product Details') }}</div>

        <div class="card-body">
          <div class="mb-3">
            <strong>Code:</strong> {{ $product->code }}
          </div>
          <div class="mb-3">
            <strong>Name:</strong> {{ $product->name }}
          </div>
          <div class="mb-3">
            <strong>Type:</strong> {{ ucfirst($product->type) }}
          </div>
          <div class="mb-3">
            <strong>Standard Cost:</strong> {{ number_format($product->standard_cost, 2) }}
          </div>
          <div class="mb-3">
            <strong>Lead Time:</strong> {{ $product->lead_time_days }} days
          </div>
          <div class="mb-3">
            <strong>Minimum Stock Level:</strong> {{ $product->minimum_stock_level }}
          </div>
          <div class="mb-3">
            <strong>Current Stock:</strong> {{ $product->current_stock }}
          </div>

          <div class="mt-3">
            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('products.bom.index', $product) }}" class="btn btn-info">Manage BOM</a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection