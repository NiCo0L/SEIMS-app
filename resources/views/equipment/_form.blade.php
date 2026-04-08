<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Category</label>
        <select name="equipment_category_id" class="form-select" required>
            <option value="">Select category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('equipment_category_id', $equipment->equipment_category_id ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Type</label>
        <select name="equipment_type_id" class="form-select" required>
            <option value="">Select type</option>
            @foreach ($types as $type)
                <option value="{{ $type->id }}" @selected(old('equipment_type_id', $equipment->equipment_type_id ?? '') == $type->id)>{{ $type->name }} ({{ $type->category->name }})</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Equipment name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $equipment->name ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Person in charge</label>
        <input type="text" name="person_in_charge" class="form-control" value="{{ old('person_in_charge', $equipment->person_in_charge ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Document number</label>
        <input type="text" name="document_number" class="form-control" value="{{ old('document_number', $equipment->document_number ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Control number</label>
        <input type="text" name="control_number" class="form-control" value="{{ old('control_number', $equipment->control_number ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $equipment->status ?? 'serviceable') == $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Last status date</label>
        <input type="date" name="last_status_date" class="form-control" value="{{ old('last_status_date', isset($equipment) && $equipment->last_status_date ? $equipment->last_status_date->format('Y-m-d') : '') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Remarks</label>
        <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $equipment->remarks ?? '') }}</textarea>
    </div>
</div>
