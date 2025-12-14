@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">{{ __('Production Plans') }}</div>

        <div class="card-body">
          <a href="{{ route('production-plans.create') }}" class="btn btn-primary mb-3">Create New Plan</a>

          <table class="table">
            <thead>
              <tr>
                <th>Plan Number</th>
                <th>Period</th>
                <th>Status</th>
                <th>Creator</th>
                <th>Created At</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($plans as $plan)
              <tr>
                <td>{{ $plan->plan_number }}</td>
                <td>{{ $plan->period_start }} - {{ $plan->period_end }}</td>
                <td>{{ $plan->status }}</td>
                <td>{{ $plan->creator->name ?? 'N/A' }}</td>
                <td>{{ $plan->created_at->format('Y-m-d') }}</td>
                <td>
                  <a href="{{ route('production-plans.show', $plan) }}" class="btn btn-sm btn-info">View</a>
                  @if($plan->status === 'draft')
                  <a href="{{ route('production-plans.edit', $plan) }}" class="btn btn-sm btn-warning">Edit</a>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6">No production plans found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>

          {{ $plans->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection