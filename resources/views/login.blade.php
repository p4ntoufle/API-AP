<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - La Mise au Vert</title>
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
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            min-height: 100vh;
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

        /* Login Form Styles */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 80px);
            padding: 2rem 1rem;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            padding: 3rem;
            min-width: 320px;
            max-width: 450px;
            width: 100%;
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header i {
            font-size: 3.5rem;
            color: var(--primary-green);
            margin-bottom: 1rem;
        }

        .login-header h3 {
            color: var(--primary-green);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.15);
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .input-icon .form-control {
            padding-left: 2.75rem;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--accent-green) 100%);
            border: none;
            color: white;
            padding: 0.9rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(45, 106, 79, 0.3);
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem;
            margin-bottom: 1.5rem;
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

            .login-card {
                padding: 2rem 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 2rem 1.25rem;
            }

            .login-header i {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
<!-- Navbar identique au layout principal -->
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
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pensions') }}">
                        <i class="fas fa-home me-1"></i> Nos pensions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact') }}">
                        <i class="fas fa-envelope me-1"></i> Contact
                    </a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('fiches') }}">
                            <i class="fas fa-file-alt me-1"></i> Vos fiches
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
                    <li class="nav-item">
                        <a class="btn btn-login active" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Connexion
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Formulaire de connexion modernisé -->
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-paw"></i>
            <h3>Connexion</h3>
            <p>Accédez à votre espace personnel</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Erreur :</strong> Identifiants incorrects
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-1"></i> Adresse e-mail
                </label>
                <div class="input-icon">
                    <i class="fas fa-at"></i>
                    <input type="email"
                           name="email"
                           id="email"
                           class="form-control"
                           placeholder="votre@email.com"
                           value="{{ old('email') }}"
                           required
                           autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">
                    <i class="fas fa-lock me-1"></i> Mot de passe
                </label>
                <div class="input-icon">
                    <i class="fas fa-key"></i>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="••••••••"
                           required>
                </div>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">
                    Se souvenir de moi
                </label>
            </div>

            <button type="submit" class="btn btn-submit">
                <i class="fas fa-sign-in-alt me-2"></i> Se connecter
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Effet de scroll sur la navbar
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.modern-navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script>
</body>
</html>
