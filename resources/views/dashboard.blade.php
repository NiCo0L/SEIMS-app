@php($title = 'SEIMS Dashboard')
@extends('layouts.app')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Supply categories</p>
                    <h2>{{ $supplyCategoryCount }}</h2>
                    <p class="mb-0">Tracked stock groups for agricultural consumables.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Total supply items</p>
                    <h2>{{ $totalSupplyItems }}</h2>
                    <p class="mb-0">Live inventory records across all configured warehouses.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card metric-card h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Equipment categories</p>
                    <h2>{{ $equipmentCategoryCount }}</h2>
                    <p class="mb-0">Structured types for field, irrigation, and workshop assets.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card table-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h5 mb-0">Low stock items</h3>
                        <a href="{{ route('supplies.index') }}" class="btn btn-outline-success btn-sm">Open supplies</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Warehouse</th>
                                    <th>Available</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lowStockItems as $item)
                                    <tr>
                                        <td><a href="{{ route('supplies.show', $item) }}">{{ $item->name }}</a></td>
                                        <td>{{ $item->category->name }}</td>
                                        <td>{{ $item->warehouse->name }}</td>
                                        <td>{{ number_format($item->current_quantity, 2) }} {{ $item->unit->symbol }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">No low-stock items detected.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card table-card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h5 mb-0">Equipment status summary</h3>
                        <a href="{{ route('equipment.index') }}" class="btn btn-outline-success btn-sm">Open equipment</a>
                    </div>
                    <div class="row g-3">
                        @foreach (App\Models\EquipmentItem::statusOptions() as $status => $label)
                            <div class="col-6">
                                <div class="border rounded p-3 h-100">
                                    <p class="text-muted small mb-1">{{ $label }}</p>
                                    <h4 class="mb-0">{{ $equipmentStatusSummary[$status] ?? 0 }}</h4>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card table-card">
                <div class="card-body">
                    <h3 class="h5 mb-3">Quick actions</h3>
                    <div class="d-grid gap-2">
                        <a href="{{ route('supplies.create') }}" class="btn btn-success">Add supply stock item</a>
                        <a href="{{ route('equipment.create') }}" class="btn btn-outline-success">Register equipment</a>
                        <a href="{{ route('supplies.export') }}" class="btn btn-outline-secondary">Export supply transactions</a>
                        <a href="{{ route('equipment.export') }}" class="btn btn-outline-secondary">Export equipment records</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
