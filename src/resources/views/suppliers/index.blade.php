@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">{{ __('取引先一覧') }}</div>

        <div class="card-body">
          <a href="{{ route('suppliers.create') }}" class="btn btn-primary mb-3">新規取引先登録</a>

          <table class="table">
            <thead>
              <tr>
                <th>コード</th>
                <th>会社名</th>
                <th>担当者</th>
                <th>メールアドレス</th>
                <th>電話番号</th>
                <th>操作</th>
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
                  <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-sm btn-info">詳細</a>
                  <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning">編集</a>
                  <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('本当に削除しますか?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">削除</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6">取引先が見つかりません。</td>
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