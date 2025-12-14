@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">{{ __('Purchase Orders') }}</div>

        <div class="card-body">
          <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary mb-3">Create New PO</a>

          <table class="table">
            <thead>
              <tr>
                <th>PO Number</th>
                <th>Supplier</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Delivery Due</th>
                <th>Total Amount</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($purchaseOrders as $po)
              <tr>
                <td>{{ $po->po_number }}</td>
                <td>{{ $po->supplier->name }}</td>
                <td>
                  @if($po->status === 'draft')
                  <span class="badge bg-secondary text-dark" style="background-color: #e2e8f0;">{{ ucfirst($po->status) }}</span>
                  @elseif($po->status === 'ordered')
                  <span class="badge bg-primary text-white" style="background-color: #3b82f6;">{{ ucfirst($po->status) }}</span>
                  @elseif($po->status === 'received')
                  <span class="badge bg-success text-white" style="background-color: #22c55e;">{{ ucfirst($po->status) }}</span>
                  @else
                  {{ ucfirst($po->status) }}
                  @endif
                </td>
                <td>{{ $po->order_date->format('Y-m-d') }}</td>
                <td>{{ $po->delivery_due_date ? $po->delivery_due_date->format('Y-m-d') : '-' }}</td>
                <td>{{ number_format($po->total_amount, 2) }}</td>
                <td>
                  <a href="{{ route('purchase-orders.show', $po) }}" class="btn btn-sm btn-info">View</a>
                  @if($po->status === 'draft')
                  <a href="{{ route('purchase-orders.edit', $po) }}" class="btn btn-sm btn-warning">Edit</a>
                  <form action="{{ route('purchase-orders.destroy', $po) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this PO?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                  </form>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7">No purchase orders found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>

          {{ $purchaseOrders->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection