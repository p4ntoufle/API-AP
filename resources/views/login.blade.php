@extends('layout')

@section('content')
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 200px);
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
            color: var(--primary-green, #2d6a4f);
            margin-bottom: 1rem;
        }

        .login-header h3 {
            color: var(--primary-green, #2d6a4f);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .login-card .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .login-card .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .login-card .form-control:focus {
            border-color: var(--primary-green, #2d6a4f);
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
            background: linear-gradient(135deg, var(--primary-green, #2d6a4f) 0%, var(--accent-green, #40916c) 100%);
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
            color: white;
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
                    <strong>Erreur :</strong> {{ $errors->first() }}
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form id="loginForm">
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

    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('{{ route("login") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (!response.ok) {
                    alert(data.error || 'Erreur de connexion');
                    return;
                }

                localStorage.setItem('auth_token', data.token);
                localStorage.setItem('user', JSON.stringify(data.user));
                window.location.href = data.redirect || '/';
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur de connexion');
            }
        });
    </script>
@endsection

