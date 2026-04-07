@php($title = 'SEIMS Settings')
@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h2 class="h4 mb-1">Master Data Settings</h2>
        <p class="text-muted mb-0">Manage warehouses, supply dropdown values, and equipment category/type references used throughout SEIMS.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card form-card mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-3">Add warehouse</h3>
                    <form method="POST" action="{{ route('settings.warehouses.store') }}" class="row g-3">
                        @csrf
                        <div class="col-12"><input name="name" class="form-control" placeholder="Warehouse name" required></div>
                        <div class="col-12"><input name="location" class="form-control" placeholder="Location"></div>
                        <div class="col-12"><textarea name="description" class="form-control" rows="3" placeholder="Description"></textarea></div>
                        <div class="col-12 d-grid"><button class="btn btn-success">Add warehouse</button></div>
                    </form>
                </div>
            </div>

            <div class="card table-card mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-3">Warehouses</h3>
                    <ul class="list-group list-group-flush">
                        @foreach ($warehouses as $warehouse)
                            <li class="list-group-item px-0">
                                <div class="fw-semibold">{{ $warehouse->name }}</div>
                                <small class="text-muted">{{ $warehouse->location }} {{ $warehouse->description ? '| '.$warehouse->description : '' }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card form-card mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-3">Add supply category</h3>
                    <form method="POST" action="{{ route('settings.supply-categories.store') }}" class="row g-3">
                        @csrf
                        <div class="col-12"><input name="name" class="form-control" placeholder="Category name" required></div>
                        <div class="col-12"><textarea name="description" class="form-control" rows="3" placeholder="Description"></textarea></div>
                        <div class="col-12 d-grid"><button class="btn btn-success">Add category</button></div>
                    </form>
                </div>
            </div>

            <div class="card table-card">
                <div class="card-body">
                    <h3 class="h5 mb-3">Supply categories</h3>
                    <ul class="list-group list-group-flush">
                        @foreach ($supplyCategories as $category)
                            <li class="list-group-item px-0">
                                <div class="fw-semibold">{{ $category->name }}</div>
                                <small class="text-muted">{{ $category->description }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card form-card mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-3">Add unit</h3>
                    <form method="POST" action="{{ route('settings.units.store') }}" class="row g-3">
                        @csrf
                        <div class="col-8"><input name="name" class="form-control" placeholder="Unit name" required></div>
                        <div class="col-4"><input name="symbol" class="form-control" placeholder="Symbol" required></div>
                        <div class="col-12 d-grid"><button class="btn btn-success">Add unit</button></div>
                    </form>
                </div>
            </div>

            <div class="card table-card mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-3">Units</h3>
                    <ul class="list-group list-group-flush">
                        @foreach ($units as $unit)
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>{{ $unit->name }}</span>
                                <strong>{{ $unit->symbol }}</strong>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card form-card mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-3">Add equipment category</h3>
                    <form method="POST" action="{{ route('settings.equipment-categories.store') }}" class="row g-3">
                        @csrf
                        <div class="col-12"><input name="name" class="form-control" placeholder="Category name" required></div>
                        <div class="col-12"><textarea name="description" class="form-control" rows="3" placeholder="Description"></textarea></div>
                        <div class="col-12 d-grid"><button class="btn btn-success">Add category</button></div>
                    </form>
                </div>
            </div>

            <div class="card form-card mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-3">Add equipment type</h3>
                    <form method="POST" action="{{ route('settings.equipment-types.store') }}" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <select name="equipment_category_id" class="form-select" required>
                                <option value="">Select category</option>
                                @foreach ($equipmentCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12"><input name="name" class="form-control" placeholder="Type name" required></div>
                        <div class="col-12"><textarea name="description" class="form-control" rows="3" placeholder="Description"></textarea></div>
                        <div class="col-12 d-grid"><button class="btn btn-success">Add type</button></div>
                    </form>
                </div>
            </div>

            <div class="card table-card">
                <div class="card-body">
                    <h3 class="h5 mb-3">Equipment categories and types</h3>
                    <ul class="list-group list-group-flush">
                        @foreach ($equipmentCategories as $category)
                            <li class="list-group-item px-0">
                                <div class="fw-semibold">{{ $category->name }}</div>
                                <small class="text-muted d-block mb-2">{{ $category->description }}</small>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($category->types as $type)
                                        <span class="badge text-bg-light border">{{ $type->name }}</span>
                                    @endforeach
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
