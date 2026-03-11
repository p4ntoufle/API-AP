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

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.9rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-actif {
            background: #d4edda;
            color: #155724;
        }

        .badge-inactif {
            background: #f8d7da;
            color: #721c24;
        }

        .edit-btn, .btn-apply, .cancel-btn {
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .edit-btn {
            background: linear-gradient(135deg, var(--primary-green, #2d6a4f), var(--accent-green, #40916c));
            color: white;
        }

        .edit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(45, 106, 79, 0.3);
        }

        .btn-apply {
            background: linear-gradient(135deg, var(--primary-green, #2d6a4f), var(--accent-green, #40916c));
            color: white;
        }

        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(45, 106, 79, 0.3);
        }

        .cancel-btn {
            background: #e9ecef;
            color: #495057;
        }

        .cancel-btn:hover {
            background: #dee2e6;
            transform: translateY(-2px);
        }

        .edit-mode {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .edit-mode .form-group {
            margin-bottom: 1rem;
        }

        .edit-mode label {
            display: block;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.35rem;
            font-size: 0.9rem;
        }

        .edit-mode input[type="text"],
        .edit-mode input[type="email"],
        .edit-mode input[type="number"] {
            width: 100%;
            padding: 0.6rem 1rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .edit-mode input[type="text"]:focus,
        .edit-mode input[type="email"]:focus,
        .edit-mode input[type="number"]:focus {
            outline: none;
            border-color: var(--primary-green, #2d6a4f);
            box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.15);
        }

        .edit-mode .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .edit-mode .form-check input[type="checkbox"] {
            width: 1.2rem;
            height: 1.2rem;
            accent-color: var(--primary-green, #2d6a4f);
            cursor: pointer;
        }

        .edit-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .edit-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 1.1rem;
        }

        .error-box {
            background: #f8d7da;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .error-box p {
            margin: 0.25rem 0;
            color: #721c24;
            font-size: 0.9rem;
        }

        @media (max-width: 576px) {
            .pension-info-grid {
                grid-template-columns: 1fr;
            }

            .edit-grid {
                grid-template-columns: 1fr;
            }

            .pension-card {
                padding: 1.25rem;
            }
        }
    </style>

    <div class="fiches-header">
        <i class="fas fa-file-alt d-block"></i>
        <h2>Fiches des pensions</h2>
        <p>Consultez et modifiez les informations de vos pensions</p>
    </div>

    @foreach($users as $user)
        <div class="user-section">
            <div class="user-section-title">
                <i class="fas fa-user-circle"></i>
                <h3>{{ $user->name }}</h3>
            </div>

            @if($user->pensions->count())
                @foreach($user->pensions as $pension)
                    <div class="pension-card">
                        <form method="post" action="{{ route('fiches.update', $pension->id) }}">
                            @csrf
                            @method('PUT')

                            {{-- Mode lecture --}}
                            <div class="view-mode">
                                <h4>
                                    <i class="fas fa-paw"></i>
                                    {{ $pension->nom ?? 'Nom non défini' }}
                                    <span class="badge-status {{ $pension->actif ? 'badge-actif' : 'badge-inactif' }} ms-2">
                                        <i class="fas {{ $pension->actif ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                        {{ $pension->actif ? 'Actif' : 'Inactif' }}
                                    </span>
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
                                        <i class="fas fa-mail-bulk"></i>
                                        <strong>Code postal :</strong>
                                        <span>{{ $pension->code_postal ?? '-' }}</span>
                                    </div>
                                    <div class="pension-info-item">
                                        <i class="fas fa-dog"></i>
                                        <strong>Chiens :</strong>
                                        <span>{{ $pension->capacite_chiens ?? 0 }} places</span>
                                    </div>
                                    <div class="pension-info-item">
                                        <i class="fas fa-cat"></i>
                                        <strong>Chats :</strong>
                                        <span>{{ $pension->capacite_chats ?? 0 }} places</span>
                                    </div>
                                    <div class="pension-info-item">
                                        <i class="fas fa-envelope"></i>
                                        <strong>Email :</strong>
                                        <span>{{ $pension->email ?? '-' }}</span>
                                    </div>
                                    <div class="pension-info-item">
                                        <i class="fas fa-euro-sign"></i>
                                        <strong>Chien/jour :</strong>
                                        <span>{{ $pension->prix_chien_jour ?? '-' }} €</span>
                                    </div>
                                    <div class="pension-info-item">
                                        <i class="fas fa-euro-sign"></i>
                                        <strong>Chat/jour :</strong>
                                        <span>{{ $pension->prix_chat_jour ?? '-' }} €</span>
                                    </div>
                                </div>
                                <button type="button" class="edit-btn">
                                    <i class="fas fa-pen me-1"></i> Modifier
                                </button>
                            </div>

                            {{-- Mode édition --}}
                            <div class="edit-mode" style="display:none;">
                                <div class="edit-grid">
                                    <div class="form-group">
                                        <label><i class="fas fa-tag me-1"></i>Nom</label>
                                        <input type="text" name="nom" value="{{ $pension->nom }}">
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-map-marker-alt me-1"></i>Adresse</label>
                                        <input type="text" name="adresse" value="{{ $pension->adresse }}">
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-city me-1"></i>Ville</label>
                                        <input type="text" name="ville" value="{{ $pension->ville }}">
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-mail-bulk me-1"></i>Code postal</label>
                                        <input type="text" name="code_postal" value="{{ $pension->code_postal }}">
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-dog me-1"></i>Capacité chiens</label>
                                        <input type="number" name="capacite_chiens" value="{{ $pension->capacite_chiens }}">
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-cat me-1"></i>Capacité chats</label>
                                        <input type="number" name="capacite_chats" value="{{ $pension->capacite_chats }}">
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-envelope me-1"></i>Email</label>
                                        <input type="email" name="email" value="{{ $pension->email }}">
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-euro-sign me-1"></i>Prix chien/jour</label>
                                        <input type="text" name="prix_chien_jour" value="{{ $pension->prix_chien_jour }}">
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-euro-sign me-1"></i>Prix chat/jour</label>
                                        <input type="text" name="prix_chat_jour" value="{{ $pension->prix_chat_jour }}">
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-toggle-on me-1"></i>Actif</label>
                                        <div class="form-check">
                                            <input type="hidden" name="actif" value="0">
                                            <input type="checkbox" name="actif" value="1" {{ $pension->actif ? 'checked' : '' }}>
                                            <label>Pension active</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="edit-actions">
                                    <button type="submit" class="btn-apply">
                                        <i class="fas fa-check me-1"></i> Appliquer
                                    </button>
                                    <button type="button" class="cancel-btn">
                                        <i class="fas fa-times me-1"></i> Annuler
                                    </button>
                                </div>
                            </div>

                            @if ($errors->any())
                                <div class="error-box">
                                    @foreach ($errors->all() as $error)
                                        <p><i class="fas fa-exclamation-triangle me-1"></i>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif
                        </form>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-folder-open d-block"></i>
                    <p>Aucune pension associée</p>
                </div>
            @endif
        </div>
    @endforeach

    <script>
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                const parent = e.target.closest('.pension-card');
                parent.querySelector('.view-mode').style.display = 'none';
                parent.querySelector('.edit-mode').style.display = 'block';
            });
        });

        document.querySelectorAll('.cancel-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                const parent = e.target.closest('.pension-card');
                parent.querySelector('.edit-mode').style.display = 'none';
                parent.querySelector('.view-mode').style.display = 'block';
            });
        });
    </script>
@endsection

