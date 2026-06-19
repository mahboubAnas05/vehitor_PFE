@extends('layouts.app')

@section('title', $car->brand.' '.$car->model.' - Vehitor')

@section('content')
<div class="container my-5">

    <div class="row g-4">
        {{-- ===== LEFT: CAR INFO ===== --}}
        <div class="col-md-7">
            <img src="{{ $car->image ? asset('storage/'.$car->image) : 'https://via.placeholder.com/700x350?text=Vehitor' }}"
                 class="img-fluid rounded mb-3" alt="{{ $car->brand }} {{ $car->model }}">

            <h2>{{ $car->brand }} {{ $car->model }} ({{ $car->year }})</h2>
            <p class="text-muted">
                <i class="bi bi-building"></i> {{ $car->agency->name }} - {{ $car->agency->city }}
            </p>

            <div class="d-flex gap-4 my-3">
                <span><i class="bi bi-gear"></i> {{ ucfirst($car->transmission) }}</span>
                <span><i class="bi bi-fuel-pump"></i> {{ ucfirst($car->fuel_type) }}</span>
                <span><i class="bi bi-people"></i> {{ $car->seats }} places</span>
                <span><i class="bi bi-star-fill text-warning"></i> {{ $car->averageRating() }}/5</span>
            </div>

            <p>{{ $car->description }}</p>

            {{-- ===== REVIEWS LIST ===== --}}
            <hr>
            <h4>Avis clients ({{ $car->reviews->count() }})</h4>

            @forelse($car->reviews as $review)
                <div class="border-bottom py-2">
                    <strong>{{ $review->client->name }}</strong>
                    <span class="text-warning">
                        {{-- print stars based on the rating number, 1 to 5 --}}
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <i class="bi bi-star-fill"></i>
                            @else
                                <i class="bi bi-star"></i>
                            @endif
                        @endfor
                    </span>
                    <p class="mb-0 text-muted">{{ $review->comment }}</p>
                </div>
            @empty
                <p class="text-muted">Aucun avis pour le moment.</p>
            @endforelse
        </div>

        {{-- ===== RIGHT: BOOKING FORM ===== --}}
        <div class="col-md-5">
            <div class="card-vehitor p-4">
                <h4 style="color: var(--royal-blue-dark);">
                    {{ number_format($car->price_per_day, 2) }} MAD <span class="fs-6 text-muted">/ jour</span>
                </h4>

                @auth
                    @if(auth()->user()->isClient())
                        {{-- only logged-in CLIENTS can book --}}
                        <form method="POST" action="{{ route('client.bookings.store', $car) }}" class="mt-3">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Date de début</label>
                                <input type="date" name="start_date" class="form-control" required min="{{ date('Y-m-d') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Date de fin</label>
                                <input type="date" name="end_date" class="form-control" required min="{{ date('Y-m-d') }}">
                            </div>

                            <button type="submit" class="btn btn-vehitor w-100">Réserver maintenant</button>
                        </form>
                    @else
                        <div class="alert alert-info mt-3">
                            Seuls les clients peuvent réserver une voiture.
                        </div>
                    @endif
                @else
                    {{-- not logged in --}}
                    <a href="{{ route('login') }}" class="btn btn-vehitor w-100 mt-3">
                        Connectez-vous pour réserver
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
