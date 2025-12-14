@csrf

<div class="mb-3">
  <label for="po_number" class="form-label">PO Number</label>
  <input type="text" class="form-control @error('po_number') is-invalid @enderror" id="po_number" name="po_number" value="{{ old('po_number', $purchaseOrder->po_number ?? $suggestedPoNumber ?? '') }}" required>
  @error('po_number')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label for="supplier_id" class="form-label">Supplier</label>
  <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
    <option value="">Select Supplier</option>
    @foreach($suppliers as $supplier)
    <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchaseOrder->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
      {{ $supplier->name }} ({{ $supplier->code }})
    </option>
    @endforeach
  </select>
  @error('supplier_id')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label for="order_date" class="form-label">Order Date</label>
  <input type="date" class="form-control @error('order_date') is-invalid @enderror" id="order_date" name="order_date" value="{{ old('order_date', isset($purchaseOrder) ? $purchaseOrder->order_date->format('Y-m-d') : date('Y-m-d')) }}" required>
  @error('order_date')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label for="delivery_due_date" class="form-label">Delivery Due Date</label>
  <input type="date" class="form-control @error('delivery_due_date') is-invalid @enderror" id="delivery_due_date" name="delivery_due_date" value="{{ old('delivery_due_date', isset($purchaseOrder) && $purchaseOrder->delivery_due_date ? $purchaseOrder->delivery_due_date->format('Y-m-d') : '') }}">
  @error('delivery_due_date')
  <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>