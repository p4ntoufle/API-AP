@extends('layout')

@section('content')
    <h2>Nos pensions</h2>
    <div class="row">
        @foreach($pensions as $pension)
            <div class="col-md-4 mb-3">
                <div class="card p-3 shadow-sm">
                    <h5>{{ $pension->nom }}</h5>
                    <p>{{ $pension->ville }}</p>
                    <p><small>{{ $pension->adresse }}</small></p>
                </div>
            </div>
        @endforeach
    </div>
@endsection
