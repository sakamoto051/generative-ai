@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">{{ __('生産計画一覧') }}</div>

        <div class="card-body">
          <a href="{{ route('production-plans.create') }}" class="btn btn-primary mb-3">新規計画作成</a>

          <table class="table">
            <thead>
              <tr>
                <th>計画番号</th>
                <th>期間</th>
                <th>ステータス</th>
                <th>作成者</th>
                <th>作成日</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              @forelse($plans as $plan)
              <tr>
                <td>{{ $plan->plan_number }}</td>
                <td>{{ $plan->period_start }} - {{ $plan->period_end }}</td>
                <td>
                  @switch($plan->status)
                  @case('draft') <span class="badge bg-secondary">ドラフト</span> @break
                  @case('pending_approval') <span class="badge bg-warning text-dark">承認待ち</span> @break
                  @case('approved') <span class="badge bg-success">承認済</span> @break
                  @case('rejected') <span class="badge bg-danger">却下</span> @break
                  @default {{ $plan->status }}
                  @endswitch
                </td>
                <td>{{ $plan->creator->name ?? 'N/A' }}</td>
                <td>{{ $plan->created_at->format('Y/m/d') }}</td>
                <td>
                  <a href="{{ route('production-plans.show', $plan) }}" class="btn btn-sm btn-info">詳細</a>
                  @if($plan->status === 'draft')
                  <a href="{{ route('production-plans.edit', $plan) }}" class="btn btn-sm btn-warning">編集</a>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6">生産計画が見つかりません。</td>
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