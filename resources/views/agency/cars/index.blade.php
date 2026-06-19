@extends('layouts.dashboard')

@section('title', 'Mes voitures - Vehitor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 style="color: var(--royal-blue-dark);">Mes voitures</h2>
    <a href="{{ route('agency.cars.create') }}" class="btn btn-vehitor">
        <i class="bi bi-plus-circle"></i> Ajouter une voiture
    </a>
</div>

<div class="card-vehitor p-3">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Voiture</th>
                <th>Prix / jour</th>
                <th>Disponible</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cars as $car)
                <tr>
                    <td>
                        <img src="{{ $car->image ? asset('storage/'.$car->image) : 'https://via.placeholder.com/80x50?text=Vehitor' }}"
                             width="80" class="rounded">
                    </td>
                    <td>{{ $car->brand }} {{ $car->model }} ({{ $car->year }})</td>
                    <td>{{ number_format($car->price_per_day, 2) }} MAD</td>
                    <td>
                        @if($car->is_available)
                            <span class="badge badge-approved">Oui</span>
                        @else
                            <span class="badge badge-rejected">Non</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('agency.cars.edit', $car) }}" class="btn btn-sm btn-vehitor-outline">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        <form method="POST" action="{{ route('agency.cars.destroy', $car) }}" class="d-inline"
                              onsubmit="return confirm('Supprimer cette voiture ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-muted text-center">Vous n'avez pas encore ajouté de voiture.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $cars->links() }}
</div>
@endsection
