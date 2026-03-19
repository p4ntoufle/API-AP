@extends('layout')

@section('title', 'Mon profil — La Mise au Vert')

@section('content')
    <style>
        .profil-header {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            padding: 3rem 2rem;
            text-align: center;
            border-radius: 18px;
            margin-bottom: 2.5rem;
        }

        .profil-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }

        .profil-header p {
            color: #4b5563;
            font-size: 1.1rem;
        }

        .profil-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.07);
            max-width: 600px;
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

        .avatar-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #2d6a4f, #40916c);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin: 0 auto 1rem;
        }
    </style>

    <div class="profil-header">
        <div class="avatar-circle">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
        <h1>Mon profil</h1>
        <p>Modifiez vos informations personnelles et votre mot de passe.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 mx-auto" style="max-width:600px" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger rounded-3 mb-4 mx-auto" style="max-width:600px">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card profil-card p-4 p-md-5">
        <form action="{{ route('profil.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Informations personnelles --}}
            <div class="section-title">Informations personnelles</div>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control rounded-3"
                           value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Adresse email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control rounded-3"
                           value="{{ old('email', $user->email) }}" required>
                </div>
            </div>

            {{-- Changer le mot de passe --}}
            <div class="section-title">Changer le mot de passe</div>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label fw-semibold">Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-control rounded-3"
                           placeholder="Laisser vide pour ne pas changer">
                    <div class="form-text">6 caractères minimum.</div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control rounded-3">
                </div>
            </div>

            <div class="d-flex gap-3">
                <a href="{{ route('animaux') }}" class="btn btn-outline-secondary rounded-3 flex-grow-1">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
                <button type="submit" class="btn btn-save flex-grow-1">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('animaux') }}" class="text-muted small">
            <i class="fas fa-paw me-1"></i>Voir mes animaux ({{ Auth::user()->animaux()->count() }})
        </a>
    </div>
@endsection
