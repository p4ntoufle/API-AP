<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>La Mise au Vert</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<div>
<nav class="navbar navbar-expand-lg navbar-dark bg-success px-3">
    <a class="navbar-brand" href="{{ route('home') }}">🐕 La Mise au Vert</a>
    <div class="navbar-nav">
        <a class="nav-link" href="{{ route('pensions') }}">Nos pensions</a>
        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
        @auth
            <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                <button class="btn btn-link nav-link">Déconnexion</button>
            </form>
        @else
            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
        @endauth
    </div>
</nav>

<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <form method="POST" action="{{ route('login') }}" class="p-4 rounded shadow bg-white" style="min-width: 320px; max-width: 400px; width: 100%;">
        @csrf
        <h3 class="text-center mb-4 text-success">Connexion</h3>
        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Entrez votre email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Entrez votre mot de passe" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Se connecter</button>
    </form>
</div>


<div class="container mt-4">
    @yield('content')
</div>
</body>
</html>
