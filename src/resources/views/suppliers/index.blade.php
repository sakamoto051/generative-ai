@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">{{ __('Suppliers') }}</div>

        <div class="card-body">
          <a href="{{ route('suppliers.create') }}" class="btn btn-primary mb-3">Create New Supplier</a>

          <table class="table">
            <thead>
              <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Contact Person</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($suppliers as $supplier)
              <tr>
                <td>{{ $supplier->code }}</td>
                <td>{{ $supplier->name }}</td>
                <td>{{ $supplier->contact_person }}</td>
                <td>{{ $supplier->email }}</td>
                <td>{{ $supplier->phone }}</td>
                <td>
                  <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-sm btn-info">View</a>
                  <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning">Edit</a>
                  <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6">No suppliers found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>

          {{ $suppliers->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection