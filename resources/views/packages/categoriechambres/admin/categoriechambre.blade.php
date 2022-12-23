@extends('themes.master')
@section('title')
    Catégories de Chambres :: Youpigoo
@endsection


@section('content')
    <div class="px-4 container-fluid ">
        <div class="card">

            <div class="card-header">
                <h4>Catégories de chambre <a href="{{ url('Admin/categoriechambres/ajouter-categoriechambre') }}"
                        class="btn btn-primary btn-sm float-end">
                        Ajouter une catégorie de chambre</a> </h4>
            </div>

            <div class="card-body">
                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }} </div>
                @endif

                <table id="myDataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">LIBELLE CATEGORIE CHAMBRE</th>
                            <th scope="col">PRIX</th>
                            <th scope="col">STATUS</th>
                            <th scope="col">IMAGE</th>
                            <th scope="col">DATE & HEURE</th>
                            <th scope="col">ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($categoriechambres as $categoriechambre)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>

                                <td>{{ $categoriechambre->libelle_categoriechambre }}</td>
                                <td>{{ $categoriechambre->prix_estimatif_categoriechambre }}</td>

                                <td>
                                    @if ($categoriechambre->status_categoriechambre)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    <img src="{{ asset('storage/uploads/' . $categoriechambre->image_categoriechambre) }}"
                                        width="50px" height="50px" alt="">
                                </td>



                                <td>{{ $categoriechambre->created_at->format('d/m/Y à H:i:s') }}</td>



                                <td>
                                    <a href="{{ url('Admin/categoriechambres/editer-categoriechambre', $categoriechambre->id_categoriechambre) }}"
                                        class="btn btn-success"><i class="fa fa-edit"></i></a>


                                    <form method="GET"
                                        action="{{ url('Admin/categoriechambres/delete-categoriechambre', $categoriechambre->id_categoriechambre) }}"
                                        class="btn btn-danger"
                                        onsubmit="return confirm('Voulez-vous supprimer cet categoriechambre de la liste?');">
                                        <input type="hidden" name="id_categoriechambre"
                                            value="{{ $categoriechambre->id_categoriechambre }}">
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
