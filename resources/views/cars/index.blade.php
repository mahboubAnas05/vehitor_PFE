@extends('layouts.app')

@section('title', 'Nos voitures - Vehitor')

@section('content')
<div class="container my-5">
    <h2 class="mb-4" style="color: var(--royal-blue-dark);">Nos voitures</h2>

    {{-- ===== FILTER FORM ===== --}}
    <form method="GET" action="{{ route('cars.index') }}" class="card-vehitor p-3 mb-4">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Marque ou modèle"
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="city" class="form-control" placeholder="Ville"
                       value="{{ request('city') }}">
            </div>
            <div class="col-md-3">
                <select name="transmission" class="form-select">
                    <option value="">Toutes transmissions</option>
                    <option value="manuelle" {{ request('transmission') == 'manuelle' ? 'selected' : '' }}>Manuelle</option>
                    <option value="automatique" {{ request('transmission') == 'automatique' ? 'selected' : '' }}>Automatique</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-vehitor w-100">Filtrer</button>
            </div>
        </div>
    </form>

    {{-- ===== RESULTS ===== --}}
    <div class="row g-4">
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
                        <p class="card-text mb-1">
                            <i class="bi bi-gear"></i> {{ ucfirst($car->transmission) }} ·
                            <i class="bi bi-fuel-pump"></i> {{ ucfirst($car->fuel_type) }} ·
                            <i class="bi bi-people"></i> {{ $car->seats }} places
                        </p>
                        <p class="card-text fw-bold" style="color: var(--royal-blue);">
                            {{ number_format($car->price_per_day, 2) }} MAD / jour
                        </p>
                        <a href="{{ route('cars.show', $car) }}" class="btn btn-vehitor-outline w-100">Voir détails</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted text-center">Aucune voiture ne correspond à votre recherche.</p>
            </div>
        @endforelse
    </div>

    {{-- ===== PAGINATION LINKS (Laravel generates these automatically) ===== --}}
    <div class="mt-4">
        {{ $cars->links() }}
    </div>
</div>
@endsection
