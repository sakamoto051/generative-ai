@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{ __('Create Product') }}</div>

        <div class="card-body">
          <form method="POST" action="{{ route('products.store') }}">
            @include('products.form')

            <div class="mb-0">
              <button type="submit" class="btn btn-primary">
                {{ __('Create Product') }}
              </button>
              <a href="{{ route('products.index') }}" class="btn btn-secondary">
                {{ __('Cancel') }}
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection