@extends('layouts.app')

@section('title', 'Vehitor - Location de voitures entre agences et clients')

@section('content')

{{-- ===== HERO SECTION ===== --}}
<div class="hero-section text-center">
    <div class="container">
        <h1 class="display-4">Bienvenue sur Vehitor</h1>
        <p class="lead">La plateforme qui connecte les agences de location de voitures et les clients</p>

        {{-- quick search form, sends user to the cars list page with filters --}}
        <form action="{{ route('cars.index') }}" method="GET" class="mt-4">
            <div class="row justify-content-center g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Marque ou modèle (ex: Dacia)">
                </div>
                <div class="col-md-3">
                    <input type="text" name="city" class="form-control" placeholder="Ville (ex: Casablanca)">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-vehitor w-100">
                        <i class="bi bi-search"></i> Chercher
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ===== FEATURED CARS ===== --}}
<div class="container my-5">
    <h2 class="mb-4" style="color: var(--royal-blue-dark);">Voitures disponibles</h2>

    <div class="row g-4">
        {{-- @forelse is like @foreach but also handles the "empty" case --}}
        @forelse($cars as $car)
            <div class="col-md-4">
                <div class="card-vehitor h-100">
                    <img src="{{ $car->image ? asset('storage/'.$car->image) : 'https://via.placeholder.com/400x200?text=Vehitor' }}"
                         class="card-img-top" alt="{{ $car->brand }} {{ $car->model }}">

                    <div class="card-body p-3">
                        <h5 class="card-title">{{ $car->brand }} {{ $car->model }} ({{ $car->year }})</h5>
                        <p class="card-text text-muted mb-1">
                            <i class="bi bi-building"></i> {{ $car->agency->name }} - {{ $car->agency->city }}
                        </p>
                        <p class="card-text fw-bold" style="color: var(--royal-blue);">
                            {{ number_format($car->price_per_day, 2) }} MAD / jour
                        </p>
                        <a href="{{ route('cars.show', $car) }}" class="btn btn-vehitor-outline w-100">Voir détails</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Aucune voiture disponible pour le moment.</p>
        @endforelse
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('cars.index') }}" class="btn btn-vehitor">Voir toutes les voitures</a>
    </div>
</div>

{{-- ===== HOW IT WORKS ===== --}}
<div class="container my-5">
    <h2 class="text-center mb-4" style="color: var(--royal-blue-dark);">Comment ça marche ?</h2>
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <i class="bi bi-search" style="font-size: 2.5rem; color: var(--royal-blue);"></i>
            <h5 class="mt-3">1. Cherchez</h5>
            <p class="text-muted">Trouvez la voiture qui correspond à vos besoins parmi nos agences partenaires.</p>
        </div>
        <div class="col-md-4">
            <i class="bi bi-calendar-check" style="font-size: 2.5rem; color: var(--royal-blue);"></i>
            <h5 class="mt-3">2. Réservez</h5>
            <p class="text-muted">Choisissez vos dates et réservez votre voiture en quelques clics.</p>
        </div>
        <div class="col-md-4">
            <i class="bi bi-car-front" style="font-size: 2.5rem; color: var(--royal-blue);"></i>
            <h5 class="mt-3">3. Roulez</h5>
            <p class="text-muted">Récupérez votre voiture auprès de l'agence et profitez de votre trajet.</p>
        </div>
    </div>
</div>

@endsection
