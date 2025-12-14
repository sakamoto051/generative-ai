@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>{{ __('Production Plan Details') }}</span>
          <div>
            <span
              class="badge {{ $productionPlan->status === 'approved' ? 'bg-success' : ($productionPlan->status === 'draft' ? 'bg-secondary' : 'bg-warning') }} text-dark border">
              {{ ucfirst($productionPlan->status) }}
            </span>
          </div>
        </div>

        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-3 fw-bold">Plan Number</div>
            <div class="col-md-9">{{ $productionPlan->plan_number }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3 fw-bold">Period</div>
            <div class="col-md-9">{{ $productionPlan->period_start }} to {{ $productionPlan->period_end }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3 fw-bold">Creator</div>
            <div class="col-md-9">{{ $productionPlan->creator->name ?? 'N/A' }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3 fw-bold">Created At</div>
            <div class="col-md-9">{{ $productionPlan->created_at->format('Y-m-d H:i') }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3 fw-bold">Description</div>
            <div class="col-md-9">{{ $productionPlan->description ?? 'No description provided' }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3 fw-bold">Estimated Cost (Standard)</div>
            <div class="col-md-9">{{ number_format($estimatedCost, 2) }}</div>
          </div>

          <hr class="my-4">
          <h5 class="mb-3">Plan Items (Progress)</h5>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Product</th>
                <th>Planned Qty</th>
                <th>Actual Qty</th>
                <th>Defective</th>
                <th>Progress</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($productionPlan->items as $item)
              <tr>
                <td>{{ $item->product->name }} ({{ $item->product->code }})</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->results->sum('quantity') }}</td>
                <td>{{ $item->results->sum('defective_quantity') }}</td>
                <td>
                  @php
                  $actual = $item->results->sum('quantity');
                  $progress = $item->quantity > 0 ? ($actual / $item->quantity) * 100 : 0;
                  @endphp
                  <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;"
                      aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                      {{ number_format($progress, 0) }}%
                    </div>
                  </div>
                </td>
                <td>
                  @if($productionPlan->status === 'approved')
                  <a href="{{ route('production-results.create', $item) }}"
                    class="btn btn-sm btn-outline-primary">Report</a>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>

          <hr class="my-4">
          <h5 class="mb-3">Material Requirements (Simulation)</h5>
          @if($materialRequirements->isEmpty())
          <div class="alert alert-info">No material requirements calculated.</div>
          @else
          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th>Material Code</th>
                <th>Name</th>
                <th>Type</th>
                <th>Required Qty</th>
                <th>Unit Cost</th>
                <th>Total Cost</th>
              </tr>
            </thead>
            <tbody>
              @foreach($materialRequirements as $req)
              <tr>
                <td>{{ $req['code'] }}</td>
                <td>{{ $req['name'] }}</td>
                <td>{{ ucfirst($req['type']) }}</td>
                <td>{{ number_format($req['total_quantity'], 2) }}</td>
                <td>{{ number_format($req['unit_cost'], 2) }}</td>
                <td>{{ number_format($req['total_quantity'] * $req['unit_cost'], 2) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @endif

          <div class="row mb-3">
            <div class="col-md-4">
              <div class="card bg-light">
                <div class="card-body py-2">
                  <small class="text-muted">Total Planned Cost</small>
                  <h4 class="mb-0">{{ number_format($costData['total_planned_cost'], 2) }}</h4>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card bg-light">
                <div class="card-body py-2">
                  <small class="text-muted">Total Actual Cost</small>
                  <h4 class="mb-0">{{ number_format($costData['total_actual_cost'], 2) }}</h4>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div
                class="card {{ $costData['total_variance'] >= 0 ? 'bg-success text-white' : 'bg-danger text-white' }}">
                <div class="card-body py-2">
                  <small class="{{ $costData['total_variance'] >= 0 ? 'text-white-50' : 'text-white-50' }}">Variance (
                    Remaining Budget )</small>
                  <h4 class="mb-0">{{ number_format($costData['total_variance'], 2) }}</h4>
                </div>
              </div>
            </div>
          </div>

          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th>Product</th>
                <th>Standard Cost</th>
                <th>Planned Cost (Qty)</th>
                <th>Actual Cost (Qty)</th>
                <th>Variance</th>
              </tr>
            </thead>
            <tbody>
              @foreach($costData['items'] as $item)
              <tr>
                <td>{{ $item['product_name'] }}</td>
                <td>{{ number_format($item['standard_unit_cost'], 2) }}</td>
                <td>{{ number_format($item['planned_cost'], 2) }} <small
                    class="text-muted">({{ $item['planned_qty'] }})</small></td>
                <td>{{ number_format($item['actual_cost'], 2) }} <small
                    class="text-muted">({{ $item['actual_qty'] }})</small></td>
                <td class="{{ $item['variance'] < 0 ? 'text-danger fw-bold' : 'text-success' }}">
                  {{ number_format($item['variance'], 2) }}
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="card-footer text-end d-flex justify-content-end gap-2">
          <a href="{{ route('production-plans.index') }}" class="btn btn-secondary me-2">Back to List</a>
          @if($productionPlan->status === 'draft')
          <a href="{{ route('production-plans.edit', $productionPlan) }}" class="btn btn-warning me-2">Edit</a>
          <form action="{{ route('production-plans.submit', $productionPlan) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-primary">Submit for Approval</button>
          </form>
          @endif

          @if($productionPlan->status === 'pending_approval')
          <form action="{{ route('production-plans.approve', $productionPlan) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-success">Approve</button>
          </form>
          <form action="{{ route('production-plans.reject', $productionPlan) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-danger">Reject</button>
          </form>
          @endif

          @if($productionPlan->status === 'approved')
          <form action="{{ route('production-plans.generate-po', $productionPlan) }}" method="POST"
            style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-info text-white">Generate Purchase Orders</button>
          </form>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection