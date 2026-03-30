@extends('layout')

@section('content')
    @php
        // Initialiser un TypeGardiennage vide si null
        $typeGardiennage = $typeGardiennage ?? new \App\Models\TypeGardiennage();
    @endphp

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header" style="background-color: var(--primary-green); color: white;">
                        <h4 class="mb-0">
                            <i class="fas fa-home"></i>
                            {{ $typeGardiennage->id ? 'Modifier le type d\'hébergement' : 'Créer un nouveau type d\'hébergement' }}
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

                        <form action="{{ $typeGardiennage->id ? route('pension.types-gardiennage.update', $typeGardiennage) : route('pension.types-gardiennage.store') }}"
                              method="POST" class="needs-validation">
                            @csrf
                            @if ($typeGardiennage->id)
                                @method('PUT')
                            @endif

                            <div class="mb-4">
                                <label for="libelle" class="form-label">
                                    <i class="fas fa-tag"></i> Libellé
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('libelle') is-invalid @enderror"
                                    id="libelle" name="libelle"
                                    value="{{ old('libelle', $typeGardiennage->libelle ?? '') }}"
                                    placeholder="Ex: Gardiennage à la maison, Garde en chenil, etc." required>
                                <small class="form-text text-muted">
                                    Décrivez le type d'hébergement que vous proposez
                                </small>
                                @error('libelle')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="d-grid gap-2 d-sm-flex justify-content-sm-end">
                                <a href="{{ route('pension.types-gardiennage') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    {{ $typeGardiennage->id ? 'Mettre à jour' : 'Créer' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-label {
            font-weight: 600;
            color: var(--primary-green);
        }

        .form-control, .input-group-text {
            border-radius: 8px;
            border: 1px solid #ddd;
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

        .input-group-text {
            background-color: #f8f9fa;
            border-left: none;
        }

        .input-group > .form-control {
            border-right: none;
        }
    </style>
@endsection

