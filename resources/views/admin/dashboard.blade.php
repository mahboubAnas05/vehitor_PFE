@extends('layouts.dashboard')

@section('title', 'Tableau de bord admin - Vehitor')

@section('content')
<h2 class="mb-4" style="color: var(--royal-blue-dark);">Tableau de bord administrateur</h2>

{{-- ===== STAT BOXES ===== --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-box">
            <p class="text-muted mb-1"><i class="bi bi-building"></i> Total agences</p>
            <h3>{{ $totalAgencies }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-box">
            <p class="text-muted mb-1"><i class="bi bi-hourglass-split"></i> Agences en attente</p>
            <h3>{{ $pendingAgencies }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-box">
            <p class="text-muted mb-1"><i class="bi bi-people"></i> Total clients</p>
            <h3>{{ $totalClients }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-box">
            <p class="text-muted mb-1"><i class="bi bi-car-front"></i> Total voitures</p>
            <h3>{{ $totalCars }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-box">
            <p class="text-muted mb-1"><i class="bi bi-calendar-check"></i> Total réservations</p>
            <h3>{{ $totalBookings }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-box">
            <p class="text-muted mb-1"><i class="bi bi-cash-coin"></i> Revenu total</p>
            <h3>{{ number_format($totalRevenue, 2) }} MAD</h3>
        </div>
    </div>
</div>

{{-- ===== PENDING AGENCIES TO-DO LIST ===== --}}
<div class="card-vehitor p-3">
    <h5 class="mb-3">Agences en attente d'approbation</h5>

    <table class="table align-middle">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Ville</th>
                <th>Date d'inscription</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentPendingAgencies as $agency)
                <tr>
                    <td>{{ $agency->name }}</td>
                    <td>{{ $agency->city }}</td>
                    <td>{{ $agency->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.agencies.index') }}" class="btn btn-sm btn-vehitor-outline">Voir</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-muted text-center">Aucune agence en attente. 🎉</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
