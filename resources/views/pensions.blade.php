@extends('layout')

@section('title', 'Nos pensions — La Mise au Vert')

@section('content')
    <style>
        .pensions-page {
            background: #ffffff;
            margin: -1rem -15px 0;
            padding: 0;
        }

        /* Header Section */
        .pensions-header {
            padding: 5rem 2rem 3rem;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            text-align: center;
        }

        .pensions-header h1 {
            font-size: 3.5rem;
            font-weight: 900;
            color: #1a1a1a;
            margin-bottom: 1rem;
            letter-spacing: -2px;
        }

        .pensions-header p {
            font-size: 1.3rem;
            color: #666;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Pensions Grid */
        .pensions-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }

        .pensions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 2.5rem;
        }

        .pension-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s ease;
            border: 1px solid #f0f0f0;
            display: flex;
            flex-direction: column;
        }

        .pension-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
            border-color: #2d6a4f;
        }

        .pension-image {
            width: 100%;
            height: 240px;
            background: linear-gradient(135deg, #2d6a4f 0%, #52b788 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .pension-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .pension-content {
            padding: 2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .pension-header-info {
            margin-bottom: 1.5rem;
        }

        .pension-name {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .pension-location {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #2d6a4f;
            font-weight: 600;
            font-size: 1.05rem;
        }

        .pension-location i {
            font-size: 1rem;
        }

        .pension-description {
            color: #666;
            line-height: 1.7;
            margin-bottom: 1.5rem;
            font-size: 1rem;
            flex: 1;
        }

        .pension-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: #2d6a4f;
            display: block;
            line-height: 1;
            margin-bottom: 0.3rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #666;
            font-weight: 600;
        }

        .pension-details {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-size: 0.95rem;
            color: #666;
        }

        .detail-item i {
            color: #2d6a4f;
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        .detail-item a {
            color: #2d6a4f;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .detail-item a:hover {
            color: #1f4d38;
            text-decoration: underline;
        }

        .services-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .service-tag {
            background: #f0fdf4;
            color: #2d6a4f;
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid #dcfce7;
        }

        .options-list {
            margin-bottom: 1.5rem;
            padding: 1rem;
            border-radius: 12px;
            background: #f8f9fa;
        }

        .options-list h4 {
            font-size: 1rem;
            margin-bottom: 0.75rem;
            color: #2d6a4f;
            font-weight: 700;
        }

        .option-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed #ddd;
            padding: 0.4rem 0;
            font-size: 0.95rem;
        }

        .option-row:last-child {
            border-bottom: 0;
        }

        .option-price {
            color: #2d6a4f;
            font-weight: 700;
        }

        .pension-footer {
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price-info {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .price {
            font-size: 1.3rem;
            font-weight: 800;
            color: #2d6a4f;
        }

        .price-label {
            font-size: 0.8rem;
            color: #666;
        }

        .btn-contact {
            background: #2d6a4f;
            color: white;
            padding: 0.8rem 1.8rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-contact:hover {
            background: #1f4d38;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(45, 106, 79, 0.3);
            color: white;
        }

        /* No results */
        .no-results {
            text-align: center;
            padding: 4rem 2rem;
        }

        .no-results h3 {
            font-size: 2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 1rem;
        }

        .no-results p {
            font-size: 1.2rem;
            color: #666;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pensions-header h1 {
                font-size: 2.5rem;
            }

            .pensions-header p {
                font-size: 1.1rem;
            }

            .pensions-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .pension-stats {
                grid-template-columns: 1fr 1fr;
            }

            .pension-footer {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .btn-contact {
                justify-content: center;
            }
        }
    </style>

    <div class="pensions-page">
        <!-- Header -->
        <div class="pensions-header">
            <h1>Nos pensions</h1>
            <p>Découvrez nos 11 établissements répartis à travers la France, chacun offrant un service d'excellence pour vos compagnons</p>
        </div>

        <!-- Pensions Grid -->
        <div class="pensions-container">
            @if($pensions->count() > 0)
                <div class="pensions-grid">
                    @foreach($pensions as $pension)
                        <div class="pension-card">
                            <!-- Image placeholder -->
                            <div class="pension-image">
                                @if($pension->image)
                                    <img src="{{ asset('storage/' . $pension->image) }}" alt="{{ $pension->nom }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="fas fa-paw"></i>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="pension-content">
                                <div class="pension-header-info">
                                    <h3 class="pension-name">{{ $pension->nom }}</h3>
                                    <div class="pension-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $pension->ville }} ({{ $pension->code_postal }})</span>
                                    </div>
                                </div>

                                @if($pension->description)
                                    <p class="pension-description">{{ Str::limit($pension->description, 150) }}</p>
                                @endif

                                <!-- Stats -->
                                <div class="pension-stats">
                                    <div class="stat-item">
                                        <span class="stat-value">{{ $pension->capacite_chiens }}</span>
                                        <span class="stat-label">Places chiens</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-value">{{ $pension->capacite_chats }}</span>
                                        <span class="stat-label">Places chats</span>
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="pension-details">
                                    <div class="detail-item">
                                        <i class="fas fa-map-marked-alt"></i>
                                        <span>{{ $pension->adresse ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-city"></i>
                                        <span>{{ $pension->ville ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-phone"></i>
                                        <a href="tel:{{ $pension->telephone }}">{{ $pension->telephone ?? 'N/A' }}</a>
                                    </div>
                                    @if($pension->responsable)
                                        <div class="detail-item">
                                            <i class="fas fa-user-tie"></i>
                                            <span>{{ $pension->responsable }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- ORAL -->

                                @if($pension->options->isNotEmpty())
                                    <div class="options-list">
                                        <h4>Options disponibles</h4>
                                        @foreach($pension->options as $option)
                                            <div class="option-row">
                                                <span>{{ $option->libelle }}</span>
                                                <span class="option-price">{{ number_format($option->tarif, 2, ',', ' ') }} EUR</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- FIN ORAL -->

                                <!-- Footer with action button -->
                                <div class="pension-footer">
                                    @auth
                                        @if(auth()->user()->pension && auth()->user()->pension->id === $pension->id)
                                            <a href="{{ route('pension.dashboard') }}" class="btn-contact" style="background: #52b788;">
                                                <i class="fas fa-chart-line"></i> Mon Dashboard
                                            </a>
                                        @else
                                            <a href="{{ route('contact') }}" class="btn-contact">
                                                <i class="fas fa-envelope"></i> Nous contacter
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn-contact">
                                            <i class="fas fa-sign-in-alt"></i> Connexion Pension
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-results">
                    <h3>Aucune pension disponible</h3>
                    <p>Nos pensions seront bientôt disponibles. Revenez plus tard !</p>
                </div>
            @endif
        </div>
    </div>
@endsection
