@extends('layout')

@section('content')
    <div class="text-center">
        <h1 class="fw-bold">Bienvenue chez La Mise au Vert</h1>
        <p class="lead">Un réseau de 11 pensions en France pour le bien-être de vos animaux.</p>
        <a href="{{ route('pensions') }}" class="btn btn-success mt-3">Découvrir nos pensions</a>
    </div>

    <div class="mt-5">
        <h3>Contexte</h3>
        <p>
            Les pensions <strong>"La Mise au Vert"</strong> forment une SCOP SARL regroupant 11 établissements pour chiens et chats en France métropolitaine. Affiliée au <em>Syndicat National des professions du chien et du chat (SNPCC)</em> et partenaire de la <em>SPA</em>, notre entreprise veille au bien-être des animaux confiés par leurs propriétaires.
        </p>
    </div>

    <div class="mt-4">
        <h3>Objectifs</h3>
        <ul>
            <li>Offrir des services d’hébergement confortables et sécurisés.</li>
            <li>Assurer un suivi personnalisé pour chaque animal.</li>
            <li>Collaborer avec des partenaires de confiance (SPA, vétérinaires).</li>
        </ul>
    </div>
@endsection
