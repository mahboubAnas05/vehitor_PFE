@extends('layouts.dashboard')

@section('title', 'Tableau de bord agence - Vehitor')

@section('content')
<h2 class="mb-4" style="color: var(--royal-blue-dark);">Bienvenue, {{ $agency->name }} 👋</h2>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-box">
            <p class="text-muted mb-1">Mes voitures</p>
            <h3>{{ $totalCars }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-box">
            <p class="text-muted mb-1">Total réservations</p>
            <h3>{{ $totalBookings }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-box">
            <p class="text-muted mb-1">Réservations en attente</p>
            <h3>{{ $pendingBookings }}</h3>
        </div>
    </div>
</div>

<div class="card-vehitor p-4">
    <h5>Actions rapides</h5>
    <a href="{{ route('agency.cars.create') }}" class="btn btn-vehitor me-2">
        <i class="bi bi-plus-circle"></i> Ajouter une voiture
    </a>
    <a href="{{ route('agency.bookings.index') }}" class="btn btn-vehitor-outline">
        <i class="bi bi-calendar-check"></i> Voir les réservations
    </a>
</div>
@endsection
