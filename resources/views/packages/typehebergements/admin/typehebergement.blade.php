@extends('themes.master')
@section('title')
    Type d'Hébergements :: Youpigoo
@endsection
@section('content')
    <div class="px-4 container-fluid ">
        <div class="card">

            <div class="card-header">
                <h4>Type Hébergement <a href="{{ url('Admin/types/ajouter-type') }}" class="btn btn-primary btn-sm float-end">
                        Ajouter un Type Hébergement</a> </h4>
            </div>

            <div class="card-body">
                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }} </div>
                @endif

                <table id="myDataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">NOM TYPE HEBERGEMENT</th>
                            <th scope="col">NOM PAYS</th>
                            <th scope="col">NOM VILLE</th>
                            <th scope="col">IMAGE</th>
                            <th scope="col">STATUS</th>
                            <th scope="col">DATE & HEURE</th>
                            <th scope="col">ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($typehebergements as $type)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>

                                <td>{{ $type->nom_typehebergement }}</td>
                                <td>{{ $type->nom_pays }}</td>
                                <td>{{ $type->nom_ville }}</td>
                                <td>
                                    <img src="{{ asset('storage/uploads/' . $type->image_typehebergement) }}" width="50px"
                                        height="50px" alt="">
                                </td>

                                <td>
                                    @if ($type->status_typehebergement)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $type->created_at }}</td>


                                <td>
                                    <a href="{{ url('Admin/types/editer-type/' . $type->id_typehebergement) }}"
                                        class="btn btn-success"><i class="fa fa-edit"></i></a>


                                    <form method="GET"
                                        action="{{ url('Admin/types/delete-type/' . $type->id_typehebergement) }}"
                                        class="btn btn-danger"
                                        onsubmit="return confirm('Voulez-vous supprimer ce type de la liste?');">
                                        <input type="hidden" name="id_typehebergement" value="{{ $type->id_typehebergement }}">
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
