@extends('layout')

@section('content')
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="mb-0" style="color: var(--primary-green)">
                        <i class="fas fa-tag"></i> Gestion des Tarifs
                    </h1>
                    <a href="{{ route('pension.tarifs.store') }}" onclick="event.preventDefault(); document.getElementById('modal-add-tarif').style.display='block';" class="btn btn-success">
                        <i class="fas fa-plus"></i> Ajouter un tarif
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            @if ($tarifs->isEmpty())
                <div class="col-md-12">
                    <div class="card shadow text-center">
                        <div class="card-body py-5">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <h4 class="mt-3 text-muted">Aucun tarif défini</h4>
                            <p class="text-muted">Créez des tarifs pour vos types d'hébergement.</p>
                            <button onclick="document.getElementById('modal-add-tarif').style.display='block';" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Créer un tarif
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead style="background-color: var(--light-green); color: white;">
                                <tr>
                                    <th><i class="fas fa-home"></i> Type d'hébergement</th>
                                    <th><i class="fas fa-euro-sign"></i> Prix / jour</th>
                                    <th><i class="fas fa-calendar"></i> Mis à jour</th>
                                    <th class="text-end"><i class="fas fa-cog"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tarifs as $tarif)
                                    <tr>
                                        <td>
                                            <strong>{{ $tarif->typeGardiennage->libelle }}</strong>
                                        </td>
                                        <td>
                                            <span style="font-size: 1.1rem; color: var(--primary-green); font-weight: bold;">
                                                {{ number_format($tarif->prix, 2, ',', ' ') }} €
                                            </span>
                                        </td>
                                        <td>{{ $tarif->updated_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-end">
                                            <button onclick="editTarif({{ $tarif->id }}, '{{ $tarif->typeGardiennage->libelle }}', {{ $tarif->prix }})" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Modifier
                                            </button>
                                            <form action="{{ route('pension.tarifs.destroy', $tarif) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr ?')">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <a href="{{ route('pension.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour au dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Modal pour ajouter un tarif -->
    <div id="modal-add-tarif" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
        <div style="background:white; margin:5% auto; padding:2rem; border-radius:8px; width:90%; max-width:500px;">
            <h5 style="color: var(--primary-green); margin-bottom:1.5rem;">Ajouter un nouveau tarif</h5>
            <form action="{{ route('pension.tarifs.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="type_gardiennage_id" class="form-label">Type d'hébergement</label>
                    <select class="form-control" id="type_gardiennage_id" name="type_gardiennage_id" required>
                        <option value="">-- Sélectionner un type --</option>
                        @foreach ($typesGardiennage as $type)
                            @php
                                $existant = $tarifs->where('type_gardiennage_id', $type->id)->first();
                            @endphp
                            @if (!$existant)
                                <option value="{{ $type->id }}">{{ $type->libelle }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="prix" class="form-label">Prix / jour (€)</label>
                    <input type="number" class="form-control" id="prix" name="prix" step="0.01" min="0" required placeholder="0.00">
                </div>
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-end">
                    <button type="button" onclick="document.getElementById('modal-add-tarif').style.display='none';" class="btn btn-secondary">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead {
            border-radius: 8px 8px 0 0;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-success {
            background-color: var(--light-green);
            border-color: var(--light-green);
        }

        .btn-success:hover {
            background-color: var(--accent-green);
            border-color: var(--accent-green);
        }

        #modal-add-tarif {
            display: none;
        }
    </style>

    <script>
        function editTarif(id, libelle, prix) {
            // Créer un formulaire pour éditer
            const form = `
                <form action="/pension/tarifs/${id}" method="POST" style="display:inline;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="prix" value="${prix}">
                    <button type="submit" style="display:none;"></button>
                </form>
            `;
            // Pour simplifier, on crée une modal d'édition
            const prix_edit = prompt(`Modifier le tarif pour "${libelle}" (€):`, prix);
            if (prix_edit !== null && prix_edit !== '') {
                const editForm = document.createElement('form');
                editForm.method = 'POST';
                editForm.action = `/pension/tarifs/${id}`;
                editForm.innerHTML = `
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="prix" value="${prix_edit}">
                `;
                document.body.appendChild(editForm);
                editForm.submit();
            }
        }
    </script>
@endsection

