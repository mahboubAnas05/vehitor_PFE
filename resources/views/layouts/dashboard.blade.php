<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vehitor - Tableau de bord')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="{{ asset('css/vehitor.css') }}" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- ===== SIDEBAR ===== -->
        <div class="col-md-3 col-lg-2 px-0 sidebar-vehitor">
            <h5 class="text-center text-white py-3 border-bottom border-secondary">
                <img src="{{ asset('images/V.png') }}" width="50" style="border-radius: 50%" alt="">
                Vehitor
            </h5>

            {{-- the sidebar links change depending on who is logged in --}}
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Tableau de bord
                </a>
                <a href="{{ route('admin.agencies.index') }}" class="{{ request()->routeIs('admin.agencies.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i> Agences
                </a>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Utilisateurs
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> Réservations
                </a>
            @elseif(auth()->user()->isAgency())
                <a href="{{ route('agency.dashboard') }}" class="{{ request()->routeIs('agency.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Tableau de bord
                </a>
                <a href="{{ route('agency.cars.index') }}" class="{{ request()->routeIs('agency.cars.*') ? 'active' : '' }}">
                    <i class="bi bi-car-front"></i> Mes voitures
                </a>
                <a href="{{ route('agency.bookings.index') }}" class="{{ request()->routeIs('agency.bookings.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> Réservations
                </a>
            @else
                <a href="{{ route('client.dashboard') }}" class="{{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Tableau de bord
                </a>
                <a href="{{ route('cars.index') }}">
                    <i class="bi bi-search"></i> Chercher une voiture
                </a>
                <a href="{{ route('client.bookings.index') }}" class="{{ request()->routeIs('client.bookings.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> Mes réservations
                </a>
            @endif

            <a href="{{ route('home') }}">
                <i class="bi bi-house"></i> Retour au site
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="border-0 bg-transparent w-100 text-start" style="padding: 12px 20px; color: #f5f5f0;">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </button>
            </form>
        </div>

        <!-- ===== MAIN CONTENT ===== -->
        <div class="col-md-9 col-lg-10 p-4">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
