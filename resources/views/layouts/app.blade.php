<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Inventory Management'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f6fb;
        }

        .navbar-brand {
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .metric-card .metric-label {
            font-size: 0.85rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">Inventory IMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">Sales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('ledger.index') ? 'active' : '' }}" href="{{ route('ledger.index') }}">Ledger</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.index') ? 'active' : '' }}" href="{{ route('report.index') }}">Report</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Validation Error:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
