@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{ __('新規発注作成') }}</div>

        <div class="card-body">
          <form method="POST" action="{{ route('purchase-orders.store') }}">
            @include('purchase-orders.form')

            <div class="mb-0">
              <button type="submit" class="btn btn-primary">
                {{ __('作成') }}
              </button>
              <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
                {{ __('キャンセル') }}
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection