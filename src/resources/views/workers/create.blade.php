@extends('layouts.app')

@section('title', '作業者登録')

@section('content')
<div class="max-w-4xl mx-auto">
  <!-- ヘッダー -->
  <div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">作業者登録</h2>
    <p class="mt-1 text-sm text-gray-600">新しい作業者情報を登録します</p>
  </div>

  <!-- 登録フォーム -->
  <div class="bg-white shadow-sm sm:rounded-lg">
    <form action="{{ route('workers.store') }}" method="POST" class="p-6 space-y-6">
      @csrf

      <!-- 基本情報 -->
      <div>
        <h3 class="text-lg font-medium text-gray-900 mb-4">基本情報</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- 作業者コード -->
          <div>
            <label for="code" class="block text-sm font-medium text-gray-700">作業者コード <span class="text-red-500">*</span></label>
            <input type="text" name="code" id="code" value="{{ old('code') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('code') border-red-500 @enderror">
            @error('code')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 氏名 -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">氏名 <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-500 @enderror">
            @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- メールアドレス -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">メールアドレス</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('email') border-red-500 @enderror">
            @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 電話番号 -->
          <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">電話番号</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('phone') border-red-500 @enderror">
            @error('phone')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 所属部署 -->
          <div>
            <label for="department" class="block text-sm font-medium text-gray-700">所属部署</label>
            <input type="text" name="department" id="department" value="{{ old('department') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('department') border-red-500 @enderror">
            @error('department')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 入社日 -->
          <div>
            <label for="hire_date" class="block text-sm font-medium text-gray-700">入社日</label>
            <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('hire_date') border-red-500 @enderror">
            @error('hire_date')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <!-- スキル・給与情報 -->
      <div class="border-t pt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">スキル・給与情報</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- スキルレベル -->
          <div>
            <label for="skill_level" class="block text-sm font-medium text-gray-700">スキルレベル <span class="text-red-500">*</span></label>
            <select name="skill_level" id="skill_level" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('skill_level') border-red-500 @enderror">
              <option value="初級" {{ old('skill_level') === '初級' ? 'selected' : '' }}>初級</option>
              <option value="中級" {{ old('skill_level') === '中級' ? 'selected' : '' }}>中級</option>
              <option value="上級" {{ old('skill_level') === '上級' ? 'selected' : '' }}>上級</option>
              <option value="熟練" {{ old('skill_level') === '熟練' ? 'selected' : '' }}>熟練</option>
            </select>
            @error('skill_level')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 時給 -->
          <div>
            <label for="hourly_wage" class="block text-sm font-medium text-gray-700">時給 (円) <span class="text-red-500">*</span></label>
            <input type="number" name="hourly_wage" id="hourly_wage" value="{{ old('hourly_wage', 0) }}" step="0.01" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('hourly_wage') border-red-500 @enderror">
            @error('hourly_wage')
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
            <label for="is_active" class="ml-2 block text-sm text-gray-900">在籍中</label>
          </div>
        </div>
      </div>

      <!-- ボタン -->
      <div class="flex items-center justify-end space-x-3 border-t pt-6">
        <a href="{{ route('workers.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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