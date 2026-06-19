@extends('layouts.app')

@section('title', 'Connexion - Vehitor')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card-vehitor p-4">
                <h3 class="text-center mb-4" style="color: var(--royal-blue-dark);">Connexion</h3>

                {{-- this @if shows validation errors if login failed --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf {{-- security token, Laravel requires this on every form --}}

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-vehitor w-100">Se connecter</button>
                </form>

                <p class="text-center mt-3">
                    Pas encore de compte ? <a href="{{ route('register') }}">Inscrivez-vous</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
