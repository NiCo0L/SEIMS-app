@php($title = 'SEIMS Login')
@extends('layouts.app')

@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: 75vh;">
        <div class="col-lg-5">
            <div class="brand-panel p-5 mb-4">
                <p class="text-uppercase small mb-2">SEIMS</p>
                <h2 class="mb-3">Private agricultural inventory control</h2>
                <p class="mb-0 text-white-50">Track consumable supplies, equipment status, transaction histories, and reports from a single Laravel-based system.</p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card form-card">
                <div class="card-body p-4">
                    <h3 class="h4 mb-3">Sign in</h3>
                    <p class="text-muted">Default seeded account: <strong>admin@seims.local</strong> / <strong>password</strong></p>
                    <form method="POST" action="{{ route('login.store') }}" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', 'admin@seims.local') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" value="password" required>
                        </div>
                        <div class="col-12 form-check ms-1">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-success w-100">Login to SEIMS</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
