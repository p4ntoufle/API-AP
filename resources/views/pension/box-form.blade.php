@extends('layout')

@section('content')
    @php
        // Initialiser une box vide si elle n'existe pas
        $box = $box ?? new \App\Models\Box();
    @endphp

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header" style="background-color: var(--primary-green); color: white;">
                        <h4 class="mb-0">
                            <i class="fas fa-cube"></i>
                            {{ $box->id ? 'Modifier la box' : 'Créer une nouvelle box' }}
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

                        <form action="{{ $box->id ? route('pension.boxes.update', $box) : route('pension.boxes.store') }}"
                              method="POST" class="needs-validation">
                            @csrf
                            @if ($box->id)
                                @method('PUT')
                            @endif

                            <div class="mb-4">
                                <label for="superficie" class="form-label">
                                    <i class="fas fa-ruler-combined"></i> Superficie (m²)
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('superficie') is-invalid @enderror"
                                        id="superficie" name="superficie" step="0.01" min="0"
                                        value="{{ old('superficie', $box->superficie ?? '') }}"
                                        placeholder="0.00">
                                    <span class="input-group-text">m²</span>
                                </div>
                                <small class="form-text text-muted">
                                    Entrez la superficie de votre box
                                </small>
                                @error('superficie')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Vous pouvez laisser la superficie vide et la remplir ultérieurement.
                            </div>

                            <div class="d-grid gap-2 d-sm-flex justify-content-sm-end">
                                <a href="{{ route('pension.boxes') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    {{ $box->id ? 'Mettre à jour' : 'Créer' }}
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

        .alert-info {
            background-color: #f0f7ff;
            border-color: #b8daff;
            color: #084298;
        }
    </style>
@endsection

