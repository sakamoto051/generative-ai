@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{ __('取引先詳細') }}</div>

        <div class="card-body">
          <div class="mb-3">
            <strong>コード:</strong> {{ $supplier->code }}
          </div>
          <div class="mb-3">
            <strong>会社名:</strong> {{ $supplier->name }}
          </div>
          <div class="mb-3">
            <strong>担当者名:</strong> {{ $supplier->contact_person }}
          </div>
          <div class="mb-3">
            <strong>メールアドレス:</strong> {{ $supplier->email }}
          </div>
          <div class="mb-3">
            <strong>電話番号:</strong> {{ $supplier->phone }}
          </div>
          <div class="mb-3">
            <strong>住所:</strong> {{ $supplier->address }}
          </div>

          <div class="mt-3">
            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning">編集</a>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">一覧へ戻る</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection