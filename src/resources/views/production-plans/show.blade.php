@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>{{ __('生産計画詳細') }}</span>
          <div>
            <span
              class="badge {{ $productionPlan->status === 'approved' ? 'bg-success' : ($productionPlan->status === 'draft' ? 'bg-secondary' : 'bg-warning') }} text-dark border">
              @switch($productionPlan->status)
              @case('draft') ドラフト @break
              @case('pending_approval') 承認待ち @break
              @case('approved') 承認済 @break
              @case('rejected') 却下 @break
              @default {{ $productionPlan->status }}
              @endswitch
            </span>
          </div>
        </div>

        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-3 fw-bold">計画番号</div>
            <div class="col-md-9">{{ $productionPlan->plan_number }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3 fw-bold">期間</div>
            <div class="col-md-9">{{ $productionPlan->period_start }} 〜 {{ $productionPlan->period_end }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3 fw-bold">作成者</div>
            <div class="col-md-9">{{ $productionPlan->creator->name ?? 'N/A' }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3 fw-bold">作成日</div>
            <div class="col-md-9">{{ $productionPlan->created_at->format('Y/m/d H:i') }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3 fw-bold">説明</div>
            <div class="col-md-9">{{ $productionPlan->description ?? '説明なし' }}</div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3 fw-bold">見積原価 (標準)</div>
            <div class="col-md-9">¥{{ number_format($estimatedCost, 2) }}</div>
          </div>

          <hr class="my-4">
          <h5 class="mb-3">計画品目 (進捗)</h5>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>製品</th>
                <th>計画数</th>
                <th>実績数</th>
                <th>不良数</th>
                <th>進捗率</th>
                <th>アクション</th>
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
                    class="btn btn-sm btn-outline-primary">実績報告</a>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>

          <hr class="my-4">
          <h5 class="mb-3">所要量計算 (シミュレーション)</h5>
          @if($materialRequirements->isEmpty())
          <div class="alert alert-info">必要な原材料はありません。</div>
          @else
          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th>部材コード</th>
                <th>品名</th>
                <th>種別</th>
                <th>必要数量</th>
                <th>単価</th>
                <th>合計コスト</th>
              </tr>
            </thead>
            <tbody>
              @foreach($materialRequirements as $req)
              <tr>
                <td>{{ $req['code'] }}</td>
                <td>{{ $req['name'] }}</td>
                <td>
                  @switch($req['type'])
                  @case('product') 製品 @break
                  @case('part') 部品 @break
                  @case('material') 原材料 @break
                  @default {{ ucfirst($req['type']) }}
                  @endswitch
                </td>
                <td>{{ number_format($req['total_quantity'], 2) }}</td>
                <td>¥{{ number_format($req['unit_cost'], 2) }}</td>
                <td>¥{{ number_format($req['total_quantity'] * $req['unit_cost'], 2) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @endif

          <div class="row mb-3">
            <div class="col-md-4">
              <div class="card bg-light">
                <div class="card-body py-2">
                  <small class="text-muted">計画総コスト</small>
                  <h4 class="mb-0">¥{{ number_format($costData['total_planned_cost'], 2) }}</h4>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card bg-light">
                <div class="card-body py-2">
                  <small class="text-muted">実績総コスト</small>
                  <h4 class="mb-0">¥{{ number_format($costData['total_actual_cost'], 2) }}</h4>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div
                class="card {{ $costData['total_variance'] >= 0 ? 'bg-success text-white' : 'bg-danger text-white' }}">
                <div class="card-body py-2">
                  <small class="{{ $costData['total_variance'] >= 0 ? 'text-white-50' : 'text-white-50' }}">差異 (予算残)</small>
                  <h4 class="mb-0">¥{{ number_format($costData['total_variance'], 2) }}</h4>
                </div>
              </div>
            </div>
          </div>

          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th>製品</th>
                <th>標準原価</th>
                <th>計画コスト (数)</th>
                <th>実績コスト (数)</th>
                <th>差異</th>
              </tr>
            </thead>
            <tbody>
              @foreach($costData['items'] as $item)
              <tr>
                <td>{{ $item['product_name'] }}</td>
                <td>¥{{ number_format($item['standard_unit_cost'], 2) }}</td>
                <td>¥{{ number_format($item['planned_cost'], 2) }} <small
                    class="text-muted">({{ $item['planned_qty'] }})</small></td>
                <td>¥{{ number_format($item['actual_cost'], 2) }} <small
                    class="text-muted">({{ $item['actual_qty'] }})</small></td>
                <td class="{{ $item['variance'] < 0 ? 'text-danger fw-bold' : 'text-success' }}">
                  ¥{{ number_format($item['variance'], 2) }}
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="card-footer text-end d-flex justify-content-end gap-2">
          <a href="{{ route('production-plans.index') }}" class="btn btn-secondary me-2">一覧に戻る</a>

          @if($productionPlan->status === 'draft')
          <a href="{{ route('production-plans.edit', $productionPlan) }}" class="btn btn-warning me-2">編集</a>
          <form action="{{ route('production-plans.submit', $productionPlan) }}" method="POST" style="display:inline;" onsubmit="return confirm('承認申請しますか？');">
            @csrf
            <button type="submit" class="btn btn-primary">承認申請</button>
          </form>
          @endif

          @if($productionPlan->status === 'pending_approval')
          <form action="{{ route('production-plans.approve', $productionPlan) }}" method="POST" style="display:inline;" onsubmit="return confirm('承認しますか？');">
            @csrf
            <button type="submit" class="btn btn-success">承認</button>
          </form>
          <form action="{{ route('production-plans.reject', $productionPlan) }}" method="POST" style="display:inline;" onsubmit="return confirm('却下しますか？');">
            @csrf
            <button type="submit" class="btn btn-danger">却下</button>
          </form>
          @endif

          @if($productionPlan->status === 'approved')
          <form action="{{ route('production-plans.generate-po', $productionPlan) }}" method="POST"
            style="display:inline;" onsubmit="return confirm('所要量に基づいて発注書を一括生成しますか？\n（注: 既存の発注とは別に新規作成されます）');">
            @csrf
            <button type="submit" class="btn btn-info text-white">発注書生成</button>
          </form>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection