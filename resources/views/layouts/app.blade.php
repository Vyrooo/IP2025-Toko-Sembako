<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sistem Manajemen Toko Sembako') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --main-green: #03ac0e;
            --main-green-dark: #028a0b;
        }

        body.bg-main-light {
            background-color: #f3faf5;
        }

        .navbar-main {
            background: linear-gradient(90deg, var(--main-green) 0%, #0bb26e 100%);
        }

        .navbar-main .nav-link.active,
        .navbar-main .nav-link:focus,
        .navbar-main .nav-link:hover {
            color: #fff;
            position: relative;
        }

        .navbar-main .nav-link.active::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 2px;
            background-color: #ffffff;
            border-radius: 999px;
        }

        .card {
            border-radius: 0.75rem;
        }

        .btn-primary {
            background-color: var(--main-green);
            border-color: var(--main-green);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: var(--main-green-dark);
            border-color: var(--main-green-dark);
        }

        @media (min-width: 992px) {
            .navbar-collapse.collapse,
            .navbar-collapse.collapse.show,
            #mainNavbar.navbar-collapse,
            #mainNavbar.navbar-collapse.collapse {
                display: flex !important;
                visibility: visible !important;
            }
        }

        .navbar-main .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .navbar-main .nav-link:hover,
        .navbar-main .nav-link:focus {
            color: #fff !important;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-main-light">
    @php
        $user = auth()->user();
    @endphp
    <nav class="navbar navbar-expand-lg navbar-dark navbar-main shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-semibold mb-0" href="#">Toko Sembako 350</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @if($user && $user->hasRole('kasir'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('kasir.pos') ? 'active' : '' }}" href="{{ route('kasir.pos') }}">POS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('kasir.transactions.*') ? 'active' : '' }}" href="{{ route('kasir.transactions.index') }}">Transaksi</a>
                        </li>
                    @endif

                    @if($user && $user->hasRole('admin'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="masterDataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Master Data
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="masterDataDropdown">
                                <li><a class="dropdown-item" href="{{ route('admin.products.index') }}">Barang</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.categories.index') }}">Kategori</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Pengguna</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.stock-in.index') }}">Barang Masuk</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">Laporan</a>
                        </li>
                    @endif

                    @if($user && $user->hasRole('owner'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}" href="{{ route('owner.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('owner.reports.*') ? 'active' : '' }}" href="{{ route('owner.reports.index') }}">Laporan</a>
                        </li>
                    @endif
                </ul>
                @if($user)
                <div class="d-flex align-items-center gap-3 ms-lg-auto">
                    <div class="text-white text-end d-none d-md-block">
                        <div class="small text-uppercase">{{ $user->role ?? '' }}</div>
                        <div class="fw-semibold">{{ $user->name ?? '' }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </nav>

<main class="container py-4">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{ $slot }}
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>