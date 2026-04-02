@extends('layout')

@section('content')
    <style>
        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 200px);
            padding: 2rem 1rem;
        }

        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            padding: 3rem;
            min-width: 320px;
            max-width: 500px;
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

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-header i {
            font-size: 3.5rem;
            color: var(--primary-green, #2d6a4f);
            margin-bottom: 1rem;
        }

        .register-header h3 {
            color: var(--primary-green, #2d6a4f);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .register-header p {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .register-card .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .register-card .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .register-card .form-control:focus {
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

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .form-text {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }

        .register-link p {
            margin: 0;
            color: #6c757d;
        }

        .register-link a {
            color: var(--primary-green, #2d6a4f);
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.2em;
        }

        .alert-verification {
            display: none;
            margin-bottom: 1rem;
        }

        .verification-success {
            background-color: #d4edda;
            border: 2px solid #28a745;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
        }

        .verification-success i {
            color: #28a745;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .verification-success h4 {
            color: #28a745;
            margin: 0.5rem 0;
        }

        .verification-success p {
            color: #155724;
            margin: 0.25rem 0;
            font-size: 0.9rem;
        }

        .password-requirements {
            font-size: 0.85rem;
            margin-top: 0.5rem;
            padding: 0.75rem;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 3px solid var(--primary-green, #2d6a4f);
        }

        .password-requirements ul {
            margin: 0;
            padding-left: 1.25rem;
            color: #6c757d;
        }

        .password-requirements li {
            margin: 0.25rem 0;
        }

        @media (max-width: 576px) {
            .register-card {
                padding: 2rem 1.25rem;
            }

            .register-header i {
                font-size: 2.5rem;
            }
        }
    </style>

    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <i class="fas fa-user-plus"></i>
                <h3>Créer un compte</h3>
                <p>Inscrivez-vous pour gérer votre pension</p>
            </div>

            <div id="errorAlert" class="alert alert-danger" style="display: none;">
                <i class="fas fa-exclamation-circle me-2"></i>
                <span id="errorMessage"></span>
            </div>

            <div id="verificationAlert" class="alert-verification alert-verification">
                <div class="verification-success">
                    <i class="fas fa-check-circle"></i>
                    <h4>Inscription réussie !</h4>
                    <p>Un email de confirmation a été envoyé à <strong id="confirmEmail"></strong></p>
                    <p>Veuillez vérifier votre email et cliquer sur le lien pour activer votre compte.</p>
                    <p style="font-size: 0.85rem; margin-top: 1rem;">
                        Vous pourrez ensuite vous connecter avec vos identifiants.
                    </p>
                </div>
            </div>

            <form id="registerForm" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">
                        <i class="fas fa-user me-1"></i> Nom complet
                    </label>
                    <div class="input-icon">
                        <i class="fas fa-signature"></i>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control"
                               placeholder="Jean Dupont"
                               required
                               autofocus>
                    </div>
                    <small class="form-text">Entrez votre nom complet</small>
                </div>

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
                               required>
                    </div>
                    <small class="form-text">Nous utiliserons cet email pour vous envoyer un lien de vérification</small>
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
                               required
                               minlength="6">
                    </div>
                    <div class="password-requirements">
                        <strong style="color: #495057;">Exigences du mot de passe :</strong>
                        <ul>
                            <li>Minimum 6 caractères</li>
                            <li>Évitez les mots de passe trop simples</li>
                            <li>Utilisez des majuscules et des chiffres si possible</li>
                        </ul>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-lock me-1"></i> Confirmer le mot de passe
                    </label>
                    <div class="input-icon">
                        <i class="fas fa-key"></i>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               class="form-control"
                               placeholder="••••••••"
                               required
                               minlength="6">
                    </div>
                    <small class="form-text" id="passwordMatch"></small>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="agree_terms" id="agree_terms" required>
                    <label class="form-check-label" for="agree_terms">
                        J'accepte les <a href="#" style="color: var(--primary-green, #2d6a4f);">conditions d'utilisation</a>
                    </label>
                </div>

                <button type="submit" id="submitBtn" class="btn btn-submit">
                    <i class="fas fa-user-check me-2"></i> S'inscrire
                </button>
            </form>

            <div class="register-link">
                <p>Vous avez déjà un compte ? 
                    <a href="{{ route('login') }}">Se connecter</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('registerForm');
        const submitBtn = document.getElementById('submitBtn');
        const errorAlert = document.getElementById('errorAlert');
        const errorMessage = document.getElementById('errorMessage');
        const verificationAlert = document.getElementById('verificationAlert');
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const passwordMatchText = document.getElementById('passwordMatch');

        // Vérifier si les mots de passe correspondent
        passwordConfirmInput.addEventListener('input', function() {
            if (passwordInput.value !== passwordConfirmInput.value) {
                passwordMatchText.textContent = '❌ Les mots de passe ne correspondent pas';
                passwordMatchText.style.color = '#dc3545';
            } else if (passwordInput.value.length >= 6) {
                passwordMatchText.textContent = '✓ Les mots de passe correspondent';
                passwordMatchText.style.color = '#28a745';
            } else {
                passwordMatchText.textContent = '';
            }
        });

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Validation côté client
            if (passwordInput.value !== passwordConfirmInput.value) {
                showError('Les mots de passe ne correspondent pas');
                return;
            }

            if (!document.getElementById('agree_terms').checked) {
                showError('Vous devez accepter les conditions d\'utilisation');
                return;
            }

            // Désactiver le bouton
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Inscription en cours...';

            try {
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        name: document.getElementById('name').value,
                        email: document.getElementById('email').value,
                        password: passwordInput.value,
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    // Afficher le message de succès
                    form.style.display = 'none';
                    document.querySelector('.register-link').style.display = 'none';
                    document.getElementById('confirmEmail').textContent = data.user.email;
                    verificationAlert.style.display = 'block';
                    errorAlert.style.display = 'none';
                } else {
                    // Afficher l'erreur
                    const errorMsg = data.message || data.errors?.[Object.keys(data.errors)[0]]?.[0] || 'Une erreur est survenue';
                    showError(errorMsg);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-user-check me-2"></i> S\'inscrire';
                }
            } catch (error) {
                console.error('Erreur:', error);
                showError('Une erreur réseau est survenue. Veuillez réessayer.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-user-check me-2"></i> S\'inscrire';
            }
        });

        function showError(message) {
            errorMessage.textContent = message;
            errorAlert.style.display = 'block';
            verificationAlert.style.display = 'none';
            window.scrollTo(0, 0);
        }
    </script>
@endsection
