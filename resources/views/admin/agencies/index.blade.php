@extends('layouts.dashboard')

@section('title', 'Gestion des agences - Vehitor')

@section('content')
<h2 class="mb-4" style="color: var(--royal-blue-dark);">Gestion des agences</h2>

<div class="card-vehitor p-3">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Responsable</th>
                <th>Ville</th>
                <th>Email</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($agencies as $agency)
                <tr>
                    <td>{{ $agency->name }}</td>
                    <td>{{ $agency->user->name }}</td>
                    <td>{{ $agency->city }}</td>
                    <td>{{ $agency->user->email }}</td>
                    <td><span class="badge badge-{{ $agency->status }}">{{ ucfirst($agency->status) }}</span></td>
                    <td>
                        {{-- only show approve/reject buttons if not already in that state --}}
                        @if($agency->status !== 'approved')
                            <form method="POST" action="{{ route('admin.agencies.approve', $agency) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-vehitor">Approuver</button>
                            </form>
                        @endif

                        @if($agency->status !== 'rejected')
                            <form method="POST" action="{{ route('admin.agencies.reject', $agency) }}" class="d-inline"
                                  onsubmit="return confirm('Rejeter cette agence ?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Rejeter</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-muted text-center">Aucune agence inscrite.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $agencies->links() }}
</div>
@endsection
