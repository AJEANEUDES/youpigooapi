@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Gestion des Hôtels</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('hotels.create') }}"> Créer un Nouvel Utilisateur</a>
        </div>
    </div>
</div>


@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif


<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>Nom</th>
        <th>Email</th>
        <th>Rôles</th>
        <th width="280px">Action</th>
    </tr>
@foreach ($data as $key => $hotel)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $hotel->name }}</td>
        <td>{{ $hotel->email_hotel }}</td>
        <td>
            @if(!empty($hotel->getRoleNames()))
                @foreach($hotel->getRoleNames() as $v)
                    {{ $v }}
                @endforeach
            @endif
        </td>
        <td>
            <a class="btn btn-info" href="{{ route('hotels.show',$hotel->id) }}">Voir</a>
            @can('edit-hotels')
          
            <a class="btn btn-primary" href="{{ route('hotels.edit',$hotel->id) }}">Editer</a>
            @endcan
           

            @can('delete-hotels')

                {!! Form::open(['method' => 'DELETE','route' => ['hotels.destroy', $hotel->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Supprimer', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}

            @endcan

        </td>
    </tr>
@endforeach
</table>

{!! $data->render() !!}


@endsection