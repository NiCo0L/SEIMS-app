<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SEIMS' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(180deg, #f4f7f2 0%, #eef3f7 100%); min-height: 100vh; }
        .brand-panel { background: linear-gradient(135deg, #274c2f, #4e7a36); color: #fff; border-radius: 1rem; }
        .metric-card { border: 0; border-radius: 1rem; box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.06); }
        .table-card, .form-card { border: 0; border-radius: 1rem; box-shadow: 0 .75rem 2rem rgba(0, 0, 0, 0.05); }
        .nav-pills .nav-link.active { background-color: #355f2e; }
        .status-badge { font-size: .78rem; }
    </style>
</head>
<body>
    <div class="container py-4">
        @auth
            <div class="brand-panel p-4 mb-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center">
                    <div>
                        <h1 class="h3 mb-1">Supply and Equipment Inventory Management System</h1>
                        <p class="mb-0 text-white-50">Centralized monitoring of agricultural supplies and equipment records.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm">Dashboard</a>
                        <a href="{{ route('supplies.index') }}" class="btn btn-outline-light btn-sm">Supplies</a>
                        <a href="{{ route('equipment.index') }}" class="btn btn-outline-light btn-sm">Equipment</a>
                        <a href="{{ route('settings.index') }}" class="btn btn-outline-light btn-sm">Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-warning btn-sm">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        @endauth

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please resolve the following:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
