@extends('layout')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" style="color: var(--primary-green)">
                <i class="fas fa-list-check"></i> Options de ma pension
            </h1>
            <a href="{{ route('pension.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Retour dashboard
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header"><strong>Ajouter une option</strong></div>
            <div class="card-body">
                <form method="POST" action="{{ route('pension.options.store') }}" class="row g-3">
                    @csrf
                    <div class="col-md-7">
                        <label class="form-label" for="libelle">Libellé</label>
                        <input id="libelle" type="text" name="libelle" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="tarif">Tarif (EUR)</label>
                        <input id="tarif" type="number" step="0.01" min="0" name="tarif" class="form-control" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-plus"></i> Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><strong>Options existantes</strong></div>
            <div class="card-body p-0">
                @if($options->isEmpty())
                    <p class="p-3 mb-0 text-muted">Aucune option pour le moment.</p>
                @else
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Libellé</th>
                                    <th>Tarif</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($options as $option)
                                    <tr>
                                        <td>
                                            <form method="POST" action="{{ route('pension.options.update', $option) }}" class="row g-2">
                                                @csrf
                                                @method('PUT')
                                                <div class="col-md-8">
                                                    <input type="text" name="libelle" value="{{ $option->libelle }}" class="form-control" required>
                                                </div>
                                        </td>
                                        <td style="min-width: 160px;">
                                                <input type="number" step="0.01" min="0" name="tarif" value="{{ $option->tarif }}" class="form-control" required>
                                        </td>
                                        <td class="text-end" style="min-width: 230px;">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-save"></i> Modifier
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('pension.options.destroy', $option) }}" class="d-inline" onsubmit="return confirm('Supprimer cette option ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
