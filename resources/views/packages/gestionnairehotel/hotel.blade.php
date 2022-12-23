@extends('themes.master')
@section('title')
    Gestionnaires d'Hôtel :: Youpigoo
@endsection
@section('content')
    <div class="px-4 container-fluid ">
        <div class="card">

            <div class="card-header">
                <h4>Gestionnaires d'Hôtel <a href="{{ url('Admin/gestionnairehotel/ajouter-gestionnairehotel') }}"
                        class="btn btn-primary btn-sm float-end">
                        Ajouter un Gestionnaire Hotel</a> </h4>
            </div>

            <div class="card-body">
                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }} </div>
                @endif

                <table id="myDataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">NOM</th>
                            <th scope="col">PRENOMS</th>
                            <th scope="col">NOM DE L'HOTEL</th>
                            <th scope="col">EMAIL DE L'HOTEL</th>
                            <th scope="col">IMAGE</th>
                            <th scope="col">ROLE</th>
                            <th scope="col">STATUS</th>
                            <th scope="col">DATE & HEURE</th>
                            <th scope="col">ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($gestionnaires as $gestionnaire)
                            @foreach ($hotels as $hotel)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>

                                    <td>{{ $gestionnaire->nom_user }}</td>
                                    <td>{{ $gestionnaire->prenoms_user }}</td>
                                    <td>{{ $hotel->nom_hotel }}</td>
                                    <td>{{ $hotel->email_hotel }}</td>
                                    <td>
                                        <img src="{{ asset('storage/uploads/' . $gestionnaire->image_user) }}"
                                            width="50px" height="50px" alt="">
                                    </td>

                                    <td>
                                        @if ($gestionnaire->roles_user)
                                            <span class="badge bg-danger">Hotel</span>
                                        @elseif ($gestionnaire->roles_user)
                                            <span class="badge bg-success">Admin</span>
                                        @elseif ($gestionnaire->roles_user)
                                            <span class="badge bg-warning">Compagnie</span>
                                        @else
                                            <span class="badge bg-info">Client</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($gestionnaire->status_user)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>

                                    <td>{{ $gestionnaire->created_at->format('d/m/Y à H:i:s') }}</td>


                                    <td>
                                        <a href="{{ url('Admin/gestionnairehotel/editer-gestionnairehotel/' . $gestionnaire->id) }}"
                                            class="btn btn-success"><i class="fa fa-edit"></i></a>


                                        <form method="GET"
                                            action="{{ url('Admin/gestionnairehotel/delete-gestionnairehotel/' . $gestionnaire->id) }}"
                                            class="btn btn-danger"
                                            onsubmit="return confirm('Voulez-vous supprimer ce Gestionnaire d\'hôtel de la liste?');">
                                            <input type="hidden" name="id_gestionnaire" value="{{ $gestionnaire->id }}">
                                            <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                        </form>

                                    </td>




                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>
@endsection
