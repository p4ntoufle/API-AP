@extends('layout')

@section('title', 'Mes animaux — La Mise au Vert')

@section('content')
    <style>
        .animaux-header {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            padding: 4rem 2rem;
            text-align: center;
            border-radius: 18px;
            margin-bottom: 2.5rem;
        }

        .animaux-header h1 {
            font-size: 3rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 0.8rem;
            letter-spacing: -1px;
        }

        .animaux-header p {
            font-size: 1.2rem;
            color: #4b5563;
            max-width: 720px;
            margin: 0 auto;
        }

        .animal-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .animal-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .animal-emoji {
            font-size: 3rem;
            line-height: 1;
        }

        .badge-sante {
            font-size: 0.75rem;
            padding: 0.35em 0.7em;
            border-radius: 50px;
        }

        .btn-ajouter {
            background: linear-gradient(135deg, #2d6a4f, #40916c);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: opacity 0.2s;
        }

        .btn-ajouter:hover {
            opacity: 0.9;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a1a1a;
        }

        .empty-state p {
            color: #6b7280;
            max-width: 420px;
            margin: 0.5rem auto 1.5rem;
        }
    </style>

    <div class="animaux-header">
        <h1>🐾 Mes animaux</h1>
        <p>Gérez les fiches de vos animaux. Ces informations seront consultables par les pensions lors de leur séjour.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 text-muted">{{ $animaux->count() }} animal(aux) enregistré(s)</h5>
        <a href="{{ route('animaux.create') }}" class="btn-ajouter">
            <i class="fas fa-plus"></i> Ajouter un animal
        </a>
    </div>

    @if($animaux->isEmpty())
        <div class="card animal-card empty-state">
            <div class="mb-3" style="font-size: 4rem;">🐾</div>
            <h3>Aucun animal enregistré</h3>
            <p>Ajoutez vos animaux pour que les pensions puissent consulter leurs informations lors de leur séjour.</p>
            <a href="{{ route('animaux.create') }}" class="btn-ajouter mx-auto">
                <i class="fas fa-plus"></i> Ajouter mon premier animal
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach($animaux as $animal)
                <div class="col-md-6 col-lg-4">
                    <div class="card animal-card h-100 p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="animal-emoji">
                                {{ strtolower($animal->espece) === 'chat' ? '🐱' : '🐶' }}
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">{{ $animal->nom }}</h5>
                                <span class="text-muted">{{ $animal->espece }}{{ $animal->race ? ' · ' . $animal->race : '' }}</span>
                            </div>
                        </div>

                        <div class="mb-3 text-muted small">
                            @if($animal->age !== null)
                                <span class="me-3"><i class="fas fa-birthday-cake me-1"></i>{{ $animal->age }} an(s)</span>
                            @endif
                            @if($animal->poids !== null)
                                <span><i class="fas fa-weight me-1"></i>{{ $animal->poids }} kg</span>
                            @endif
                        </div>

                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <span class="badge badge-sante {{ $animal->carnet_vaccination ? 'bg-success' : 'bg-danger' }}">
                                {{ $animal->carnet_vaccination ? '✅ Carnet' : '❌ Carnet' }}
                            </span>
                            <span class="badge badge-sante {{ $animal->vaccin_a_jour ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $animal->vaccin_a_jour ? '✅ Vaccin à jour' : '⚠️ Vaccin' }}
                            </span>
                            <span class="badge badge-sante {{ $animal->vermifuge_a_jour ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $animal->vermifuge_a_jour ? '✅ Vermifuge' : '⚠️ Vermifuge' }}
                            </span>
                        </div>

                        <div class="d-flex gap-2 mt-auto">
                            <a href="{{ route('animaux.edit', $animal->id) }}" class="btn btn-outline-success btn-sm flex-grow-1">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                            <form action="{{ route('animaux.destroy', $animal->id) }}" method="POST"
                                  onsubmit="return confirm('Supprimer {{ $animal->nom }} ? Cette action est irréversible.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-4 text-center">
        <a href="{{ route('profil') }}" class="btn btn-outline-secondary">
            <i class="fas fa-user me-2"></i>Voir mon profil
        </a>
    </div>
@endsection
