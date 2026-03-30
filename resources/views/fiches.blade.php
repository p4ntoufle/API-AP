@extends('layout')

@section('content')
    <style>
        .fiches-header {
            text-align: center;
            margin-bottom: 2.5rem;
            padding: 2rem 0 1rem;
        }

        .fiches-header i {
            font-size: 3rem;
            color: var(--primary-green, #2d6a4f);
            margin-bottom: 0.75rem;
        }

        .fiches-header h2 {
            color: var(--primary-green, #2d6a4f);
            font-weight: 700;
        }

        .fiches-header p {
            color: #6c757d;
            font-size: 1.05rem;
        }

        .user-section {
            margin-bottom: 2.5rem;
        }

        .user-section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--light-green, #52b788);
        }

        .user-section-title i {
            font-size: 1.4rem;
            color: var(--primary-green, #2d6a4f);
        }

        .user-section-title h3 {
            margin: 0;
            color: var(--primary-green, #2d6a4f);
            font-weight: 600;
            font-size: 1.4rem;
        }

        .pension-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 4px solid var(--light-green, #52b788);
        }

        .pension-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .pension-card .view-mode h4 {
            color: var(--primary-green, #2d6a4f);
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .pension-card .view-mode h4 i {
            font-size: 1.1rem;
        }

        .pension-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .pension-info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .pension-info-item i {
            color: var(--accent-green, #40916c);
            width: 18px;
            text-align: center;
            flex-shrink: 0;
        }

        .pension-info-item strong {
            color: #495057;
            margin-right: 0.25rem;
        }

        .pension-info-item span {
            color: #6c757d;
        }

        .edit-btn {
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            background: linear-gradient(135deg, var(--primary-green, #2d6a4f), var(--accent-green, #40916c));
            color: white;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
            margin-right: 0.5rem;
        }

        .edit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(45, 106, 79, 0.3);
            color: white;
        }

        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            border-left: 4px solid #17a2b8;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 3rem;
            color: #ccc;
            display: block;
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 1.1rem;
        }

        @media (max-width: 576px) {
            .pension-info-grid {
                grid-template-columns: 1fr;
            }

            .pension-card {
                padding: 1.25rem;
            }
        }
    </style>

    <div class="fiches-header">
        <i class="fas fa-file-alt d-block"></i>
        <h2>Ma Fiche de Pension</h2>
        <p>Consultez et modifiez les informations de votre pension</p>
    </div>

    @auth
        @if(Auth::user()->pension)
            @php $user = Auth::user(); $pension = $user->pension @endphp
            <div class="user-section">
                <div class="user-section-title">
                    <i class="fas fa-user-circle"></i>
                    <h3>{{ $user->name }}</h3>
                </div>

                <div class="pension-card">
                    <div class="view-mode">
                        <h4>
                            <i class="fas fa-building"></i>
                            Ma Pension
                        </h4>
                        <div class="pension-info-grid">
                            <div class="pension-info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <strong>Adresse :</strong>
                                <span>{{ $pension->adresse ?? '-' }}</span>
                            </div>
                            <div class="pension-info-item">
                                <i class="fas fa-city"></i>
                                <strong>Ville :</strong>
                                <span>{{ $pension->ville ?? '-' }}</span>
                            </div>
                            <div class="pension-info-item">
                                <i class="fas fa-phone"></i>
                                <strong>Téléphone :</strong>
                                <span>{{ $pension->telephone ?? '-' }}</span>
                            </div>
                            <div class="pension-info-item">
                                <i class="fas fa-user"></i>
                                <strong>Responsable :</strong>
                                <span>{{ $pension->responsable ?? '-' }}</span>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('pension.edit') }}" class="edit-btn">
                                <i class="fas fa-edit"></i> Modifier ma fiche
                            </a>
                            <a href="{{ route('pension.dashboard') }}" class="edit-btn" style="background: linear-gradient(135deg, #52b788, #40916c);">
                                <i class="fas fa-chart-line"></i> Mon Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info" style="margin-top: 2rem;">
                <i class="fas fa-info-circle"></i> Pas de pension associée à votre compte. Veuillez contacter l'administrateur.
            </div>
        @endif
    @else
        <div class="alert alert-warning" style="margin-top: 2rem;">
            <i class="fas fa-lock"></i> Vous devez être connecté pour accéder à cette page. <a href="{{ route('login') }}">Se connecter</a>
        </div>
    @endauth

@endsection

