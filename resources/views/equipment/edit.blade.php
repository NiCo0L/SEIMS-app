@php($title = 'Edit Equipment')
@extends('layouts.app')

@section('content')
    <div class="card form-card">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1">Edit equipment</h2>
                    <p class="text-muted mb-0">Update record details for {{ $equipment->name }}.</p>
                </div>
                <a href="{{ route('equipment.show', $equipment) }}" class="btn btn-outline-secondary">Back</a>
            </div>
            <form method="POST" action="{{ route('equipment.update', $equipment) }}">
                @csrf
                @method('PUT')
                @include('equipment._form')
                <div class="mt-4">
                    <button class="btn btn-success">Update equipment</button>
                </div>
            </form>
        </div>
    </div>
@endsection
