@extends('layouts.dashboard')

@section('title', 'Réservations - Vehitor')

@section('content')
<h2 class="mb-4" style="color: var(--royal-blue-dark);">Réservations sur mes voitures</h2>

<div class="card-vehitor p-3">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Client</th>
                <th>Voiture</th>
                <th>Du</th>
                <th>Au</th>
                <th>Prix total</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->client->name }}</td>
                    <td>{{ $booking->car->brand }} {{ $booking->car->model }}</td>
                    <td>{{ $booking->start_date->format('d/m/Y') }}</td>
                    <td>{{ $booking->end_date->format('d/m/Y') }}</td>
                    <td>{{ number_format($booking->total_price, 2) }} MAD</td>
                    <td><span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span></td>
                    <td>
                        {{-- only show confirm/cancel buttons if the booking is still pending --}}
                        @if($booking->status === 'pending')
                            <form method="POST" action="{{ route('agency.bookings.confirm', $booking) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-vehitor">Confirmer</button>
                            </form>
                            <form method="POST" action="{{ route('agency.bookings.cancel', $booking) }}" class="d-inline"
                                  onsubmit="return confirm('Refuser cette réservation ?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Refuser</button>
                            </form>
                        @endif
                        @if($booking->status === 'confirmed')
                            <form method="POST" action="{{ route('agency.bookings.complete', $booking) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success">✓ Terminer</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-muted text-center">Aucune réservation pour le moment.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $bookings->links() }}
</div>
@endsection
