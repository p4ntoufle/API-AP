@extends('layout')

@section('content')
    <h2>Nous contacter</h2>
    <p>Une question, une réservation ou une visite ? Contactez-nous facilement :</p>

    <form class="mt-4">
        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" id="name" class="form-control" placeholder="Votre nom complet">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" id="email" class="form-control" placeholder="votre@mail.com">
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea id="message" class="form-control" rows="4" placeholder="Votre message..."></textarea>
        </div>
        <button type="submit" class="btn btn-success">Envoyer</button>
    </form>
@endsection
