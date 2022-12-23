@extends('themes.master')
@section('title')
    chambres :: Youpigoo
@endsection
@section('content')
    <div class="px-4 container-fluid ">
        <div class="card">

            <div class="card-header">
                <h4>Chambres <a href="{{ url('Admin/chambres/ajouter-chambre') }}" class="btn btn-primary btn-sm float-end">
                        Ajouter une chambre</a> </h4>
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
                            <th scope="col">CATEGORIE CHAMBRE</th>
                            <th scope="col">HOTEL</th>
                            <th scope="col">PRIX</th>
                            <th scope="col">STATUS</th>
                            <th scope="col">DATE & HEURE</th>
                            <th scope="col">ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($chambres as $chambre)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $chambre->code_chambre }}</td>
                                <td>{{ $chambre->nom_chambre }}</td>
                                <td>{{ $chambre->categoriechammbres->libelle_categoriechambre }}</td>
                                <td>{{ $chambre->hotels->nom_hotel }}</td>
                                <td>{{ $chambre->prix_standard_chambre }}</td>
                                <td>
                                    @if ($chambre->status_chambre)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $chambre->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>


                                    <a href="{{ url('Admin/chambres/editer-chambre/' . $chambre->id_chambre) }}"
                                        class="btn btn-success"><i class="fa fa-edit"></i></a>

                                    <form method="GET"
                                        action="{{ url('Admin/chambres/delete-chambre/' . $chambre->id_chambre) }}"
                                        class="btn btn-danger"
                                        onsubmit="return confirm('Voulez-vous supprimer cette chambre de la liste?');">
                                        <input type="hidden" name="id_chambre" value="{{ $chambre->id_chambre }}">
                                        <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                    </form>


                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <br>
        <br>
        <br>
        <br>

        <div class="card">

            <div class="card-header">
                <h4>Chambres Reservées</h4>
            </div>

            <div class="card-body">
                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }} </div>
                @endif


                <table id="myDataTable" class="table table-bordered">

                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">NOM CHAMBRE</th>
                            <th scope="col">CATEGORIE CHAMBRE</th>
                            <th scope="col">HOTEL</th>
                            <th scope="col">PRIX</th>
                            <th scope="col">ETAT</th>
                            <th scope="col">DATE & HEURE</th>
                            <th scope="col">ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($chambres_reservees as $chambre)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $chambre->nom_chambre }}</td>
                                <td>{{ $chambre->categoriechammbres->libelle_categoriechambre }}</td>
                                <td>{{ $chambre->hotels->nom_hotel }}</td>
                                <td>{{ $chambre->prix_standard_chambre }}</td>
                                <td>
                                    @if ($chambre->status_reserver_chambre)
                                        <span class="badge bg-success">Reservée</span>
                                    @endif
                                </td>
                                <td>{{ $chambre->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>

                                    <a href="{{ url('Admin/chambres/voir-chambre/' . $chambre->id_chambre) }}"
                                        class="btn btn-success"><i class="fa fa-eyes"></i></a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>


    {{-- <section class="section">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body" style="overflow: auto;">
                                    <h5 class="card-title" style="text-align: center;font-size: 25px;">Listes des chambres
                                    </h5>
                                    <table class="table datatable">
                                        <thead>
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col">CODE CHAMBRE</th>
                                                <th scope="col">NOM</th>
                                                <th scope="col">CATEGORIE CHAMBRE</th>
                                                <th scope="col">HOTEL</th>
                                                <th scope="col">PRIX</th>
                                                <th scope="col">STATUS</th>
                                                <th scope="col">DATE & HEURE</th>
                                                <th scope="col">ACTIONS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($chambres as $chambre)
                                                <tr>
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td>{{ $chambre->code_chambre }}</td>
                                                    <td>{{ $chambre->nom_chambre }}</td>
                                                    <td>{{ $chambre->categoriechammbres->nom_categoriechambre }}</td>
                                                    <td>{{ $chambre->hotels->nom_hotel }}</td>
                                                    <td>{{ $chambre->prix_standard_chambre }}</td>
                                                    <td>
                                                        @if ($chambre->status_chambre)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $chambre->created_at->format('d/m/Y H:i:s') }}</td>
                                                    <td>
                                                        <button class="btn btn-success"
                                                            data-chambre="{{ $chambre->id_chambre }}" id="updateChambre">
                                                            <i class="bi bi-pen"></i>
                                                        </button>

                                                        <button class="btn btn-secondary"
                                                            data-chambre="{{ $chambre->id_chambre }}"
                                                            id="getInfosChambre">
                                                            <i class="bi bi-eye"></i>
                                                        </button>

                                                        <button class="btn btn-danger"
                                                            data-chambre="{{ $chambre->id_chambre }}" id="deteleChambre">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section> --}}
@endsection
