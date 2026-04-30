<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>La Mise au Vert</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #2d6a4f;
            --light-green: #52b788;
            --accent-green: #40916c;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .modern-navbar {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--accent-green) 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .modern-navbar.scrolled {
            padding: 0.5rem 0;
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.15);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .navbar-brand i {
            font-size: 1.8rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1.2rem !important;
            margin: 0 0.2rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white !important;
            transform: translateY(-2px);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white !important;
        }

        .btn-logout {
            background: rgba(220, 53, 69, 0.2);
            border: 2px solid rgba(220, 53, 69, 0.5);
            color: white !important;
            padding: 0.5rem 1.2rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: #dc3545;
            border-color: #dc3545;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .btn-login {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.5);
            color: white !important;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: white;
            color: var(--primary-green) !important;
            border-color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
        }

        .navbar-toggler {
            border: 2px solid rgba(255, 255, 255, 0.5);
            padding: 0.5rem 0.75rem;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .user-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.4rem 1rem;
            border-radius: 20px;
            margin-right: 1rem;
            color: white;
            font-weight: 500;
        }

        @media (max-width: 991px) {
            .navbar-collapse {
                background: rgba(0, 0, 0, 0.1);
                padding: 1rem;
                border-radius: 8px;
                margin-top: 1rem;
            }

            .user-badge {
                margin: 0.5rem 0;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg modern-navbar">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-leaf"></i>
            La Mise au Vert
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                @auth
                    @if(auth()->user()->pension)
                        <!-- Navigation pour les pensions connectées -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pension.dashboard') }}">
                                <i class="fas fa-home me-1"></i> Accueil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pension.types-gardiennage') }}">
                                <i class="fas fa-home me-1"></i> Types d'hébergement
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pension.tarifs') }}">
                                <i class="fas fa-tag me-1"></i> Tarifs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pension.boxes') }}">
                                <i class="fas fa-cube me-1"></i> Boxes
                            </a>
                        </li>

                        <!-- ORAL -->

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pension.options') }}">
                                <i class="fas fa-list-check me-1"></i> Options
                            </a>
                        </li>

                        <!-- FIN ORAL -->

                    @else
                        <!-- Navigation pour les propriétaires d'animaux -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pensions') }}">
                                <i class="fas fa-home me-1"></i> Nos pensions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('services') }}">
                                <i class="fas fa-concierge-bell me-1"></i> Services
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('horaires') }}">
                                <i class="fas fa-clock me-1"></i> Horaires
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('contact') }}">
                                <i class="fas fa-envelope me-1"></i> Contact
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('animaux') }}">
                                <i class="fas fa-paw me-1"></i> Mes animaux
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('factures') }}">
                                <i class="fas fa-file-invoice me-1"></i> Vos factures
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('fiches') }}">
                                <i class="fas fa-file-alt me-1"></i> Vos fiches
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profil') }}">
                            <i class="fas fa-user-edit me-1"></i> Mon profil
                        </a>
                    </li>
                    <li class="nav-item d-none d-lg-block">
                        <span class="user-badge">
                            <i class="fas fa-user me-1"></i> {{ Auth::user()->name ?? 'Utilisateur' }}
                        </span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-logout">
                                <i class="fas fa-sign-out-alt me-1"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                @else
                    <!-- Navigation pour les visiteurs -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pensions') }}">
                            <i class="fas fa-home me-1"></i> Nos pensions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('services') }}">
                            <i class="fas fa-concierge-bell me-1"></i> Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('horaires') }}">
                            <i class="fas fa-clock me-1"></i> Horaires
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">
                            <i class="fas fa-envelope me-1"></i> Contact
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-login" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Connexion
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.modern-navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    const currentLocation = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentLocation) {
            link.classList.add('active');
        }
    });
</script>

@include('components.auth-script')
</body>
</html>
