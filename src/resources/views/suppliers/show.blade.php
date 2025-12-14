@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{ __('Supplier Details') }}</div>

        <div class="card-body">
          <div class="mb-3">
            <strong>Code:</strong> {{ $supplier->code }}
          </div>
          <div class="mb-3">
            <strong>Name:</strong> {{ $supplier->name }}
          </div>
          <div class="mb-3">
            <strong>Contact Person:</strong> {{ $supplier->contact_person }}
          </div>
          <div class="mb-3">
            <strong>Email:</strong> {{ $supplier->email }}
          </div>
          <div class="mb-3">
            <strong>Phone:</strong> {{ $supplier->phone }}
          </div>
          <div class="mb-3">
            <strong>Address:</strong>
            <p class="text-muted">{{ $supplier->address }}</p>
          </div>

          <div class="mt-3">
            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Back to List</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection