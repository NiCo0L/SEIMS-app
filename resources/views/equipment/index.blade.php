@php($title = 'Equipment Management')
@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h2 class="h4 mb-1">Equipment Management System</h2>
            <p class="text-muted mb-0">Track equipment records, control numbers, status changes, and supporting documents.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('equipment.export', request()->query()) }}" class="btn btn-outline-secondary">Export CSV</a>
            <a href="{{ route('equipment.create') }}" class="btn btn-success">Register equipment</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card form-card mb-4">
                <div class="card-body">
                    <h3 class="h6 mb-3">Filters</h3>
                    <form method="GET" class="row g-3">
                        <div class="col-12">
                            <input type="text" name="search" class="form-control" placeholder="Search name, doc no., person" value="{{ $filters['search'] ?? '' }}">
                        </div>
                        <div class="col-12">
                            <select name="status" class="form-select">
                                <option value="">All status</option>
                                @foreach ($statusOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(($filters['status'] ?? '') == $value)>{{ $label }}</option>
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
                    <h3 class="h6 mb-3">Equipment in category</h3>
                    <ul class="list-group list-group-flush">
                        @foreach ($categorySummary as $category)
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>{{ $category->name }}</span>
                                <strong>{{ $category->equipment_items_count }}</strong>
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
                                    <th>Equipment</th>
                                    <th>Category / Type</th>
                                    <th>Person in charge</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($equipment as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $item->name }}</div>
                                            <small class="text-muted">Doc #: {{ $item->document_number }} | Ctrl #: {{ $item->control_number }}</small>
                                        </td>
                                        <td>{{ $item->category->name }} / {{ $item->type->name }}</td>
                                        <td>{{ $item->person_in_charge }}</td>
                                        <td><span class="badge text-bg-secondary status-badge">{{ $statusOptions[$item->status] }}</span></td>
                                        <td class="text-end"><a href="{{ route('equipment.show', $item) }}" class="btn btn-sm btn-outline-success">Open</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-5 text-muted">No equipment records match the current filters.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $equipment->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
