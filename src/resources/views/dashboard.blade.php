@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row mb-4">
    <div class="col-md-12">
      <h1>ダッシュボード</h1>
      <p class="text-muted">生産および在庫状況の概要を表示します。</p>
    </div>
  </div>

  <!-- Quick Stats Cards -->
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card bg-primary text-white h-100">
        <div class="card-body">
          <h5 class="card-title">進行中の計画</h5>
          <p class="display-6">{{ number_format($activePlansCount) }}</p>
          <small>承認中 / 承認済</small>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-warning text-dark h-100">
        <div class="card-body">
          <h5 class="card-title">在庫不足品目</h5>
          <p class="display-6">{{ number_format($lowStockCount) }}</p>
          <small>最低在庫数未満</small>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-success text-white h-100">
        <div class="card-body">
          <h5 class="card-title">入荷待ち (PO)</h5>
          <p class="display-6">{{ number_format($purchaseOrdersCount) }}</p>
          <small>発注済件数</small>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-info text-white h-100">
        <div class="card-body">
          <h5 class="card-title">入荷予定金額</h5>
          <p class="display-6">¥{{ number_format($pendingReceiptAmount, 0) }}</p>
          <small>発注残金額</small>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Recent Production Plans -->
    <div class="col-md-8">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>最近の生産計画</span>
          <a href="{{ route('production-plans.index') }}" class="btn btn-sm btn-outline-primary">すべて表示</a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>計画番号</th>
                  <th>ステータス</th>
                  <th>進捗</th>
                  <th>期間</th>
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
                    <small>{{ \Carbon\Carbon::parse($plan->period_start)->format('Y/m/d') }} - {{ \Carbon\Carbon::parse($plan->period_end)->format('m/d') }}</small>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="4" class="text-center text-muted">最近の計画はありません。</td>
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
          <span class="text-danger"><i class="bi bi-exclamation-triangle"></i> 在庫アラート</span>
          <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">在庫一覧</a>
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
                <div style="font-size: 0.75rem;">基準: {{ $product->minimum_stock_level }}</div>
              </div>
            </li>
            @empty
            <li class="list-group-item text-center text-muted py-3">
              在庫は適正です。
            </li>
            @endforelse
          </ul>
        </div>
      </div>

      <div class="d-grid gap-2">
        <a href="{{ route('production-plans.create') }}" class="btn btn-outline-primary">
          新しい生産計画を作成
        </a>
        <a href="{{ route('purchase-orders.create') }}" class="btn btn-outline-success">
          新規発注作成
        </a>
      </div>
    </div>
  </div>
</div>
@endsection