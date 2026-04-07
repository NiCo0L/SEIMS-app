@php($title = 'Edit Supply Item')
@extends('layouts.app')

@section('content')
    <div class="card form-card">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1">Edit supply item</h2>
                    <p class="text-muted mb-0">Update the stock profile for {{ $supply->name }}.</p>
                </div>
                <a href="{{ route('supplies.show', $supply) }}" class="btn btn-outline-secondary">Back</a>
            </div>
            <form method="POST" action="{{ route('supplies.update', $supply) }}">
                @csrf
                @method('PUT')
                @include('supplies._form')
                <div class="mt-4">
                    <button class="btn btn-success">Update supply item</button>
                </div>
            </form>
        </div>
    </div>
@endsection
