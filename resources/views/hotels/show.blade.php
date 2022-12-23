@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2> Voir les détails d'un Hotel</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('hotels.index') }}"> Retour</a>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Nom de l'Hôtel:</strong>
            {{ $hotel->name }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Email:</strong>
            {{ $hotel->email_hotel }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Roles:</strong>
            @if(!empty($hotel->getRoleNames()))
                @foreach($hotel->getRoleNames() as $v)
                   {{ $v }}
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
