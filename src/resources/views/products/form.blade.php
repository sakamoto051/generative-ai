@csrf

<div class="mb-3">
  <label for="code" class="form-label">コード</label>
  <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $product->code ?? '') }}" required>
  @error('code')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label for="name" class="form-label">品名</label>
  <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
  @error('name')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label for="type" class="form-label">種別</label>
  <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
    <option value="">選択してください</option>
    @foreach(['product' => '製品', 'part' => '部品', 'material' => '原材料'] as $value => $label)
    <option value="{{ $value }}" {{ old('type', $product->type ?? '') == $value ? 'selected' : '' }}>
      {{ $label }}
    </option>
    @endforeach
  </select>
  @error('type')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label for="standard_cost" class="form-label">標準原価</label>
  <input type="number" step="0.01" class="form-control @error('standard_cost') is-invalid @enderror" id="standard_cost" name="standard_cost" value="{{ old('standard_cost', $product->standard_cost ?? 0) }}" required>
  @error('standard_cost')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label for="lead_time_days" class="form-label">リードタイム (日)</label>
  <input type="number" class="form-control @error('lead_time_days') is-invalid @enderror" id="lead_time_days" name="lead_time_days" value="{{ old('lead_time_days', $product->lead_time_days ?? 0) }}" required>
  @error('lead_time_days')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label for="minimum_stock_level" class="form-label">最低在庫数 (安全在庫)</label>
  <input type="number" class="form-control @error('minimum_stock_level') is-invalid @enderror" id="minimum_stock_level" name="minimum_stock_level" value="{{ old('minimum_stock_level', $product->minimum_stock_level ?? 0) }}" required>
  @error('minimum_stock_level')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label for="current_stock" class="form-label">現在庫数</label>
  <input type="number" class="form-control @error('current_stock') is-invalid @enderror" id="current_stock" name="current_stock" value="{{ old('current_stock', $product->current_stock ?? 0) }}" required>
  @error('current_stock')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>