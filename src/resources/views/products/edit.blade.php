@extends('layouts.app')

@section('title', '製品編集')

@section('content')
<div class="max-w-4xl mx-auto">
  <!-- ヘッダー -->
  <div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">製品編集</h2>
    <p class="mt-1 text-sm text-gray-600">製品情報を更新します</p>
  </div>

  <!-- 編集フォーム -->
  <div class="bg-white shadow-sm sm:rounded-lg">
    <form action="{{ route('products.update', $product) }}" method="POST" class="p-6 space-y-6">
      @csrf
      @method('PUT')

      <!-- 基本情報 -->
      <div>
        <h3 class="text-lg font-medium text-gray-900 mb-4">基本情報</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- 製品コード -->
          <div>
            <label for="code" class="block text-sm font-medium text-gray-700">製品コード <span class="text-red-500">*</span></label>
            <input type="text" name="code" id="code" value="{{ old('code', $product->code) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('code') border-red-500 @enderror">
            @error('code')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 製品名 -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">製品名 <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-500 @enderror">
            @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- カテゴリ -->
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700">カテゴリ</label>
            <input type="text" name="category" id="category" value="{{ old('category', $product->category) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('category') border-red-500 @enderror">
            @error('category')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 単位 -->
          <div>
            <label for="unit" class="block text-sm font-medium text-gray-700">単位 <span class="text-red-500">*</span></label>
            <input type="text" name="unit" id="unit" value="{{ old('unit', $product->unit) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('unit') border-red-500 @enderror">
            @error('unit')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 説明 -->
          <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700">説明</label>
            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
            @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <!-- 原価・価格情報 -->
      <div class="border-t pt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">原価・価格情報</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- 標準原価 -->
          <div>
            <label for="standard_cost" class="block text-sm font-medium text-gray-700">標準原価 (円) <span class="text-red-500">*</span></label>
            <input type="number" name="standard_cost" id="standard_cost" value="{{ old('standard_cost', $product->standard_cost) }}" step="0.01" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('standard_cost') border-red-500 @enderror">
            @error('standard_cost')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 販売価格 -->
          <div>
            <label for="selling_price" class="block text-sm font-medium text-gray-700">販売価格 (円)</label>
            <input type="number" name="selling_price" id="selling_price" value="{{ old('selling_price', $product->selling_price) }}" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('selling_price') border-red-500 @enderror">
            @error('selling_price')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <!-- 在庫・生産情報 -->
      <div class="border-t pt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">在庫・生産情報</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- リードタイム -->
          <div>
            <label for="lead_time_days" class="block text-sm font-medium text-gray-700">リードタイム (日) <span class="text-red-500">*</span></label>
            <input type="number" name="lead_time_days" id="lead_time_days" value="{{ old('lead_time_days', $product->lead_time_days) }}" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('lead_time_days') border-red-500 @enderror">
            @error('lead_time_days')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 安全在庫数 -->
          <div>
            <label for="safety_stock" class="block text-sm font-medium text-gray-700">安全在庫数 <span class="text-red-500">*</span></label>
            <input type="number" name="safety_stock" id="safety_stock" value="{{ old('safety_stock', $product->safety_stock) }}" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('safety_stock') border-red-500 @enderror">
            @error('safety_stock')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <!-- ステータス -->
      <div class="border-t pt-6">
        <div class="flex items-center">
          <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
          <label for="is_active" class="ml-2 block text-sm text-gray-900">有効</label>
        </div>
      </div>

      <!-- ボタン -->
      <div class="flex items-center justify-end space-x-3 border-t pt-6">
        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
          キャンセル
        </a>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
          更新
        </button>
      </div>
    </form>
  </div>
</div>
@endsection