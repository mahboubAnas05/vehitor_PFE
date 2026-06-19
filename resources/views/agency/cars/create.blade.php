@extends('layouts.dashboard')

@section('title', 'Ajouter une voiture - Vehitor')

@section('content')
<h2 class="mb-4" style="color: var(--royal-blue-dark);">Ajouter une voiture</h2>

<div class="card-vehitor p-4">
    {{-- enctype="multipart/form-data" is REQUIRED whenever a form uploads a file --}}
    <form method="POST" action="{{ route('agency.cars.store') }}" enctype="multipart/form-data">
        @include('agency.cars._form')
    </form>
</div>
@endsection
