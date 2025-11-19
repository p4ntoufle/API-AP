@extends('layout')

@section('content')
    <style>
        .contact-page {
            background: white;
            min-height: calc(100vh - 80px);
            padding: 3rem 0;
        }

        .contact-hero {
            text-align: center;
            margin-bottom: 4rem;
            animation: fadeIn 0.8s ease;
        }

        .contact-hero h2 {
            color: #2d6a4f;
            font-weight: 800;
            font-size: 3rem;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .contact-hero h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: white;
            border-radius: 2px;
        }

        .contact-hero p {
            color: #6c757d;
            font-size: 1.2rem;
            max-width: 700px;
            margin: 1.5rem auto 0;
        }

        .contact-wrapper {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Cartes d'informations en haut */
        .info-cards-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
            animation: slideUp 0.8s ease;
        }

        .info-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            border: 2px solid transparent;
        }

        .info-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 35px rgba(45, 106, 79, 0.2);
            border-color: #52b788;
        }

        .info-card-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #2d6a4f, #52b788);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            transition: transform 0.4s ease;
        }

        .info-card:hover .info-card-icon {
            transform: rotate(360deg) scale(1.1);
        }

        .info-card h4 {
            color: #2d6a4f;
            font-weight: 700;
            margin-bottom: 0.8rem;
            font-size: 1.2rem;
        }

        .info-card p {
            color: #6c757d;
            margin: 0;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        /* Formulaire central */
        .form-container {
            background: white;
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.12);
            animation: fadeInScale 0.8s ease;
            margin-bottom: 3rem;
        }

        .form-title {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .form-title h3 {
            color: #2d6a4f;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .form-title p {
            color: #6c757d;
            font-size: 1rem;
        }

        .form-row-custom {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group-custom {
            margin-bottom: 1.5rem;
        }

        .form-label-custom {
            display: block;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.6rem;
            font-size: 0.95rem;
        }

        .form-label-custom i {
            color: #2d6a4f;
            margin-right: 0.5rem;
            width: 18px;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #ffffff;
        }

        .form-input:focus {
            outline: none;
            border-color: #2d6a4f;
            background: white;
            box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.1);
        }

        .form-textarea {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 1rem;
            resize: vertical;
            min-height: 160px;
            background: #f8f9fa;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #2d6a4f;
            background: white;
            box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.1);
        }

        .btn-submit-custom {
            width: 100%;
            padding: 1.2rem;
            background: linear-gradient(135deg, #2d6a4f 0%, #40916c 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
        }

        .btn-submit-custom::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-submit-custom:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-submit-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(45, 106, 79, 0.4);
        }

        .btn-submit-custom span {
            position: relative;
            z-index: 1;
        }

        /* Section horaires et réseaux sociaux en bas */
        .bottom-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            animation: fadeIn 1s ease;
        }

        .hours-card, .social-card {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .card-header-custom {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }

        .card-header-custom i {
            font-size: 2rem;
            color: #2d6a4f;
        }

        .card-header-custom h4 {
            color: #2d6a4f;
            font-weight: 700;
            margin: 0;
            font-size: 1.4rem;
        }

        .hours-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .hours-list li {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid #e9ecef;
            color: #495057;
        }

        .hours-list li:last-child {
            border-bottom: none;
        }

        .hours-list .day {
            font-weight: 600;
        }

        .hours-list .time {
            color: #6c757d;
        }

        .social-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .social-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 12px;
            text-decoration: none;
            color: #495057;
            font-weight: 600;
            transition: all 0.3s ease;
            gap: 1rem;
        }

        .social-item i {
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .social-item.facebook:hover {
            background: #1877f2;
            color: white;
            transform: translateX(5px);
        }

        .social-item.instagram:hover {
            background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
            color: white;
            transform: translateX(5px);
        }

        .social-item.twitter:hover {
            background: #1da1f2;
            color: white;
            transform: translateX(5px);
        }

        .social-item.whatsapp:hover {
            background: #25d366;
            color: white;
            transform: translateX(5px);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @media (max-width: 768px) {
            .contact-hero h2 {
                font-size: 2rem;
            }

            .form-row-custom {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .form-container {
                padding: 2rem 1.5rem;
            }

            .bottom-section {
                grid-template-columns: 1fr;
            }

            .info-cards-row {
                grid-template-columns: 1fr;
            }

            .social-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="contact-page">
        <div class="contact-wrapper">
            <!-- En-tête Hero -->
            <div class="contact-hero">
                <h2>Contactez-nous</h2>
                <p>Nous sommes là pour répondre à toutes vos questions et vous accompagner dans votre projet. N'hésitez pas à nous écrire !</p>
            </div>

            <!-- Cartes d'informations rapides -->
            <div class="info-cards-row">
                <div class="info-card">
                    <div class="info-card-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4>Notre adresse</h4>
                    <p>123 Chemin de la Nature<br>75000 Paris, France</p>
                </div>

                <div class="info-card">
                    <div class="info-card-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h4>Téléphone</h4>
                    <p>01 23 45 67 89<br>Lun-Ven: 9h-18h</p>
                </div>

                <div class="info-card">
                    <div class="info-card-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4>Email</h4>
                    <p>contact@lamiseauvert.fr<br>Réponse sous 24h</p>
                </div>
            </div>

            <!-- Formulaire central -->
            <div class="form-container">
                <div class="form-title">
                    <h3>Envoyez-nous un message</h3>
                    <p>Remplissez le formulaire ci-dessous et nous vous répondrons rapidement</p>
                </div>

                <form> <!-- Ajouter méthode POST et route -->
                    @csrf

                    <div class="form-row-custom">
                        <div class="form-group-custom">
                            <label for="name" class="form-label-custom">
                                <i class="fas fa-user"></i>Nom complet
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-input"
                                   placeholder="Jean Dupont"
                                   value="{{ old('name') }}"
                                   required>
                        </div>

                        <div class="form-group-custom">
                            <label for="email" class="form-label-custom">
                                <i class="fas fa-envelope"></i>Adresse e-mail
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-input"
                                   placeholder="votre@email.com"
                                   value="{{ old('email') }}"
                                   required>
                        </div>
                    </div>

                    <div class="form-row-custom">
                        <div class="form-group-custom">
                            <label for="phone" class="form-label-custom">
                                <i class="fas fa-phone"></i>Téléphone
                            </label>
                            <input type="tel"
                                   id="phone"
                                   name="phone"
                                   class="form-input"
                                   placeholder="06 12 34 56 78"
                                   value="{{ old('phone') }}">
                        </div>

                        <div class="form-group-custom">
                            <label for="subject" class="form-label-custom">
                                <i class="fas fa-tag"></i>Sujet
                            </label>
                            <select id="subject" name="subject" class="form-input" required>
                                <option value="">Sélectionnez un sujet</option>
                                <option value="reservation">Réservation</option>
                                <option value="information">Demande d'information</option>
                                <option value="visite">Visite de la pension</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label for="message" class="form-label-custom">
                            <i class="fas fa-comment-dots"></i>Votre message
                        </label>
                        <textarea id="message"
                                  name="message"
                                  class="form-textarea"
                                  placeholder="Décrivez votre demande en détail..."
                                  required>{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="btn-submit-custom">
                        <span><i class="fas fa-paper-plane"></i> Envoyer le message</span>
                    </button>
                </form>
            </div>

            <!-- Section horaires et réseaux sociaux -->
            <div class="bottom-section">
                <div class="hours-card">
                    <div class="card-header-custom">
                        <i class="fas fa-clock"></i>
                        <h4>Horaires d'ouverture</h4>
                    </div>
                    <ul class="hours-list">
                        <li>
                            <span class="day">Lundi - Vendredi</span>
                            <span class="time">9h00 - 18h00</span>
                        </li>
                        <li>
                            <span class="day">Samedi</span>
                            <span class="time">10h00 - 16h00</span>
                        </li>
                        <li>
                            <span class="day">Dimanche</span>
                            <span class="time">Fermé</span>
                        </li>
                    </ul>
                </div>

                <div class="social-card">
                    <div class="card-header-custom">
                        <i class="fas fa-share-alt"></i>
                        <h4>Suivez-nous</h4>
                    </div>
                    <div class="social-grid">
                        <a href="#" class="social-item facebook">
                            <i class="fab fa-facebook-f"></i>
                            <span>Facebook</span>
                        </a>
                        <a href="#" class="social-item instagram">
                            <i class="fab fa-instagram"></i>
                            <span>Instagram</span>
                        </a>
                        <a href="#" class="social-item twitter">
                            <i class="fab fa-twitter"></i>
                            <span>Twitter</span>
                        </a>
                        <a href="#" class="social-item whatsapp">
                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
