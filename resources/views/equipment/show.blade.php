@php($title = 'Equipment Details')
@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h2 class="h4 mb-1">{{ $equipment->name }}</h2>
            <p class="text-muted mb-0">{{ $equipment->category->name }} / {{ $equipment->type->name }} | Doc #: {{ $equipment->document_number }} | Ctrl #: {{ $equipment->control_number }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('equipment.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card metric-card mb-4">
                <div class="card-body">
                    <p class="text-muted mb-2">Current status</p>
                    <h3>{{ $statusOptions[$equipment->status] }}</h3>
                    <p class="mb-0">Person in charge: {{ $equipment->person_in_charge }}</p>
                </div>
            </div>

            <div class="card form-card mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-3">Record status change</h3>
                    <form method="POST" action="{{ route('equipment.status', $equipment) }}" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <select name="status" class="form-select" required>
                                @foreach ($statusOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <input type="date" name="status_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-12">
                            <input type="text" name="document_number" class="form-control" placeholder="Document number" required>
                        </div>
                        <div class="col-12">
                            <textarea name="remarks" class="form-control" rows="3" placeholder="Remarks"></textarea>
                        </div>
                        <div class="col-12 d-grid">
                            <button class="btn btn-success">Save status update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card form-card">
                <div class="card-body">
                    <h3 class="h5 mb-3">Upload document</h3>
                    <form method="POST" action="{{ route('equipment.document', $equipment) }}" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <input type="text" name="document_type" class="form-control" placeholder="Document type" required>
                        </div>
                        <div class="col-12">
                            <input type="text" name="document_number" class="form-control" placeholder="Document number">
                        </div>
                        <div class="col-12">
                            <input type="file" name="attachment" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <textarea name="remarks" class="form-control" rows="3" placeholder="Remarks"></textarea>
                        </div>
                        <div class="col-12 d-grid">
                            <button class="btn btn-outline-success">Upload file</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card table-card mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-3">Status history</h3>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Document</th>
                                    <th>Remarks</th>
                                    <th>Recorded by</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($statusLogs as $log)
                                    <tr>
                                        <td>{{ $log->status_date?->format('M d, Y') }}</td>
                                        <td>{{ $statusOptions[$log->status] }}</td>
                                        <td>{{ $log->document_number }}</td>
                                        <td>{{ $log->remarks }}</td>
                                        <td>
                                            <div>{{ $log->user?->name ?? 'System' }}</div>
                                            <small class="text-muted">{{ $log->created_at->format('M d, Y h:i A') }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-5 text-muted">No status history recorded yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $statusLogs->links() }}
                </div>
            </div>

            <div class="card table-card">
                <div class="card-body">
                    <h3 class="h5 mb-3">Uploaded documents</h3>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Document no.</th>
                                    <th>File</th>
                                    <th>Uploaded by</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($documents as $document)
                                    <tr>
                                        <td>{{ $document->document_type }}</td>
                                        <td>{{ $document->document_number ?: 'N/A' }}</td>
                                        <td>
                                            <div>{{ $document->original_name }}</div>
                                            <small class="text-muted">{{ $document->remarks }}</small>
                                        </td>
                                        <td>{{ $document->user?->name ?? 'System' }}</td>
                                        <td class="text-end"><a href="{{ route('equipment.document.download', $document) }}" class="btn btn-sm btn-outline-secondary">Download</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-5 text-muted">No documents uploaded yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
