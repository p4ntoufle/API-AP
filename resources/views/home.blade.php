@extends('layout')

@section('title', 'Accueil — La Mise au Vert')

@section('content')
    <style>
        .home-page {
            background: #ffffff;
            margin: -1rem -15px 0;
            padding: 0;
        }

        /* Hero Section - Clean & Modern */
        .hero-section {
            padding: 6rem 2rem;
            background: #ffffff;
            position: relative;
        }

        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-text h1 {
            font-size: 4rem;
            font-weight: 900;
            color: #1a1a1a;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -2px;
        }

        .hero-text .accent {
            color: #2d6a4f;
            position: relative;
            display: inline-block;
        }

        .hero-text p {
            font-size: 1.3rem;
            color: #666;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .hero-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            background: #2d6a4f;
            color: white;
            padding: 1.2rem 2.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .hero-cta:hover {
            background: #1f4d38;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(45, 106, 79, 0.2);
            color: white;
        }

        .hero-visual {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 24px;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 900;
            color: #2d6a4f;
            line-height: 1;
        }

        .stat-label {
            font-size: 1rem;
            color: #666;
            font-weight: 500;
        }

        /* Info Grid Section */
        .info-grid-section {
            padding: 6rem 2rem;
            background: #ffffff;
        }

        .info-grid-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-header {
            margin-bottom: 4rem;
        }

        .section-header h2 {
            font-size: 3rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 1rem;
            letter-spacing: -1px;
        }

        .section-header p {
            font-size: 1.2rem;
            color: #666;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .info-card {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .info-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border-color: #2d6a4f;
        }

        .info-card-icon {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            display: block;
        }

        .info-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 1rem;
        }

        .info-card p {
            color: #666;
            line-height: 1.7;
            margin: 0;
            font-size: 1.05rem;
        }

        /* Features Section */
        .features-section {
            padding: 6rem 2rem;
            background: white;
        }

        .features-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6rem;
            align-items: center;
        }

        .features-content h2 {
            font-size: 3rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 2rem;
            letter-spacing: -1px;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .feature-item {
            display: flex;
            gap: 1.5rem;
            align-items: start;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: #f0fdf4;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .feature-text h4 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }

        .feature-text p {
            color: #666;
            line-height: 1.6;
            margin: 0;
        }

        .features-visual {
            background: linear-gradient(135deg, #2d6a4f 0%, #40916c 100%);
            border-radius: 24px;
            padding: 4rem;
            color: white;
            text-align: center;
        }

        .features-visual h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .hours-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            text-align: left;
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 16px;
        }

        .hours-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            font-size: 1.1rem;
        }

        .hours-item:last-child {
            border-bottom: none;
        }

        .hours-item .day {
            font-weight: 600;
        }

        /* Quote Section */
        .quote-section {
            padding: 6rem 2rem;
            background: #ffffff;
            color: black;
            text-align: center;
        }

        .quote-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .quote-text {
            font-size: 2.5rem;
            font-weight: 300;
            line-height: 1.5;
            font-style: italic;
        }

        /* CTA Section */
        .cta-section {
            padding: 6rem 2rem;
            background: #ffffff;
            text-align: center;
        }

        .cta-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .cta-container h2 {
            font-size: 3rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 1.5rem;
            letter-spacing: -1px;
        }

        .cta-container p {
            font-size: 1.3rem;
            color: #666;
            margin-bottom: 2.5rem;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary-cta {
            background: #2d6a4f;
            color: white;
            padding: 1.2rem 2.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary-cta:hover {
            background: #1f4d38;
            transform: translateY(-2px);
            color: white;
        }

        .btn-secondary-cta {
            background: white;
            color: #2d6a4f;
            padding: 1.2rem 2.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: 2px solid #2d6a4f;
            display: inline-block;
        }

        .btn-secondary-cta:hover {
            background: #2d6a4f;
            color: white;
            transform: translateY(-2px);
        }

        @media (max-width: 1024px) {
            .hero-container,
            .features-container {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .hero-text h1 {
                font-size: 3rem;
            }

            .section-header h2,
            .features-content h2,
            .cta-container h2 {
                font-size: 2.5rem;
            }

            .quote-text {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .cards-grid {
                grid-template-columns: 1fr;
            }

            .hero-text h1 {
                font-size: 2.5rem;
            }

            .section-header h2 {
                font-size: 2rem;
            }

            .quote-text {
                font-size: 1.5rem;
            }
        }
    </style>

    <div class="home-page">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="hero-container">
                <div class="hero-text">
                    <h1>Le bien-être de vos animaux, <span class="accent">notre priorité</span></h1>
                    <p>11 pensions d'excellence réparties en France pour accueillir vos chiens et chats dans un environnement chaleureux et sécurisé.</p>
                    <a href="{{ route('pensions') }}" class="hero-cta">
                        Découvrir nos pensions
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="hero-visual">
                    <div class="stat-item">
                        <div class="stat-number">11</div>
                        <div class="stat-label">Pensions à travers la France</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">1976</div>
                        <div class="stat-label">Plus de 45 ans d'expertise</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Dédiés au bien-être animal</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Grid Section -->
        <div class="info-grid-section">
            <div class="info-grid-container">
                <div class="section-header">
                    <h2>Nos services</h2>
                    <p>Des hébergements adaptés à chaque besoin</p>
                </div>
                <div class="cards-grid">
                    <div class="info-card">
                        <span class="info-card-icon">🏨</span>
                        <h3>Hôtel canin</h3>
                        <p>Des boxes spacieux et confortables pour que votre chien se sente comme à la maison pendant votre absence.</p>
                    </div>
                    <div class="info-card">
                        <span class="info-card-icon">🏕️</span>
                        <h3>Camping canin</h3>
                        <p>Un espace en plein air pour les chiens qui aiment la liberté et les grands espaces naturels.</p>
                    </div>
                    <div class="info-card">
                        <span class="info-card-icon">🐱</span>
                        <h3>Pension féline</h3>
                        <p>Un environnement calme et adapté aux besoins spécifiques de nos amis félins.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="features-section">
            <div class="features-container">
                <div class="features-content">
                    <h2>Nos engagements</h2>
                    <div class="feature-list">
                        <div class="feature-item">
                            <div class="feature-icon">🩺</div>
                            <div class="feature-text">
                                <h4>Suivi personnalisé</h4>
                                <p>Chaque animal bénéficie d'une attention individuelle et d'un suivi quotidien adapté à ses besoins.</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🤝</div>
                            <div class="feature-text">
                                <h4>Partenaires de confiance</h4>
                                <p>Collaboration étroite avec la SPA et le SNPCC pour garantir les meilleures pratiques.</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🌿</div>
                            <div class="feature-text">
                                <h4>Hygiène irréprochable</h4>
                                <p>Des installations maintenues aux plus hauts standards d'hygiène et de propreté.</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🏛️</div>
                            <div class="feature-text">
                                <h4>Expertise depuis 1976</h4>
                                <p>Une SCOP SARL avec plus de 45 ans d'expérience dans le soin aux animaux.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="features-visual">
                    <h3>Nos horaires</h3>
                    <div class="hours-list">
                        <div class="hours-item">
                            <span class="day">Lundi au Samedi</span>
                            <span class="time">8h30 - 12h</span>
                        </div>
                        <div class="hours-item">
                            <span class="day">Après-midi</span>
                            <span class="time">14h30 - 19h</span>
                        </div>
                        <div class="hours-item">
                            <span class="day">Mardi après-midi</span>
                            <span class="time">Fermé</span>
                        </div>
                        <div class="hours-item">
                            <span class="day">Dimanche & Jours fériés</span>
                            <span class="time">Fermé</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quote Section -->
        <div class="quote-section">
            <div class="quote-container">
                <p class="quote-text">"Parce qu'un animal mérite autant d'attention qu'un membre de la famille"</p>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="cta-section">
            <div class="cta-container">
                <h2>Prêt à nous confier votre compagnon ?</h2>
                <p>Découvrez nos pensions et trouvez celle qui convient le mieux à votre animal</p>
                <div class="cta-buttons">
                    <a href="{{ route('pensions') }}" class="btn-primary-cta">Voir nos pensions</a>
                    <a href="{{ route('contact') }}" class="btn-secondary-cta">Nous contacter</a>
                </div>
            </div>
        </div>
    </div>
@endsection
