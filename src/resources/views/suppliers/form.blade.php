@csrf

<div class="mb-3">
  <label for="code" class="form-label">コード</label>
  <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $supplier->code ?? '') }}" required>
  @error('code')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label for="name" class="form-label">会社名</label>
  <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $supplier->name ?? '') }}" required>
  @error('name')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label for="contact_person" class="form-label">担当者名</label>
  <input type="text" class="form-control @error('contact_person') is-invalid @enderror" id="contact_person" name="contact_person" value="{{ old('contact_person', $supplier->contact_person ?? '') }}">
  @error('contact_person')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label for="email" class="form-label">メールアドレス</label>
  <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $supplier->email ?? '') }}">
  @error('email')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label for="phone" class="form-label">電話番号</label>
  <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}">
  @error('phone')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label for="address" class="form-label">住所</label>
  <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $supplier->address ?? '') }}</textarea>
  @error('address')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>