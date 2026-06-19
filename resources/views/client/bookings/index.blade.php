@extends('layouts.dashboard')

@section('title', 'Mes réservations - Vehitor')

@section('content')
<h2 class="mb-4" style="color: var(--royal-blue-dark);">Mes réservations</h2>

<div class="card-vehitor p-3">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Voiture</th>
                <th>Agence</th>
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
                    <td>{{ $booking->car->brand }} {{ $booking->car->model }}</td>
                    <td>{{ $booking->car->agency->name }}</td>
                    <td>{{ $booking->start_date->format('d/m/Y') }}</td>
                    <td>{{ $booking->end_date->format('d/m/Y') }}</td>
                    <td>{{ number_format($booking->total_price, 2) }} MAD</td>
                    <td><span class="badge badge-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span></td>
                    <td>
                        {{-- only allow cancelling if it's still pending or confirmed --}}
                        @if(in_array($booking->status, ['pending', 'confirmed']))
                            <form method="POST" action="{{ route('client.bookings.cancel', $booking) }}"
                                  onsubmit="return confirm('Annuler cette réservation ?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Annuler</button>
                            </form>
                        @endif

                        {{-- if the booking is completed, allow leaving a review --}}
                        @if($booking->status === 'completed')
                            <button type="button" class="btn btn-sm btn-vehitor-outline" data-bs-toggle="modal"
                                    data-bs-target="#reviewModal{{ $booking->id }}">
                                Laisser un avis
                            </button>

                            {{-- ===== REVIEW MODAL ===== --}}
                            <div class="modal fade" id="reviewModal{{ $booking->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('client.reviews.store', $booking->car) }}">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Avis sur {{ $booking->car->brand }} {{ $booking->car->model }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label class="form-label">Note (1 à 5)</label>
                                                <select name="rating" class="form-select mb-3" required>
                                                    <option value="5">5 - Excellent</option>
                                                    <option value="4">4 - Très bien</option>
                                                    <option value="3">3 - Bien</option>
                                                    <option value="2">2 - Moyen</option>
                                                    <option value="1">1 - Mauvais</option>
                                                </select>

                                                <label class="form-label">Commentaire</label>
                                                <textarea name="comment" class="form-control" rows="3"></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-vehitor">Envoyer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </td>
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
