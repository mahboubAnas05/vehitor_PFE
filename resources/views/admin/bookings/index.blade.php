@extends('layouts.dashboard')

@section('title', 'Toutes les réservations - Vehitor')

@section('content')
<h2 class="mb-4" style="color: var(--royal-blue-dark);">Toutes les réservations</h2>

<div class="card-vehitor p-3">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Client</th>
                <th>Voiture</th>
                <th>Agence</th>
                <th>Du</th>
                <th>Au</th>
                <th>Prix total</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->client->name }}</td>
                    <td>{{ $booking->car->brand }} {{ $booking->car->model }}</td>
                    <td>{{ $booking->car->agency->name }}</td>
                    <td>{{ $booking->start_date->format('d/m/Y') }}</td>
                    <td>{{ $booking->end_date->format('d/m/Y') }}</td>
                    <td>{{ number_format($booking->total_price, 2) }} MAD</td>
                    <td><span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-muted text-center">Aucune réservation.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $bookings->links() }}
</div>
@endsection
