@extends('layout')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header" style="background-color: var(--primary-green); color: white;">
                        <h4 class="mb-0">
                            <i class="fas fa-edit"></i>
                            {{ $pension->id ? 'Modifier ma fiche de pension' : 'Compléter ma fiche de pension' }}
                        </h4>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> <strong>Erreurs de validation :</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('pension.update') }}" method="POST" class="needs-validation">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="ville" class="form-label">
                                    <i class="fas fa-map-marker-alt"></i> Ville
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('ville') is-invalid @enderror"
                                    id="ville" name="ville" value="{{ old('ville', $pension->ville ?? '') }}"
                                    placeholder="Entrez le nom de votre ville" required>
                                @error('ville')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="adresse" class="form-label">
                                    <i class="fas fa-home"></i> Adresse
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('adresse') is-invalid @enderror"
                                    id="adresse" name="adresse" value="{{ old('adresse', $pension->adresse ?? '') }}"
                                    placeholder="Entrez l'adresse de votre pension" required>
                                @error('adresse')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="telephone" class="form-label">
                                    <i class="fas fa-phone"></i> Téléphone
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="tel" class="form-control @error('telephone') is-invalid @enderror"
                                    id="telephone" name="telephone" value="{{ old('telephone', $pension->telephone ?? '') }}"
                                    placeholder="Entrez le numéro de téléphone" required>
                                @error('telephone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="responsable" class="form-label">
                                    <i class="fas fa-user"></i> Responsable
                                </label>
                                <input type="text" class="form-control @error('responsable') is-invalid @enderror"
                                    id="responsable" name="responsable" value="{{ old('responsable', $pension->responsable ?? '') }}"
                                    placeholder="Nom du responsable de la pension">
                                @error('responsable')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2 d-sm-flex justify-content-sm-end">
                                <a href="{{ route('pension.dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    {{ $pension->id ? 'Mettre à jour' : 'Créer ma fiche' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="alert alert-info alert-dismissible fade show mt-4" role="alert">
                    <i class="fas fa-info-circle"></i>
                    <strong>Informations :</strong> Votre fiche de pension est essentielle pour gérer vos types d'hébergement et vos boxes.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-label {
            font-weight: 600;
            color: var(--primary-green);
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.25);
        }

        .btn-primary {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }

        .btn-primary:hover {
            background-color: var(--accent-green);
            border-color: var(--accent-green);
        }

        .card-header {
            border-radius: 8px 8px 0 0;
        }

        .text-danger {
            font-weight: bold;
        }
    </style>
@endsection

