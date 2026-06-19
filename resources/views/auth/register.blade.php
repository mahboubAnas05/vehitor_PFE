@extends('layouts.app')

@section('title', 'Inscription - Vehitor')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5 mb-5">
        <div class="col-md-7">
            <div class="card-vehitor p-4">
                <h3 class="text-center mb-4" style="color: var(--royal-blue-dark);">Créer un compte</h3>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.submit') }}">
                    @csrf

                    {{-- ----- choose role ----- --}}
                    <div class="mb-3">
                        <label class="form-label">Je suis :</label>
                        <select name="role" id="role" class="form-select" required onchange="toggleAgencyFields()">
                            <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client (je veux louer une voiture)</option>
                            <option value="agency" {{ old('role') == 'agency' ? 'selected' : '' }}>Agence (je veux louer mes voitures)</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom complet</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    {{-- ----- these fields only appear/are required if role = agency -----
                         we use plain JavaScript to show/hide them, simple and easy to understand --}}
                    <div id="agencyFields" style="display:none;">
                        <hr>
                        <h6 class="mb-3">Informations de l'agence</h6>

                        <div class="mb-3">
                            <label class="form-label">Nom de l'agence</label>
                            <input type="text" name="agency_name" class="form-control" value="{{ old('agency_name') }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Adresse</label>
                                <input type="text" name="agency_address" class="form-control" value="{{ old('agency_address') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ville</label>
                                <input type="text" name="agency_city" class="form-control" value="{{ old('agency_city') }}">
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-info-circle"></i>
                            Votre compte agence devra être approuvé par l'administrateur avant de pouvoir publier des voitures.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-vehitor w-100 mt-2">S'inscrire</button>
                </form>

                <p class="text-center mt-3">
                    Déjà un compte ? <a href="{{ route('login') }}">Connectez-vous</a>
                </p>
            </div>
        </div>
    </div>
</div>

{{-- simple JS to show agency fields only when "Agence" is selected --}}
<script>
    function toggleAgencyFields() {
        const role = document.getElementById('role').value;
        const agencyFields = document.getElementById('agencyFields');
        agencyFields.style.display = (role === 'agency') ? 'block' : 'none';
    }
    // run once on page load too, in case the form was refilled after a validation error
    toggleAgencyFields();
</script>
@endsection
