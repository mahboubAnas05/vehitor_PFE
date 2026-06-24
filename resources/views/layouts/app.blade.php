<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vehitor')</title>

    <!-- Bootstrap CSS (CDN, easy for beginners, no npm build needed) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Our custom theme -->
    <link href="{{ asset('css/vehitor.css') }}" rel="stylesheet">
</head>
<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg navbar-vehitor">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                {{-- <i class="bi bi-car-front-fill"></i> Vehitor --}}
                <img src="{{ asset('images/V.png') }}" width="50" alt="">
                Vehitor
            </a>

            <button class="border border-light navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                {{-- <span class="navbar-toggler-icon"></span> --}}
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#eee"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cars.index') }}">Voitures</a>
                    </li>

                    {{-- if no one is logged in, show login/register links --}}
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Inscription</a>
                        </li>
                    @endguest

                    {{-- if someone is logged in --}}
                    @auth
                        <li class="nav-item">
                            {{-- send each role to their own dashboard --}}
                            @if(auth()->user()->isAdmin())
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Mon Espace</a>
                            @elseif(auth()->user()->isAgency())
                                <a class="nav-link" href="{{ route('agency.dashboard') }}">Mon Espace</a>
                            @else
                                <a class="nav-link" href="{{ route('client.dashboard') }}">Mon Espace</a>
                            @endif
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link border-0 bg-transparent">Déconnexion</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- ===== FLASH MESSAGES (success/error alerts) ===== -->
    <div class="container mt-1">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- ===== PAGE CONTENT ===== -->
    <main>
        @yield('content')
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="footer-vehitor text-center">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Vehitor - Les Droits Sont Reservés</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
