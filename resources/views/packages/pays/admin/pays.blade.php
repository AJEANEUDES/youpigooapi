@extends('themes.master')
@section('title')
    pays :: Youpigoo
@endsection
@section('content')
    @extends('themes.master')
@section('title')
    pays :: Youpigoo
@endsection
@section('content')
    


<div class="px-4 container-fluid ">
        <div class="card">

            <div class="card-header">
                <h4>pays <a href="{{ url('Admin/pays/ajouter-pays') }}" class="btn btn-primary btn-sm float-end">
                        Ajouter un Pays</a> </h4>
            </div>

            <div class="card-body">
                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }} </div>
                @endif

                <table id="myDataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">NOM PAYS</th>
                            <th scope="col">IMAGE</th>
                            <th scope="col">STATUS</th>
                            <th scope="col">DATE & HEURE</th>
                            <th scope="col">ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>


                        @foreach ($pays as $payskey)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>

                                <td>{{ $payskey->nom_pays }}</td>

                                <td>
                                    <img src="{{ asset('storage/uploads/' . $payskey->image_pays) }}" width="50px"
                                        height="50px" alt="">
                                </td>

                                <td>
                                    @if ($payskey->status_pays)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $payskey->created_at }}</td>


                                <td>
                                    <a href="{{ url('Admin/pays/editer-pays/' . $payskey->id_pays) }}"
                                        class="btn btn-success"><i class="fa fa-edit"></i></a>


                                    <form method="GET" action="{{ url('Admin/pays/delete-pays/' . $payskey->id_pays) }}"
                                        class="btn btn-danger"
                                        onsubmit="return confirm('Voulez-vous supprimer ce pays de la liste?');">
                                        <input type="hidden" name="id_pays" value="{{ $payskey->id_pays }}">
                                        <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                    </form>

                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>



@endsection



