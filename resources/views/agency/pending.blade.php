@extends('layouts.app')

@section('title', 'En attente d\'approbation - Vehitor')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 text-center">
            <div class="card-vehitor p-5">
                <i class="bi bi-hourglass-split" style="font-size: 3rem; color: var(--royal-blue);"></i>
                <h3 class="mt-3">Compte en attente d'approbation</h3>
                <p class="text-muted">
                    Merci de votre inscription ! Notre équipe est en train de vérifier les informations de votre agence.
                    Vous recevrez un accès complet dès que votre compte sera approuvé.
                </p>
                <a href="{{ route('home') }}" class="btn btn-vehitor mt-3">Retour à l'accueil</a>
            </div>
        </div>
    </div>
</div>
@endsection
