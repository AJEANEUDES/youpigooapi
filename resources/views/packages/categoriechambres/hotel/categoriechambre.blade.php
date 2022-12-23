@extends('themes.main')
@section('title')
    Catégories de Chambres :: Youpigoo
@endsection


@section('main')
    <div class="pagetitle">
        <h1 style="font-size: 25px;">
            Catégories de chambres
            <i class="bx bx-plus-circle" data-bs-toggle="modal" data-bs-target="#addCategorieChambreModal"
                style="position: relative;top: 10px;font-size: 40px;color: #2DAF07;cursor: pointer;"></i>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Accueil</a></li>
                <li class="breadcrumb-item active">Catégories de chambres</li>
            </ol>
        </nav>
    </div>



    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body" style="overflow: auto;">
                        <h5 class="card-title" style="text-align: center;font-size: 25px;">Listes des administrateurs
                        </h5>

                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif



                        <div class="table-responsive">
                            <table id="example" class="table style-1">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">CODE CATEGORIE CHAMBRE</th>
                                        <th scope="col">LIBELLE</th>
                                        <th scope="col">PRIX</th>
                                        <th scope="col">STATUS</th>
                                        <th scope="col">DATE & HEURE</th>
                                        <th scope="col">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categoriechambres as $categoriechambre)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $categoriechambre->code_categoriechambre }}</td>
                                            <td>{{ $categoriechambre->libelle_categoriechambre }}</td>
                                            <td>{{ $categoriechambre->prix_estimatif_categoriechambre }}</td>

                                            <td>
                                                @if ($categoriechambre->status_categoriechambre)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $categoriechambre->created_at }}</td>
                                            <td>
                                                <button class="btn btn-success"
                                                    data-categoriechambre="{{ encodeId($categoriechambre->id_categoriechambre) }}"
                                                    id="updateCategorieChambre">
                                                    <i class="bi bi-pen"></i>
                                                </button>
                                                <button class="btn btn-secondary"
                                                    data-categoriechambre="{{ encodeId($categoriechambre->id_categoriechambre) }}"
                                                    id="getInfosCategorieChambre">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-danger"
                                                    data-categoriechambre="{{ encodeId($categoriechambre->id_categoriechambre) }}"
                                                    id="deleteCategorieChambre">
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
        </div>
    </section>

    {{-- Modal pour voir plus d'info sur la catégorie de chambre --}}
    <div class="modal fade" id="infosCategoriechambreModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">INFORMATION D'UNE CATEGORIE DE CHAMBRE</h4>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6 text-center">
                        <span>Nom de la catégorie de chambre</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewLibelleCategorieChambre"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Code de la catégorie de la chambre</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewCodeCategorieChambre"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Date & Heure</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewDateCreatedCategorieChambre"></div>
                    </div>

                    <div class="col-md-6 text-center">
                        <span>Description de la catégorie de la chambre</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewDescriptionCategorieChambre"></div>
                    </div>

                    <div class="col-md-6 text-center">
                        <span>Prix estimatif de la catégorie de chambre</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewPrixCategorieChambre"></div>
                    </div>

                    <div class="col-md-6 text-center">
                        <span>Status</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewStatusCategorieChambre"></div>
                    </div>
                    <div class="col-md-12 text-center">
                        <span>Opérateur (crée par)</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="operateur"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" style="border-radius: 10px;" class="btn btn-secondary btn-md"
                        data-bs-dismiss="modal">
                        <i class="bx bx-exit"></i> Quitter
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal pour modifier la catégorie de la chambres --}}
    <div class="modal fade" id="updateCategorieChambreModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('categoriechambres.update.gestion') }}" method="post"
                    id="categoriechambres-update-gestion" enctype="multipart/form-data">
                    @csrf
                    <div class="update-categoriechambre"></div>
                    <div class="modal-header">
                        <h4 class="modal-title">MISE A JOUR D'UNE CATEGORIE DE CHAMBRE</h4>
                    </div>
                    <div class="modal-body">
                        <label for="libelle_categoriechambre" class="form-label">Nom de la categorie de chambre</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" name="libelle_categoriechambre"
                            id="libelle_categoriechambre" value="" class="form-control form-control-lg" />

                        <label for="prix_estimatif_categoriechambre" class="form-label">Prix de la categorie de
                            chambre</label>
                        <input type="number" min="0" style="height: 55px;border-radius: 10px;"
                            class="form-control form-control-lg" name="prix_estimatif_categoriechambre"
                            id="prix_estimatif_categoriechambre">

                        <label for="image_categoriechambre" class="form-label">Image de la catégorie de chambre</label>
                        <input type="file" style="height: 55px;border-radius: 10px;"
                            class="form-control form-control-lg" name="image_categoriechambre"
                            id="image_categoriechambre" />
                    </div>
                    <div class="col-md-6" id="viewImageCategorieChambre"></div>


                    <label for="status_categoriechambre" class="form-label mt-3">Status de la categorie de
                        chambre</label>
                    <select style="height: 55px;border-radius: 10px;" name="status_categoriechambre"
                        id="status_categoriechambre" class="form-control form-control-lg">
                    </select>
            </div>
            <div class="modal-footer">
                <button type="button" style="border-radius: 10px;" class="btn btn-danger btn-md"
                    data-bs-dismiss="modal"> <i class="bx bx-exit"></i> Annuler</button>
                <button type="submit" style="border-radius: 10px;" class="btn btn-success btn-md"><i
                        class="bx bx-save"></i>
                    Enregistrer</button>
            </div>
            </form>
        </div>
    </div>
    </div>

    {{-- Modal pour supprimer cat chambre --}}
    <div class="modal fade" id="deleteCategorieChambreModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">SUPPRESSION D'UNE CATEGORIE DE CHAMBRE</h4>
                </div>
                <div class="modal-body">
                    <p style="text-align: center;" id="viewMessageDeleteCategorieChambre"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" style="border-radius: 10px;" class="btn btn-secondary btn-md"
                        data-bs-dismiss="modal"> <i class="bx bx-exit"></i> Non</button>
                    <form action="{{ route('categoriechambres.delete.gestion') }}" method="post"
                        id="categoriechambres-delete-gestion">
                        @csrf
                        <div id="formDeleteCategorieChambre"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('packages.categoriechambres.gestionnaire.categoriechambreModal')
@endsection
@push('script-find-categoriechambre')
    <script>
        //Mettre a jour une voiture grace a l'identifiant
        $(document).on('click', '#updateCategorieChambre', function() {
            let id_categoriechambre = $(this).data('categoriechambre');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                type: 'GET',
                url: '{{ route('categoriechambres.info.gestion') }}',
                dataType: 'JSON',
                data: {
                    id_categoriechambre: id_categoriechambre
                },
                success: (response) => {
                    $("#updateCategorieChambreModal").find('input[name="libelle_categoriechambre"]')
                        .val(response.libelle_categoriechambre);
                    if (response.status_categoriechambre) {
                        $("#status_categoriechambre").html(
                            '<option value="1" selected>Active</option><option value="0">Inactive</option>'
                        );
                    } else {
                        $("#status_categoriechambre").html(
                            '<option value="1">Active</option><option value="0" selected>Inactive</option>'
                        );
                    }
                    $(".update-categoriechambre").html(
                        '<input type="hidden" name="id_categoriechambre" value="' + response
                        .id_categoriechambre + '">');
                  
                        $("#viewImageCategorieChambre").html('<img width="100" height="100" src="' +
                        response.image_categoriechambre + '" />');

                    $("#updateCategorieChambreModal").modal("show");
                },
                error: (error) => {
                    console.log('Response Erreur Update Catégorie chambre')
                    console.log(error)
                }
            })
        })

        //Pour obtenir les informations de la catégorie de chambre grace a l'identifiant
        $(document).on('click', '#getInfosCategorieChambre', function() {
            let id_categoriechambre = $(this).data('categoriechambre');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                type: 'GET',
                url: '{{ route('categoriechambres.info.gestion') }}',
                dataType: 'JSON',
                data: {
                    id_categoriechambre: id_categoriechambre
                },
                success: (response) => {
                    let created_at = new Date().toLocaleDateString("fr");
                    $("#viewLibelleCategorieChambre").html(response.libelle_categoriechambre);
                    $("#viewDescriptionCategorieChambre").html(response.description_categoriechambre);
                    $("#viewPrixCategorieChambre").html(response.prix_estimatif_categoriechambre);
                    $("#viewCodeCategorieChambre").html(response.code_categoriechambre);
                    $("#viewDateCreatedCategorieChambre").html(new Date(response.created_at)
                        .toLocaleDateString());
                    if (response.status_categoriechambre) {
                        $("#viewStatusCategorieChambre").html(
                            '<span class="badge bg-success">Active</span>');
                    } else {
                        $("#viewStatusCategorieChambre").html(
                            '<span class="badge bg-danger">Inactive</span>');
                    }
                    $("#operateur").html(response.nom_user + " " + response.prenoms_user);

                    $("#infosCategoriechambreModal").modal('show');
                },
                error: (error) => {
                    console.log("Response error")
                    console.log(error)
                }

            })
        })

        //Supprimer une catégorie de chambre grace a l'identifiant
        $(document).on('click', '#deleteCategorieChambre', function() {
            let id_categoriechambre = $(this).data('categoriechambre');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                type: 'GET',
                url: '{{ route('categoriechambres.info.gestion') }}',
                dataType: 'JSON',
                data: {
                    id_categoriechambre: id_categoriechambre
                },
                success: (response) => {
                    $("#viewMessageDeleteCategorieChambre").html(
                        'Etes-vous sur de vouloir supprimer la catégorie de chambre <strong>' +
                        response
                        .libelle_categoriechambre + '</strong>?');
                    $("#formDeleteCategorieChambre").html(
                        '<input type="hidden" name="id_categoriechambre" value="' +
                        response.id_categoriechambre +
                        '"> <button type="submit" style="border-radius: 10px;" class="btn btn-danger btn-md"><i class="bx bx-trash"></i> Oui</button>'
                    );
                    $("#deleteCategorieChambreModal").modal('show');
                },
                error: (error) => {
                    console.log('Response Erreur Delete Catégorie Categorie chambre')
                    console.log(error)
                }
            })
        })
    </script>
@endpush
