<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fiches</title>
</head>
<body>
    @foreach($users as $user)
        <h1>{{ $user->name }}</h1>
        @if($user->pensions->count())
            <ul>
                @foreach($user->pensions as $pension)
                    <div class="pension-item">
                        <form method="POST" action="/pensions/{{ $pension->id }}/update">
                            @csrf
                            @method('PUT')

                            <div class="view-mode">
                                <h4>{{ $pension->nom ?? 'Nom non défini' }}</h4>
                                <p>Adresse: {{ $pension->adresse ?? '-' }}</p>
                                <p>Ville: {{ $pension->ville ?? '-' }}</p>
                                <p>Capacité chiens: {{ $pension->capacite_chiens ?? 0 }}</p>
                                <p>Capacité chats: {{ $pension->capacite_chats ?? 0 }}</p>
                                <p>Email: {{ $pension->email ?? '-' }}</p>
                                <p>Prix chien/jour: {{ $pension->prix_chien_jour ?? '-' }}€</p>
                                <p>Prix chat/jour: {{ $pension->prix_chat_jour ?? '-' }}€</p>
                                <button type="button" class="edit-btn">Modifier</button>
                            </div>

                            <div class="edit-mode" style="display:none;">
                                <label>Nom</label>
                                <input type="text" name="nom" value="{{ $pension->nom }}">

                                <label>Adresse</label>
                                <input type="text" name="adresse" value="{{ $pension->adresse }}">

                                <label>Ville</label>
                                <input type="text" name="ville" value="{{ $pension->ville }}">

                                <label>Capacité chiens</label>
                                <input type="number" name="capacite_chiens" value="{{ $pension->capacite_chiens }}">

                                <label>Capacité chats</label>
                                <input type="number" name="capacite_chats" value="{{ $pension->capacite_chats }}">

                                <label>Email</label>
                                <input type="email" name="email" value="{{ $pension->email }}">

                                <label>Prix chien/jour</label>
                                <input type="text" name="prix_chien_jour" value="{{ $pension->prix_chien_jour }}">

                                <label>Prix chat/jour</label>
                                <input type="text" name="prix_chat_jour" value="{{ $pension->prix_chat_jour }}">

                                <button type="submit">Sauvegarder</button>
                                <button type="button" class="cancel-btn">Annuler</button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </ul>
        @else
            <p>Aucune pension associée</p>
        @endif
    @endforeach
</body>
<script>
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        const parent = e.target.closest('.pension-item');
        parent.querySelector('.view-mode').style.display = 'none';
        parent.querySelector('.edit-mode').style.display = 'block';
    });
});

document.querySelectorAll('.cancel-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        const parent = e.target.closest('.pension-item');
        parent.querySelector('.edit-mode').style.display = 'none';
        parent.querySelector('.view-mode').style.display = 'block';
    });
});

document.querySelectorAll('.pension-item form').forEach(form => {
    form.addEventListener('submit', e => {
        e.preventDefault();
        alert('Les modifications ont été sauvegardées.');
    });
});
</script>
</html>
