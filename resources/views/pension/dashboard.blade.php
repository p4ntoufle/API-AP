@extends('layout')

@section('content')
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="mb-0" style="color: var(--primary-green)">
                        <i class="fas fa-building"></i> Dashboard Pension
                    </h1>
                    <a href="{{ route('pension.edit') }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Modifier ma fiche
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
            <!-- Informations Pension -->
            <div class="col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title" style="color: var(--primary-green)">
                            <i class="fas fa-info-circle"></i> Informations de la Pension
                        </h5>
                        <hr>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="text-muted small">Ville</label>
                                <p class="mb-0 font-weight-bold">{{ $pension->ville ?? 'N/A' }}</p>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-muted small">Adresse</label>
                                <p class="mb-0 font-weight-bold">{{ $pension->adresse ?? 'N/A' }}</p>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-muted small">Téléphone</label>
                                <p class="mb-0 font-weight-bold">{{ $pension->telephone ?? 'N/A' }}</p>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-muted small">Responsable</label>
                                <p class="mb-0 font-weight-bold">{{ $pension->responsable ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="col-md-6 mb-4">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title" style="color: var(--primary-green)">
                            <i class="fas fa-chart-bar"></i> Statistiques
                        </h5>
                        <hr>
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <p class="text-muted small mb-1">Types de Gardiennage</p>
                                <h3 style="color: var(--light-green)">{{ $pension->typesGardiennage->count() }}</h3>
                                <a href="{{ route('pension.types-gardiennage') }}" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="fas fa-arrow-right"></i> Gérer
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <p class="text-muted small mb-1">Tarifs</p>
                                <h3 style="color: var(--light-green)">{{ $pension->tarifs->count() }}</h3>
                                <a href="{{ route('pension.tarifs') }}" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="fas fa-arrow-right"></i> Gérer
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <p class="text-muted small mb-1">Boxes</p>
                                <h3 style="color: var(--light-green)">{{ $pension->boxes->count() }}</h3>
                                <a href="{{ route('pension.boxes') }}" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="fas fa-arrow-right"></i> Gérer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        .border-left-primary {
            border-left: 4px solid var(--primary-green) !important;
        }

        .border-left-success {
            border-left: 4px solid var(--light-green) !important;
        }

        .card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .btn-outline-primary {
            color: var(--primary-green);
            border-color: var(--primary-green);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }
    </style>
@endsection

