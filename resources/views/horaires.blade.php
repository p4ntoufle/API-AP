@extends('layout')

@section('content')
    <h2>Horaires d’ouverture</h2>

    <table class="table table-bordered mt-4">
        <thead class="table-success">
        <tr>
            <th>Jour</th>
            <th>Horaires de visite</th>
        </tr>
        </thead>
        <tbody>
        <tr><td>Lundi</td><td>8h30 - 12h / 14h30 - 19h</td></tr>
        <tr><td>Mardi</td><td>8h30 - 12h (après-midi fermé)</td></tr>
        <tr><td>Mercredi</td><td>8h30 - 12h / 14h30 - 19h</td></tr>
        <tr><td>Jeudi</td><td>8h30 - 12h / 14h30 - 19h</td></tr>
        <tr><td>Vendredi</td><td>8h30 - 12h / 14h30 - 19h</td></tr>
        <tr><td>Samedi</td><td>8h30 - 12h / 14h30 - 19h</td></tr>
        <tr><td>Dimanche & jours fériés</td><td>Fermé</td></tr>
        </tbody>
    </table>

    <p><strong>Dépôt / Enlèvement :</strong> uniquement sur rendez-vous.</p>
@endsection
