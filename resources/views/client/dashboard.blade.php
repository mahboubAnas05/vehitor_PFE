@extends('layouts.dashboard')

@section('title', 'Mon tableau de bord - Vehitor')

@section('content')
<h2 class="mb-4" style="color: var(--royal-blue-dark);">Bonjour, {{ auth()->user()->name }} 👋</h2>

{{-- ===== STAT BOXES ===== --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="stat-box">
            <p class="text-muted mb-1">Total réservations</p>
            <h3>{{ $totalBookings }}</h3>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-box">
            <p class="text-muted mb-1">Réservations actives</p>
            <h3>{{ $activeBookings }}</h3>
        </div>
    </div>
</div>

{{-- ===== RECENT BOOKINGS TABLE ===== --}}
<div class="card-vehitor p-3">
    <h5 class="mb-3">Réservations récentes</h5>

    <table class="table">
        <thead>
            <tr>
                <th>Voiture</th>
                <th>Du</th>
                <th>Au</th>
                <th>Prix</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentBookings as $booking)
                <tr>
                    <td>{{ $booking->car->brand }} {{ $booking->car->model }}</td>
                    <td>{{ $booking->start_date->format('d/m/Y') }}</td>
                    <td>{{ $booking->end_date->format('d/m/Y') }}</td>
                    <td>{{ number_format($booking->total_price, 2) }} MAD</td>
                    <td>
                        {{-- badge color changes depending on status, using the CSS classes we made --}}
                        <span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-muted text-center">Aucune réservation pour le moment.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('client.bookings.index') }}" class="btn btn-vehitor-outline">Voir toutes mes réservations</a>
</div>
@endsection
