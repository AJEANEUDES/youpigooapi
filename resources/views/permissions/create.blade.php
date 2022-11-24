
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Créer une nouvelle permission</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('permissions.index') }}"> Retour</a>
        </div>
    </div>
</div>


@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Désolé!</strong> Il y a eu quelques problèmes avec votre entrée.<br><br>
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
@endif


{!! Form::open(array('route' => 'permissions.store','method'=>'POST')) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Nom:</strong>
            {!! Form::text('name', null, array('placeholder' => 'Nom','class' => 'form-control')) !!}
        </div>
    </div>
   
   
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-check form-check-inline">
            <strong>Rôle:</strong>
            <br/>
            @foreach($role as $value)
                <label class="form-check-label" for="inlineCheckbox1" >{{ Form::checkbox('role[]', $value->id, false, array('class' => 'name')) }}
                {{ $value->name }}</label>
            <br/>
            @endforeach
        </div>
    </div>


    
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Soumettre</button>
    </div>
</div>
{!! Form::close() !!}



@endsection
