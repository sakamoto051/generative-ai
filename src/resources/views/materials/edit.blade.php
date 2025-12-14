@extends('layouts.app')

@section('title', '材料編集')

@section('content')
<div class="max-w-4xl mx-auto">
  <!-- ヘッダー -->
  <div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">材料編集</h2>
    <p class="mt-1 text-sm text-gray-600">材料情報を編集します</p>
  </div>

  <!-- 編集フォーム -->
  <div class="bg-white shadow-sm sm:rounded-lg">
    <form action="{{ route('materials.update', $material) }}" method="POST" class="p-6 space-y-6">
      @csrf
      @method('PUT')

      <!-- 基本情報 -->
      <div>
        <h3 class="text-lg font-medium text-gray-900 mb-4">基本情報</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- 材料コード -->
          <div>
            <label for="code" class="block text-sm font-medium text-gray-700">材料コード <span class="text-red-500">*</span></label>
            <input type="text" name="code" id="code" value="{{ old('code', $material->code) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('code') border-red-500 @enderror">
            @error('code')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 材料名 -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">材料名 <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $material->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-500 @enderror">
            @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- カテゴリ -->
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700">カテゴリ <span class="text-red-500">*</span></label>
            <input type="text" name="category" id="category" value="{{ old('category', $material->category) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('category') border-red-500 @enderror">
            @error('category')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 単位 -->
          <div>
            <label for="unit" class="block text-sm font-medium text-gray-700">単位 <span class="text-red-500">*</span></label>
            <input type="text" name="unit" id="unit" value="{{ old('unit', $material->unit) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('unit') border-red-500 @enderror">
            @error('unit')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 仕入先 -->
          <div>
            <label for="supplier" class="block text-sm font-medium text-gray-700">仕入先</label>
            <input type="text" name="supplier" id="supplier" value="{{ old('supplier', $material->supplier) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('supplier') border-red-500 @enderror">
            @error('supplier')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 説明 -->
          <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700">説明</label>
            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $material->description) }}</textarea>
            @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <!-- 価格・在庫情報 -->
      <div class="border-t pt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">価格・在庫情報</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- 単価 -->
          <div>
            <label for="unit_price" class="block text-sm font-medium text-gray-700">単価 (円) <span class="text-red-500">*</span></label>
            <input type="number" name="unit_price" id="unit_price" value="{{ old('unit_price', $material->unit_price) }}" step="0.01" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('unit_price') border-red-500 @enderror">
            @error('unit_price')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 現在在庫数 -->
          <div>
            <label for="current_stock" class="block text-sm font-medium text-gray-700">現在在庫数 <span class="text-red-500">*</span></label>
            <input type="number" name="current_stock" id="current_stock" value="{{ old('current_stock', $material->current_stock) }}" step="0.01" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('current_stock') border-red-500 @enderror">
            @error('current_stock')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 安全在庫数 -->
          <div>
            <label for="safety_stock" class="block text-sm font-medium text-gray-700">安全在庫数 <span class="text-red-500">*</span></label>
            <input type="number" name="safety_stock" id="safety_stock" value="{{ old('safety_stock', $material->safety_stock) }}" step="0.01" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('safety_stock') border-red-500 @enderror">
            @error('safety_stock')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 発注点 -->
          <div>
            <label for="reorder_point" class="block text-sm font-medium text-gray-700">発注点 <span class="text-red-500">*</span></label>
            <input type="number" name="reorder_point" id="reorder_point" value="{{ old('reorder_point', $material->reorder_point) }}" step="0.01" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('reorder_point') border-red-500 @enderror">
            @error('reorder_point')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- リードタイム -->
          <div>
            <label for="lead_time_days" class="block text-sm font-medium text-gray-700">リードタイム (日) <span class="text-red-500">*</span></label>
            <input type="number" name="lead_time_days" id="lead_time_days" value="{{ old('lead_time_days', $material->lead_time_days) }}" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('lead_time_days') border-red-500 @enderror">
            @error('lead_time_days')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <!-- ステータス -->
      <div class="border-t pt-6">
        <div class="flex items-center">
          <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $material->is_active) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
          <label for="is_active" class="ml-2 block text-sm text-gray-900">有効</label>
        </div>
      </div>

      <!-- ボタン -->
      <div class="flex items-center justify-end space-x-3 border-t pt-6">
        <a href="{{ route('materials.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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