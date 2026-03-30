@extends('layout')

@section('content')
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="mb-0" style="color: var(--primary-green)">
                        <i class="fas fa-cube"></i> Gestion des Boxes
                    </h1>
                    <a href="{{ route('pension.boxes.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Ajouter une box
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

        <div class="row">
            @if ($boxes->isEmpty())
                <div class="col-md-12">
                    <div class="card shadow text-center">
                        <div class="card-body py-5">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <h4 class="mt-3 text-muted">Aucune box</h4>
                            <p class="text-muted">Créez vos premières boxes pour gérer vos hébergements.</p>
                            <a href="{{ route('pension.boxes.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Créer une box
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead style="background-color: var(--light-green); color: white;">
                                <tr>
                                    <th><i class="fas fa-cube"></i> ID Box</th>
                                    <th><i class="fas fa-ruler-combined"></i> Superficie (m²)</th>
                                    <th><i class="fas fa-calendar"></i> Créée le</th>
                                    <th class="text-end"><i class="fas fa-cog"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($boxes as $box)
                                    <tr>
                                        <td>
                                            <span class="badge" style="background-color: var(--primary-green);">
                                                #{{ $box->id }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($box->superficie)
                                                <strong>{{ number_format($box->superficie, 2, ',', ' ') }} m²</strong>
                                            @else
                                                <span class="text-muted">Non définie</span>
                                            @endif
                                        </td>
                                        <td>{{ $box->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('pension.boxes.edit', $box) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                            <form action="{{ route('pension.boxes.destroy', $box) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette box ?')">
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
            transform: translateX(5px);
        }

        .badge {
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
        }

        .btn-success {
            background-color: var(--light-green);
            border-color: var(--light-green);
        }

        .btn-success:hover {
            background-color: var(--accent-green);
            border-color: var(--accent-green);
        }
    </style>
@endsection

