@php($title = 'Supply Details')
@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h2 class="h4 mb-1">{{ $supply->name }}</h2>
            <p class="text-muted mb-0">{{ $supply->category->name }} | {{ $supply->warehouse->name }} | Doc #: {{ $supply->document_number }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('supplies.edit', $supply) }}" class="btn btn-outline-secondary">Edit</a>
            <a href="{{ route('supplies.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card metric-card mb-4">
                <div class="card-body">
                    <p class="text-muted mb-2">Current stock</p>
                    <h2>{{ number_format($supply->current_quantity, 2) }} {{ $supply->unit->symbol }}</h2>
                    <p class="mb-0">Minimum level: {{ number_format($supply->minimum_quantity, 2) }} {{ $supply->unit->symbol }}</p>
                </div>
            </div>
            <div class="card form-card">
                <div class="card-body">
                    <h3 class="h5 mb-3">Record stock movement</h3>
                    <form method="POST" action="{{ route('supplies.transaction', $supply) }}" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <select name="transaction_type" class="form-select" required>
                                <option value="in">Deposit / Stock In</option>
                                <option value="out">Withdrawal / Stock Out</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <input type="number" step="0.01" min="0.01" name="quantity" class="form-control" placeholder="Quantity" required>
                        </div>
                        <div class="col-12">
                            <input type="text" name="document_number" class="form-control" placeholder="Document number" required>
                        </div>
                        <div class="col-12">
                            <input type="date" name="reference_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-12">
                            <input type="text" name="recipient_name" class="form-control" placeholder="Recipient (for withdrawals)">
                        </div>
                        <div class="col-12">
                            <input type="text" name="person_in_charge" class="form-control" placeholder="Person in charge">
                        </div>
                        <div class="col-12">
                            <textarea name="remarks" class="form-control" rows="3" placeholder="Remarks"></textarea>
                        </div>
                        <div class="col-12 d-grid">
                            <button class="btn btn-success">Save movement</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card table-card">
                <div class="card-body">
                    <h3 class="h5 mb-3">Transaction history</h3>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Recipient / Person</th>
                                    <th>Document</th>
                                    <th>Recorded by</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->reference_date?->format('M d, Y') }}</td>
                                        <td>{{ strtoupper($transaction->transaction_type) }}</td>
                                        <td>{{ number_format($transaction->quantity, 2) }} {{ $supply->unit->symbol }}</td>
                                        <td>
                                            <div>{{ $transaction->recipient_name ?: 'N/A' }}</div>
                                            <small class="text-muted">{{ $transaction->person_in_charge ?: 'No person noted' }}</small>
                                        </td>
                                        <td>
                                            <div>{{ $transaction->document_number }}</div>
                                            <small class="text-muted">{{ $transaction->remarks }}</small>
                                        </td>
                                        <td>
                                            <div>{{ $transaction->user?->name ?? 'System' }}</div>
                                            <small class="text-muted">{{ $transaction->created_at->format('M d, Y h:i A') }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-5 text-muted">No stock movement has been recorded yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
