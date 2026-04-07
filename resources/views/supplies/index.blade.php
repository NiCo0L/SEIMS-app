@php($title = 'Supply Inventory')
@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h2 class="h4 mb-1">Supply Inventory System</h2>
            <p class="text-muted mb-0">Manage consumable stocks, warehouses, and supply transaction history.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('supplies.export', request()->query()) }}" class="btn btn-outline-secondary">Export CSV</a>
            <a href="{{ route('supplies.create') }}" class="btn btn-success">Add supply item</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card form-card mb-4">
                <div class="card-body">
                    <h3 class="h6 mb-3">Filters</h3>
                    <form method="GET" class="row g-3">
                        <div class="col-12">
                            <input type="text" name="search" class="form-control" placeholder="Search item, doc no., person" value="{{ $filters['search'] ?? '' }}">
                        </div>
                        <div class="col-12">
                            <select name="warehouse_id" class="form-select">
                                <option value="">All warehouses</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" @selected(($filters['warehouse_id'] ?? '') == $warehouse->id)>{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                        </div>
                        <div class="col-12">
                            <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                        </div>
                        <div class="col-12 d-grid">
                            <button class="btn btn-success">Apply filters</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card table-card">
                <div class="card-body">
                    <h3 class="h6 mb-3">Supplies by category</h3>
                    <ul class="list-group list-group-flush">
                        @foreach ($categorySummary as $category)
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>{{ $category->name }}</span>
                                <strong>{{ $category->supply_items_count }}</strong>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card table-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Warehouse</th>
                                    <th>Stock</th>
                                    <th>Threshold</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($supplies as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $item->name }}</div>
                                            <small class="text-muted">Doc #: {{ $item->document_number }}</small>
                                        </td>
                                        <td>{{ $item->category->name }}</td>
                                        <td>{{ $item->warehouse->name }}</td>
                                        <td>{{ number_format($item->current_quantity, 2) }} {{ $item->unit->symbol }}</td>
                                        <td>{{ number_format($item->minimum_quantity, 2) }} {{ $item->unit->symbol }}</td>
                                        <td class="text-end"><a href="{{ route('supplies.show', $item) }}" class="btn btn-sm btn-outline-success">Open</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-5 text-muted">No supply records match the current filters.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $supplies->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
