<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Visa - Documents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eef2f7;
            font-family: 'Segoe UI', sans-serif;
        }
        .logo-container {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        .logo-container img {
            max-width: 240px;
            height: auto;
        }
        .main-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 10px;
        }
        .subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: #7f8c8d;
            margin-bottom: 30px;
        }
        .btn-nav {
            border-radius: 30px;
            padding: 8px 18px;
            font-weight: 500;
        }
        .card-style {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
            margin-bottom: 30px;
        }
        .form-control {
            border-radius: 8px;
        }
        .table thead {
            background-color: #f1f3f5;
        }
        .table td, .table th {
            vertical-align: middle;
            text-align: center;
        }
        .table td {
            background-color: #fff;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.85rem;
            border-radius: 6px;
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
<div class="container py-4">

    <div class="logo-container">
        <img src="{{ asset('images/logo-2.png') }}" alt="Logo">
    </div>

    <div class="main-title">BUREAU D'ORDRE</div>
    <div class="subtitle">DAI - Visa</div>

    <div class="d-flex justify-content-between align-items-center my-4 flex-wrap gap-3">
        <div>
            <a href="{{ route('depart.index') }}" class="btn btn-outline-primary btn-nav me-2">📤 Départ</a>
            <a href="{{ route('arrivee.index') }}" class="btn btn-outline-dark btn-nav me-2">📥 Arrivée</a>
            <a href="{{ route('visa.index') }}" class="btn btn-outline-success btn-nav">📝 Visa</a> 
            <a href="{{ route('departconfidentiel.index') }}" class="btn btn-outline-danger btn-nav">🔒 Départ Confidentiel</a>
            <a href="{{ route('departministerielle.index') }}" class="btn btn-outline-primary btn-nav">🏛️ Départ Ministérielle</a>
        </div>

        <form action="{{ route('visa.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Rechercher..." value="{{ request('search') }}">
            <button class="btn btn-success" type="submit">🔍</button>
        </form>
    </div>

    <div class="card-style">
        <h4 class="mb-3">📝 Documents de visa</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ isset($doc) ? route('visa.update', $doc->id) : route('visa.store') }}" enctype="multipart/form-data" class="row g-3">
            @csrf
            @if(isset($doc)) @method('PUT') @endif

            <div class="col-md-4">
                <input type="text" name="numero" class="form-control" placeholder="Numéro" value="{{ $doc->numero ?? '' }}" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="destinataire" class="form-control" placeholder="Destinataire" value="{{ $doc->destinataire ?? '' }}" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="sujet" class="form-control" placeholder="Objet" value="{{ $doc->sujet ?? '' }}" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="service_concerne" class="form-control" placeholder="Service concerné" value="{{ $doc->service_concerne ?? '' }}" required>
            </div>
            <div class="col-md-4">
                <input type="date" name="date" class="form-control" value="{{ $doc->date ?? '' }}" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="numero_correspondance" class="form-control" placeholder="N° d'envoi" value="{{ $doc->numero_correspondance ?? '' }}" required>
            </div>

            <!-- Champ fichier -->
            <div class="col-md-6">
                <label for="fichier" class="form-label">Fichier (pdf, jpg, png)</label>
                <input type="file" name="fichier" class="form-control" accept=".pdf,.jpg,.jpeg,.png" />
                @if(isset($doc) && $doc->fichier)
                    <small>Fichier actuel :
                        <a href="{{ asset('storage/fichiers/' . $doc->fichier) }}" target="_blank">{{ $doc->fichier }}</a>
                    </small>
                @endif
                @error('fichier')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn {{ isset($doc) ? 'btn-warning' : 'btn-success' }}">
                    {{ isset($doc) ? 'Mettre à jour' : 'Ajouter' }}
                </button>
                @if(isset($doc))
                    <a href="{{ route('visa.index') }}" class="btn btn-secondary ms-2">Annuler</a>
                @endif
            </div>
        </form>
    </div>

    <div class="card-style">
        <h5 class="mb-3">📋 Liste des documents</h5>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Destinataire</th>
                    <th>Objet</th>
                    <th>Service concerné</th>
                    <th>Date</th>
                    <th>N° d'envoi</th>
                    <th>Fichier</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($documents as $doc)
                    <tr>
                        <td>{{ $doc->numero }}</td>
                        <td>{{ $doc->destinataire }}</td>
                        <td>{{ $doc->sujet }}</td>
                        <td>{{ $doc->service_concerne }}</td>
                        <td>{{ $doc->date }}</td>
                        <td>{{ $doc->numero_correspondance }}</td>
                        <td>
                            @if($doc->fichier)
                                <a href="{{ asset('storage/fichiers/' . $doc->fichier) }}" target="_blank">Voir</a>
                            @else
                                Aucun
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('visa.edit', $doc->id) }}" class="btn btn-sm btn-warning me-1">✏️</a>
                            <form action="{{ route('visa.destroy', $doc->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

