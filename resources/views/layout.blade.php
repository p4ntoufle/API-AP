<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>La Mise au Vert</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success px-3">
    <a class="navbar-brand" href="{{ route('home') }}">La Mise au Vert</a>
    <div class="navbar-nav">
        <a class="nav-link" href="{{ route('pensions') }}">Nos pensions</a>
        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
        @auth
            <a class="nav-link" href="{{ route('fiches') }}">Vos fiches</a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                <button class="btn btn-link nav-link">Déconnexion</button>
            </form>
        @else
            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
        @endauth
    </div>
</nav>

<div class="container mt-4">
    @yield('content')
</div>
</body>
</html>
