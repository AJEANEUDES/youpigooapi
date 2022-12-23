@extends('themes.master')
@section('title')
    Villes :: Youpigoo
@endsection
@section('content')


    <div class="px-4 container-fluid ">
        <div class="card">

            <div class="card-header">
                <h4>ville <a href="{{ url('Admin/villes/ajouter-ville') }}" class="btn btn-primary btn-sm float-end">
                        Ajouter une ville</a> </h4>
            </div>

            <div class="card-body">
                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }} </div>
                @endif

                <table id="myDataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col"> VILLE</th>
                            <th scope="col"> PAYS</th>
                            <th scope="col">IMAGE</th>
                            <th scope="col">STATUS</th>
                            <th scope="col">DATE & HEURE</th>
                            <th scope="col">ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($villes as $ville)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>

                                <td>{{ $ville->nom_ville }}</td>
                                <td>{{ $ville->nom_pays }}</td>
                                <td>
                                    <img src="{{ asset('storage/uploads/' . $ville->image_ville) }}" width="50px"
                                        height="50px" alt="">
                                </td>

                                <td>
                                    @if ($ville->status_ville)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $ville->created_at }}</td>


                                <td>
                                    <a href="{{ url('Admin/villes/editer-ville/' . $ville->id_ville) }}"
                                        class="btn btn-success"><i class="fa fa-edit"></i></a>


                                    <form method="GET" action="{{ url('Admin/villes/delete-ville/' . $ville->id_ville) }}"
                                        class="btn btn-danger"
                                        onsubmit="return confirm('Voulez-vous supprimer cette ville de la liste?');">
                                        <input type="hidden" name="id_ville" value="{{ $ville->id_ville }}">
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
