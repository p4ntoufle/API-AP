@extends('layout')

@section('title', ($animal ? 'Modifier ' . $animal->nom : 'Ajouter un animal') . ' — La Mise au Vert')

@section('content')
    <style>
        .form-header {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            padding: 3rem 2rem;
            text-align: center;
            border-radius: 18px;
            margin-bottom: 2.5rem;
        }

        .form-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #4b5563;
            font-size: 1.1rem;
        }

        .form-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.07);
            max-width: 700px;
            margin: 0 auto;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #2d6a4f;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #dcfce7;
        }

        .form-check-input:checked {
            background-color: #2d6a4f;
            border-color: #2d6a4f;
        }

        .btn-save {
            background: linear-gradient(135deg, #2d6a4f, #40916c);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            color: white;
            width: 100%;
            font-size: 1rem;
            transition: opacity 0.2s;
        }

        .btn-save:hover {
            opacity: 0.9;
            color: white;
        }
    </style>

    <div class="form-header">
        <h1>{{ $animal ? '✏️ Modifier ' . $animal->nom : '🐾 Ajouter un animal' }}</h1>
        <p>{{ $animal ? 'Mettez à jour les informations de votre animal.' : 'Renseignez les informations de votre animal.' }}</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-3 mb-4 mx-auto" style="max-width:700px">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card form-card p-4 p-md-5">
        <form action="{{ $animal ? route('animaux.update', $animal->id) : route('animaux.store') }}"
              method="POST">
            @csrf
            @if($animal)
                @method('PUT')
            @endif

            {{-- Identité --}}
            <div class="section-title">Identité</div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                    <input type="text" name="nom" class="form-control rounded-3"
                           value="{{ old('nom', $animal->nom ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Espèce <span class="text-danger">*</span></label>
                    <select name="espece" class="form-select rounded-3" required>
                        @foreach(['Chien', 'Chat', 'Lapin', 'Oiseau', 'Autre'] as $espece)
                            <option value="{{ $espece }}"
                                {{ old('espece', $animal->espece ?? '') === $espece ? 'selected' : '' }}>
                                {{ $espece }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Race</label>
                    <input type="text" name="race" class="form-control rounded-3"
                           value="{{ old('race', $animal->race ?? '') }}"
                           placeholder="Labrador, Siamois…">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Âge (ans)</label>
                    <input type="number" name="age" class="form-control rounded-3" min="0"
                           value="{{ old('age', $animal->age ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Poids (kg)</label>
                    <input type="number" name="poids" class="form-control rounded-3"
                           min="0" step="0.1"
                           value="{{ old('poids', $animal->poids ?? '') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Description / Particularités</label>
                    <textarea name="description" class="form-control rounded-3" rows="3"
                              placeholder="Allergies, comportement, régime alimentaire…">{{ old('description', $animal->description ?? '') }}</textarea>
                </div>
            </div>

            {{-- Santé --}}
            <div class="section-title">Santé</div>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="form-check form-switch ps-0">
                        <label class="form-check-label fw-semibold d-flex align-items-center gap-2">
                            <input type="hidden" name="carnet_vaccination" value="0">
                            <input class="form-check-input ms-0" type="checkbox"
                                   name="carnet_vaccination" value="1"
                                {{ old('carnet_vaccination', $animal->carnet_vaccination ?? false) ? 'checked' : '' }}>
                            Carnet de vaccination
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check form-switch ps-0">
                        <label class="form-check-label fw-semibold d-flex align-items-center gap-2">
                            <input type="hidden" name="vaccin_a_jour" value="0">
                            <input class="form-check-input ms-0" type="checkbox"
                                   name="vaccin_a_jour" value="1"
                                {{ old('vaccin_a_jour', $animal->vaccin_a_jour ?? false) ? 'checked' : '' }}>
                            Vaccins à jour
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check form-switch ps-0">
                        <label class="form-check-label fw-semibold d-flex align-items-center gap-2">
                            <input type="hidden" name="vermifuge_a_jour" value="0">
                            <input class="form-check-input ms-0" type="checkbox"
                                   name="vermifuge_a_jour" value="1"
                                {{ old('vermifuge_a_jour', $animal->vermifuge_a_jour ?? false) ? 'checked' : '' }}>
                            Vermifuge à jour
                        </label>
                    </div>
                </div>
            </div>

            {{-- Boutons --}}
            <div class="d-flex gap-3">
                <a href="{{ route('animaux') }}" class="btn btn-outline-secondary rounded-3 flex-grow-1">
                    <i class="fas fa-arrow-left me-2"></i>Annuler
                </a>
                <button type="submit" class="btn btn-save flex-grow-1">
                    <i class="fas fa-save me-2"></i>{{ $animal ? 'Enregistrer les modifications' : 'Ajouter l\'animal' }}
                </button>
            </div>
        </form>
    </div>
@endsection
