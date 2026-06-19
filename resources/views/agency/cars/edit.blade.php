@extends('layouts.dashboard')

@section('title', 'Modifier la voiture - Vehitor')

@section('content')
<h2 class="mb-4" style="color: var(--royal-blue-dark);">Modifier {{ $car->brand }} {{ $car->model }}</h2>

<div class="card-vehitor p-4">
    <form method="POST" action="{{ route('agency.cars.update', $car) }}" enctype="multipart/form-data">
        @include('agency.cars._form')
    </form>
</div>
@endsection
