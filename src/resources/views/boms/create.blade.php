@extends('layouts.app')

@section('title', 'BOM登録')

@section('content')
<div class="max-w-4xl mx-auto">
  <!-- ヘッダー -->
  <div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">BOM登録</h2>
    <p class="mt-1 text-sm text-gray-600">新しい部品表情報を登録します</p>
  </div>

  <!-- 登録フォーム -->
  <div class="bg-white shadow-sm sm:rounded-lg">
    <form action="{{ route('boms.store') }}" method="POST" class="p-6 space-y-6">
      @csrf

      <!-- 基本情報 -->
      <div>
        <h3 class="text-lg font-medium text-gray-900 mb-4">基本情報</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- 製品 -->
          <div class="md:col-span-2">
            <label for="product_id" class="block text-sm font-medium text-gray-700">製品 <span class="text-red-500">*</span></label>
            <select name="product_id" id="product_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('product_id') border-red-500 @enderror">
              <option value="">選択してください</option>
              @foreach($products as $product)
              <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                {{ $product->code }} - {{ $product->name }}
              </option>
              @endforeach
            </select>
            @error('product_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 材料 -->
          <div class="md:col-span-2">
            <label for="material_id" class="block text-sm font-medium text-gray-700">材料 <span class="text-red-500">*</span></label>
            <select name="material_id" id="material_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('material_id') border-red-500 @enderror">
              <option value="">選択してください</option>
              @foreach($materials as $material)
              <option value="{{ $material->id }}" {{ old('material_id') == $material->id ? 'selected' : '' }} data-unit="{{ $material->unit }}">
                {{ $material->code }} - {{ $material->name }} ({{ $material->unit }})
              </option>
              @endforeach
            </select>
            @error('material_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 使用数量 -->
          <div>
            <label for="quantity" class="block text-sm font-medium text-gray-700">使用数量 <span class="text-red-500">*</span></label>
            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" step="0.01" min="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('quantity') border-red-500 @enderror">
            @error('quantity')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 単位 -->
          <div>
            <label for="unit" class="block text-sm font-medium text-gray-700">単位 <span class="text-red-500">*</span></label>
            <input type="text" name="unit" id="unit" value="{{ old('unit', 'kg') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('unit') border-red-500 @enderror">
            @error('unit')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- 歩留まり率 -->
          <div>
            <label for="yield_rate" class="block text-sm font-medium text-gray-700">歩留まり率 (%) <span class="text-red-500">*</span></label>
            <input type="number" name="yield_rate" id="yield_rate" value="{{ old('yield_rate', 100) }}" step="0.1" min="0" max="100" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('yield_rate') border-red-500 @enderror">
            @error('yield_rate')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">100%が標準です。歩留まりが悪い場合は小さい値を設定してください。</p>
          </div>

          <!-- 順序 -->
          <div>
            <label for="sequence" class="block text-sm font-medium text-gray-700">順序</label>
            <input type="number" name="sequence" id="sequence" value="{{ old('sequence', 1) }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('sequence') border-red-500 @enderror">
            @error('sequence')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">製造工程の順序を指定します。</p>
          </div>

          <!-- 備考 -->
          <div class="md:col-span-2">
            <label for="notes" class="block text-sm font-medium text-gray-700">備考</label>
            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
            @error('notes')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <!-- ボタン -->
      <div class="flex items-center justify-end space-x-3 border-t pt-6">
        <a href="{{ route('boms.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
          キャンセル
        </a>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
          登録
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  // 材料選択時に単位を自動設定
  document.getElementById('material_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const unit = selectedOption.getAttribute('data-unit');
    if (unit) {
      document.getElementById('unit').value = unit;
    }
  });
</script>
@endpush
@endsection