<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Warehouse</label>
        <select name="warehouse_id" class="form-select" required>
            <option value="">Select warehouse</option>
            @foreach ($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" @selected(old('warehouse_id', $supply->warehouse_id ?? '') == $warehouse->id)>{{ $warehouse->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Category</label>
        <select name="supply_category_id" class="form-select" required>
            <option value="">Select category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('supply_category_id', $supply->supply_category_id ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Item name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $supply->name ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Document number</label>
        <input type="text" name="document_number" class="form-control" value="{{ old('document_number', $supply->document_number ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Unit</label>
        <select name="unit_id" class="form-select" required>
            <option value="">Select unit</option>
            @foreach ($units as $unit)
                <option value="{{ $unit->id }}" @selected(old('unit_id', $supply->unit_id ?? '') == $unit->id)>{{ $unit->name }} ({{ $unit->symbol }})</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Current quantity</label>
        <input type="number" step="0.01" min="0" name="current_quantity" class="form-control" value="{{ old('current_quantity', $supply->current_quantity ?? '0') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Minimum quantity</label>
        <input type="number" step="0.01" min="0" name="minimum_quantity" class="form-control" value="{{ old('minimum_quantity', $supply->minimum_quantity ?? '0') }}" required>
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $supply->description ?? '') }}</textarea>
    </div>
</div>
