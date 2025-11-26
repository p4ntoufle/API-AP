@extends('layout')

@section('title', 'Vos factures — La Mise au Vert')

@section('content')
    <style>
        .factures-page {
            margin: -1rem -15px 0;
            padding: 0;
        }

        .factures-header {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            padding: 4rem 2rem;
            text-align: center;
            border-radius: 18px;
            margin-bottom: 2.5rem;
        }

        .factures-header h1 {
            font-size: 3rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 0.8rem;
            letter-spacing: -1px;
        }

        .factures-header p {
            font-size: 1.2rem;
            color: #4b5563;
            margin: 0 auto;
            max-width: 720px;
        }

        .invoice-number {
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #2d6a4f;
        }

        .facture-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        }

        .download-btn {
            border-width: 2px;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }

        .empty-state h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a1a1a;
        }

        .empty-state p {
            color: #6b7280;
            max-width: 420px;
            margin: 0.5rem auto 0;
        }
    </style>

    <div class="factures-page">
        <div class="factures-header">
            <h1>Vos factures de séjour</h1>
            <p>Retrouvez ici les factures PDF générées après les séjours de vos animaux. Elles sont classées du plus récent au plus ancien pour faciliter leur repérage.</p>
        </div>

        @guest
            <div class="card facture-card p-4 text-center">
                <h3 class="mb-3">Connectez-vous pour consulter vos factures</h3>
                <p class="text-muted mb-4">Les factures sont personnelles et liées à votre compte client. Connectez-vous pour télécharger vos documents.</p>
                <a href="{{ route('login') }}" class="btn btn-success px-4">
                    <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                </a>
            </div>
        @else
            @if($factures->isEmpty())
                <div class="card facture-card empty-state">
                    <div class="mb-3">
                        <i class="fas fa-file-invoice-dollar fa-3x text-success"></i>
                    </div>
                    <h3>Pas encore de facture</h3>
                    <p>Vous pourrez télécharger vos factures dès qu'un séjour sera terminé et validé par la pension.</p>
                </div>
            @else
                <div class="card facture-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date de facture</th>
                                        <th>Pension</th>
                                        <th>Numéro</th>
                                        <th>Fin de séjour</th>
                                        <th>Animaux</th>
                                        <th>Total TTC</th>
                                        <th class="text-end">Téléchargement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($factures as $facture)
                                        <tr>
                                            <td class="fw-semibold">{{ optional($facture->issued_at)->format('d/m/Y') ?? '—' }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $facture->pension->nom ?? 'Pension n°' . $facture->pension_id }}</div>
                                                <div class="text-muted small">Fin de séjour : {{ optional($facture->stay_end_at)->format('d/m/Y') ?? '—' }}</div>
                                            </td>
                                            <td><span class="invoice-number">{{ $facture->numero }}</span></td>
                                            <td>{{ optional($facture->stay_end_at)->format('d/m/Y') ?? '—' }}</td>
                                            <td>{{ $facture->animals_count ?? 1 }}</td>
                                            <td>
                                                @if($facture->total_ttc !== null)
                                                    {{ number_format((float) $facture->total_ttc, 2, ',', ' ') }} €
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('factures.download', $facture) }}" class="btn btn-outline-success btn-sm download-btn">
                                                    <i class="fas fa-file-download me-1"></i> Télécharger le PDF
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted small mt-3 mb-0">
                            Pour toute correction ou demande de facture regroupant plusieurs animaux, contactez directement la pension concernée.
                        </p>
                    </div>
                </div>
            @endif
        @endguest
    </div>
@endsection
