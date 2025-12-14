@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row mb-4">
    <div class="col-md-12">
      <h1>Dashboard</h1>
      <p class="text-muted">Overview of production and inventory status.</p>
    </div>
  </div>

  <!-- Quick Stats Cards -->
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card bg-primary text-white h-100">
        <div class="card-body">
          <h5 class="card-title">Active Plans</h5>
          <p class="display-6">{{ number_format($activePlansCount) }}</p>
          <small>In Approval / Approved</small>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-warning text-dark h-100">
        <div class="card-body">
          <h5 class="card-title">Low Stock Items</h5>
          <p class="display-6">{{ number_format($lowStockCount) }}</p>
          <small>Below Minimum Level</small>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-success text-white h-100">
        <div class="card-body">
          <h5 class="card-title">Pending Receipt (PO)</h5>
          <p class="display-6">{{ number_format($purchaseOrdersCount) }}</p>
          <small>Orders Placed</small>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-info text-white h-100">
        <div class="card-body">
          <h5 class="card-title">Incoming Value</h5>
          <p class="display-6">${{ number_format($pendingReceiptAmount, 0) }}</p>
          <small>Pending PO Amount</small>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Recent Production Plans -->
    <div class="col-md-8">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Recent Production Plans</span>
          <a href="{{ route('production-plans.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Plan #</th>
                  <th>Status</th>
                  <th>Progress</th>
                  <th>Period</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentPlans as $plan)
                <tr>
                  <td>
                    <a href="{{ route('production-plans.show', $plan) }}" class="text-decoration-none fw-bold">
                      {{ $plan->plan_number }}
                    </a>
                  </td>
                  <td>
                    <span class="badge {{ $plan->status === 'approved' ? 'bg-success' : ($plan->status === 'draft' ? 'bg-secondary' : 'bg-warning') }}">
                      {{ ucfirst($plan->status) }}
                    </span>
                  </td>
                  <td>
                    <div class="progress" style="height: 6px;">
                      <div class="progress-bar" role="progressbar" style="width: {{ $plan->progress }}%" aria-valuenow="{{ $plan->progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">{{ number_format($plan->progress, 0) }}%</small>
                  </td>
                  <td>
                    <small>{{ \Carbon\Carbon::parse($plan->period_start)->format('M d') }} - {{ \Carbon\Carbon::parse($plan->period_end)->format('M d') }}</small>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="4" class="text-center text-muted">No recent plans found.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Low Stock Alerts -->
    <div class="col-md-4">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span class="text-danger"><i class="bi bi-exclamation-triangle"></i> Low Stock Alerts</span>
          <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">View Inventory</a>
        </div>
        <div class="card-body p-0">
          <ul class="list-group list-group-flush">
            @forelse($lowStockProducts as $product)
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-bold">{{ $product->name }}</div>
                <small class="text-muted">{{ $product->code }}</small>
              </div>
              <div class="text-end">
                <span class="badge bg-danger rounded-pill">{{ $product->current_stock }}</span>
                <div style="font-size: 0.75rem;">Min: {{ $product->minimum_stock_level }}</div>
              </div>
            </li>
            @empty
            <li class="list-group-item text-center text-muted py-3">
              Inventory levels are healthy.
            </li>
            @endforelse
          </ul>
        </div>
      </div>

      <div class="d-grid gap-2">
        <a href="{{ route('production-plans.create') }}" class="btn btn-outline-primary">
          Crete New Production Plan
        </a>
        <a href="{{ route('purchase-orders.create') }}" class="btn btn-outline-success">
          Create Purchase Order
        </a>
      </div>
    </div>
  </div>
</div>
@endsection