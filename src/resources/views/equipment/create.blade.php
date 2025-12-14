@extends('layouts.app')

@section('title', '設備登録')

@section('content')
<div class="max-w-4xl mx-auto">
  <!-- ヘッダー -->
  <div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">設備登録</h2>
    <p class="mt-1 text-sm text-gray-600">新しい設備情報を登録します</p>
  </div>

  <!-- 登録フォーム -->
  <div class="bg-white shadow-sm sm:rounded-lg">
    <form action="{{ route('equipment.store') }}" method="POST" class="p-6 space-y-6">
      @csrf

      <!-- 基本情報 -->
      <div>
        <h3 class="text-lg font-medium text-gray-900 mb-4">基本情報</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- 設備コード -->
          <div>
            <label for="code" class="block text-sm font-medium text-gray-700">設備コード <span class="text-red-500">*</span></label>
            <input type="text" name="code" id="code" value="{{ old('code') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('code') border-red-500 @enderror">
            @error('code')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 設備名 -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">設備名 <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-500 @enderror">
            @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 設備カテゴリ -->
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700">設備カテゴリ</label>
            <input type="text" name="category" id="category" value="{{ old('category') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('category') border-red-500 @enderror">
            @error('category')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 工程 -->
          <div>
            <label for="process" class="block text-sm font-medium text-gray-700">工程</label>
            <input type="text" name="process" id="process" value="{{ old('process') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('process') border-red-500 @enderror">
            @error('process')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 設置場所 -->
          <div class="md:col-span-2">
            <label for="location" class="block text-sm font-medium text-gray-700">設置場所</label>
            <input type="text" name="location" id="location" value="{{ old('location') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('location') border-red-500 @enderror">
            @error('location')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <!-- 生産能力・コスト情報 -->
      <div class="border-t pt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">生産能力・コスト情報</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- 生産能力 -->
          <div>
            <label for="capacity_per_hour" class="block text-sm font-medium text-gray-700">生産能力 (個/時間)</label>
            <input type="number" name="capacity_per_hour" id="capacity_per_hour" value="{{ old('capacity_per_hour', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('capacity_per_hour') border-red-500 @enderror">
            @error('capacity_per_hour')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 段取り時間 -->
          <div>
            <label for="setup_time_minutes" class="block text-sm font-medium text-gray-700">段取り時間 (分)</label>
            <input type="number" name="setup_time_minutes" id="setup_time_minutes" value="{{ old('setup_time_minutes', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('setup_time_minutes') border-red-500 @enderror">
            @error('setup_time_minutes')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 時間チャージ -->
          <div>
            <label for="hourly_rate" class="block text-sm font-medium text-gray-700">時間チャージ (円/時間) <span class="text-red-500">*</span></label>
            <input type="number" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate', 0) }}" step="0.01" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('hourly_rate') border-red-500 @enderror">
            @error('hourly_rate')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- メンテナンス周期 -->
          <div>
            <label for="maintenance_interval_days" class="block text-sm font-medium text-gray-700">メンテナンス周期 (日)</label>
            <input type="number" name="maintenance_interval_days" id="maintenance_interval_days" value="{{ old('maintenance_interval_days', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('maintenance_interval_days') border-red-500 @enderror">
            @error('maintenance_interval_days')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <!-- 備考・ステータス -->
      <div class="border-t pt-6">
        <div class="space-y-4">
          <!-- 備考 -->
          <div>
            <label for="notes" class="block text-sm font-medium text-gray-700">備考</label>
            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
            @error('notes')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- ステータス -->
          <div class="flex items-center">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
            <label for="is_active" class="ml-2 block text-sm text-gray-900">有効</label>
          </div>
        </div>
      </div>

      <!-- ボタン -->
      <div class="flex items-center justify-end space-x-3 border-t pt-6">
        <a href="{{ route('equipment.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
          キャンセル
        </a>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
          登録
        </button>
      </div>
    </form>
  </div>
</div>
@endsection