@php($title = 'Register Equipment')
@extends('layouts.app')

@section('content')
    <div class="card form-card">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1">Register equipment</h2>
                    <p class="text-muted mb-0">Create a tracked equipment record with responsible personnel and control references.</p>
                </div>
                <a href="{{ route('equipment.index') }}" class="btn btn-outline-secondary">Back</a>
            </div>
            <form method="POST" action="{{ route('equipment.store') }}">
                @csrf
                @include('equipment._form')
                <div class="mt-4">
                    <button class="btn btn-success">Save equipment</button>
                </div>
            </form>
        </div>
    </div>
@endsection
