@extends('layout')

@section('content')
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="mb-0" style="color: var(--primary-green)">
                        <i class="fas fa-home"></i> Types d'Hébergement
                    </h1>
                    <a href="{{ route('pension.types-gardiennage.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Ajouter un type
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            @if ($typesGardiennage->isEmpty())
                <div class="col-md-12">
                    <div class="card shadow text-center">
                        <div class="card-body py-5">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <h4 class="mt-3 text-muted">Aucun type d'hébergement</h4>
                            <p class="text-muted">Créez votre premier type d'hébergement pour pouvoir gérer vos tarifs.</p>
                            <a href="{{ route('pension.types-gardiennage.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Créer
                            </a>
                        </div>
                    </div>
                </div>
            @else
                @foreach ($typesGardiennage as $type)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow h-100 border-left-warning">
                            <div class="card-body">
                                <h5 class="card-title" style="color: var(--primary-green)">
                                    <i class="fas fa-cube"></i> {{ $type->libelle }}
                                </h5>
                                <hr>
                                <div class="row">
                                    <div class="col-6">
                                        <p class="text-muted small mb-1">Tarif</p>
                                        <p class="mb-0 font-weight-bold" style="font-size: 1.3rem; color: var(--light-green);">
                                            {{ number_format($type->tarif ?? 0, 2, ',', ' ') }} €
                                        </p>
                                    </div>
                                    <div class="col-6 text-end">
                                        <a href="{{ route('pension.types-gardiennage.edit', $type) }}" class="btn btn-sm btn-primary mb-2">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <form action="{{ route('pension.types-gardiennage.destroy', $type) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr ?')">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <a href="{{ route('pension.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour au dashboard
                </a>
            </div>
        </div>
    </div>

    <style>
        .border-left-warning {
            border-left: 4px solid var(--light-green) !important;
        }

        .card {
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .btn-success {
            background-color: var(--light-green);
            border-color: var(--light-green);
        }

        .btn-success:hover {
            background-color: var(--accent-green);
            border-color: var(--accent-green);
        }
    </style>
@endsection

