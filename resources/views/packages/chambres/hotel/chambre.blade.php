@extends('themes.main')
@section('title')
    chambres :: Youpigoo
@endsection
@section('main')
    <div class="pagetitle">
        <h1 style="font-size: 25px;">
            chambres
            <i class="bx bx-plus-circle" data-bs-toggle="modal" data-bs-target="#addChambreModal"
                style="position: relative;top: 10px;font-size: 40px;color: #2DAF07;cursor: pointer;"></i>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Accueil</a></li>
                <li class="breadcrumb-item active">chambres</li>
            </ol>
        </nav>
    </div>

    <section class="section">
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
                                                data-chambre="{{ encodeId($chambre->id_chambre) }}" id="updateChambre">
                                                <i class="bi bi-pen"></i>
                                            </button>

                                            <button class="btn btn-secondary"
                                                data-chambre="{{ encodeId($chambre->id_chambre) }}" id="getInfosChambre">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            <button class="btn btn-danger"
                                                data-chambre="{{ encodeId($chambre->id_chambre) }}" id="deteleChambre">
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
    </section>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body" style="overflow: auto;">
                        <h5 class="card-title" style="text-align: center;font-size: 25px;">Listes des chambres reservées
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
                                    <th scope="col">ETAT</th>
                                    <th scope="col">DATE & HEURE</th>
                                    <th scope="col">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($chambres_reservees as $chambre)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $chambre->code_chambre }}</td>
                                        <td>{{ $chambre->nom_chambre }}</td>
                                        <td>{{ $chambre->categoriechammbres->nom_categoriechambre }}</td>
                                        <td>{{ $chambre->hotels->nom_hotel }}</td>
                                        <td>{{ $chambre->prix_standard_chambre }}</td>
                                        <td>
                                            @if ($chambre->status_reserver_chambre)
                                                <span class="badge bg-success">Reservée</span>
                                            @endif
                                        </td>
                                        <td>{{ $chambre->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>
                                            {{-- <button class="btn btn-success" data-chambre="{{ encodeId($chambre->id_chambre) }}" id="updatechambre">
                                        <i class="bi bi-pen"></i>
                                    </button> --}}

                                            <button class="btn btn-secondary"
                                                data-chambre="{{ encodeId($chambre->id_chambre) }}" id="getInfosChambre">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            {{-- <button class="btn btn-danger" data-chambre="{{ encodeId($chambre->id_chambre) }}" id="detelechambre">
                                        <i class="bi bi-trash"></i>
                                    </button> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal pour voir plus d'info sur la chambre --}}
    <div class="modal fade" id="infosChambreModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">INFORMATION D'UNE chambre</h4>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6 text-center">
                        <span>Nom</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewNomChambre"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Categorie chambre</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewCatChambre"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Hotel</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewHotelChambre"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Classe de la chambre</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewClasseChambre"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Prix de la chambre</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewPrixChambre"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Nombre de lits dans la chambre</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewLitsChambre"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Nombre de places dans la chambre</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewPlacesChambre"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Societe</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewSocieteChambre"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Ville</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewVilleChambre"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Pays</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewPaysChambre"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>TypeHebergement</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewTypeChambre"></div>
                    </div>

                    <div class="col-md-6 text-center">
                        <span>Code de la Chambre</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewCodeChambre"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Date & Heure</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewDateCreatedChambre"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Etat de Reservation</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewStatusRes"></div>
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

    {{-- Modal pour mettre a jour une chambre donnee --}}
    <div class="modal fade" id="updateChambreModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('chambres.update.gestion') }}" method="post"
                    enctype="multipart/form-data"id="chambres-update-gestion">
                    @csrf
                    <div class="update-chambre"></div>
                    <div class="modal-header">
                        <h4 class="modal-title">MISE A JOUR DE LA CHAMBRE</h4>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label for="categoriechambre_id" class="form-label">Catégorie de la chambre</label>
                            <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                                name="categoriechambre_id" id="categoriechambre_id">
                                @foreach ($categoriechambres as $categoriechambre)
                                    <option value="{{ $categoriechambre->id_categoriechambre }}">
                                        {{ $categoriechambre->libelle_categoriechambre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="hotel_id" class="form-label">Hotel de la chambre</label>
                            <select style="height: 55px;border-radius: 10px;text-transform: uppercase;"
                                class="form-control form-control-lg" name="hotel_id" id="hotel_id">
                                @foreach ($hotels as $hotel)
                                    <option value="{{ $hotel->id_hotel }}">{{ $hotel->nom_hotel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="ville_id" class="form-label">Ville de la chambre</label>
                            <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                                name="ville_id" id="ville_id">
                                @foreach ($villes as $ville)
                                    <option value="{{ $ville->id_ville }}">{{ $ville->nom_ville }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="pays_id" class="form-label">Pays de la chambre</label>
                            <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                                name="pays_id" id="pays_id">
                                @foreach ($pays as $payskey)
                                    <option value="{{ $payskey->id_pays }}">{{ $payskey->nom_pays }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="pays_id" class="form-label">Type </label>
                            <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                                name="pays_id" id="pays_id">
                                @foreach ($typeheergements as $type)
                                    <option value="{{ $type->id_typehebergement }}">{{ $type->nom_typehebergement }}
                                    </option>
                                @endforeach
                            </select>
                        </div>




                        <div class="col-md-4">
                            <label for="classe_chambre" class="form-label">Classe de la chambre</label>
                            <input type="text" style="height: 55px;border-radius: 10px;"
                                class="form-control form-control-lg" name="classe_chambre" id="classe_chambre">
                        </div>

                        {{-- <div class="col-md-4">
                            <label for="carburant_chambre" class="form-label">Carburant</label>
                            <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                                name="carburant_chambre" id="carburant_chambre">
                                <option selected disabled>-- Selectionnez --</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Essence">Essence</option>
                                <option value="Hybride">Hybride</option>
                                <option value="Electrique">Electrique</option>
                                <option value="GLP">GLP</option>
                                <option value="GNV">GNV</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="boite_vitesse_chambre" class="form-label">Boite de vitesse</label>
                            <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                                name="boite_vitesse_chambre" id="boite_vitesse_chambre">
                                <option selected disabled>-- Selectionnez --</option>
                                <option value="Manuelle">Manuelle</option>
                                <option value="Automatique">Automatique</option>
                                <option value="Robotisée">Robotisée</option>
                            </select>
                        </div>
                         --}}


                        <div class="col-md-4">
                            <label for="nombre_places_chambre" class="form-label">Nombre de place</label>
                            <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                                name="nombre_places_chambre" id="nombre_places_chambre">
                                <option selected disabled>-- Selectionnez --</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="9">9</option>
                            </select>
                        </div>



                        <div class="col-md-4">
                            <label for="nombre_lits_chambre" class="form-label">Nombre de Lits</label>
                            <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                                name="nombre_lits_chambre" id="nombre_lits_chambre">
                                <option selected disabled>-- Selectionnez --</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>

                        {{-- 

                        <div class="col-md-4">
                            <label for="interieur_chambre" class="form-label">Interieur de la chambre (couleur)</label>
                            <input type="text" style="height: 55px;border-radius: 10px;"
                                class="form-control form-control-lg" name="interieur_chambre" id="interieur_chambre">
                        </div>
                        <div class="col-md-4">
                            <label for="exterieur_chambre" class="form-label">Exterieur de la chambre (couleur)</label>
                            <input type="text" style="height: 55px;border-radius: 10px;"
                                class="form-control form-control-lg" name="exterieur_chambre" id="exterieur_chambre">
                        </div>
                        <div class="col-md-4">
                            <label for="puissance_chambre" class="form-label">Puissance de la chambre</label>
                            <input type="text" style="height: 55px;border-radius: 10px;"
                                class="form-control form-control-lg" name="puissance_chambre" id="puissance_chambre">
                        </div> --}}


                        <div class="col-md-6">
                            <label for="prix_standard_chambre" class="form-label">Prix de la chambre</label>
                            <input type="number" min="0" style="height: 55px;border-radius: 10px;"
                                class="form-control form-control-lg" name="prix_standard_chambre"
                                id="prix_standard_chambre">
                        </div>
                        <div class="col-md-6">
                            <label for="status_chambre" class="form-label">Status de la chambre</label>
                            <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                                name="status_chambre" id="status_chambre">
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="status_reserver_chambre" class="form-label">Etat de Reservation</label>
                            <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                                name="status_reserver_chambre" id="status_reserver_chambre">
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="image_chambre" class="form-label">Image</label>
                            <input type="file" style="height: 55px;border-radius: 10px;"
                                class="form-control form-control-lg" name="image_chambre" id="image_chambre" />
                        </div>
                        <div class="col-md-6" id="viewImageChambre"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" style="border-radius: 10px;" class="btn btn-danger btn-md"
                            data-bs-dismiss="modal"> <i class="bx bx-exit"></i> Annuler</button>
                        <button type="submit" style="border-radius: 10px;" class="btn btn-success btn-md"><i
                                class="bx bx-save"></i> Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal pour supprimer une chambre donnee --}}
    <div class="modal fade" id="deleteChambreModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">SUPPRESSION D'UNE CHAMBRE</h4>
                </div>
                <div class="modal-body">
                    <p style="text-align: center;" id="viewMessageDeleteChambre"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" style="border-radius: 10px;" class="btn btn-secondary btn-md"
                        data-bs-dismiss="modal"> <i class="bx bx-exit"></i> Non</button>
                    <form action="{{ route('chambres.delete.gestion') }}" method="post" id="chambres-delete-gestion">
                        @csrf
                        <div id="formDeleteChambre"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @include('packages.chambres.gestionnaire.chambreModal')
@endsection
@push('script-find-chambre')
    <script>
        //Pour recuperer les categories de chambre grace a l'identifiant de la catégorie
        $(document).ready(function() {
            $("#categoriechambre_id").on("change", function() {
                let categoriechambre_id = $("#categoriechambre_id").val()
                //console.log(categoriechambre_id)
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'GET',
                    url: '{{ route('categoriechambres.get.spec.gestion') }}',
                    dataType: 'JSON',
                    data: {
                        categoriechambre_id: categoriechambre_id
                    },
                    success: (response) => {
                        //console.log('Request Success')
                        let optionsHotel = '';
                        for (let i = 0; i < response.length; i++) {
                            optionsHotel += '<option value="' + response[i].id_hotel + '">' +
                                response[i].nom_hotel + '</option>';
                        }

                        if (response.length > 0) {
                            $('#hotel_id').html(optionsHotel);
                        } else {
                            $('#hotel_id').html(
                                '<option value="" selected disabled>Aucun hotel pour cette cateforie</option>'
                            );
                        }

                        $("#hotel_id").on("change", function() {
                            let hotel_id = $("#hotel_id").val()
                        })
                    },
                    error: (error) => {
                        console.log('Request Erreur')
                        console.log(error)
                    }
                })
            })
        })

        //Pour recuperer  grace a l'identifiant du hotel
        $(document).ready(function() {
            $("#hotel_id").on("change", function() {
                let hotel_id = $("#hotel_id").val()
                //console.log(hotel_id)
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'GET',
                    url: '{{ route('hotels.get.spec.gestion') }}',
                    dataType: 'JSON',
                    data: {
                        hotel_id: hotel_id
                    },
                    success: (response) => {
                        //console.log('Request Success')
                        //console.log(response)
                        let optionsSociete = '';
                        for (let i = 0; i < response.length; i++) {
                            optionsSociete += '<option value="' + response[i].id_societe +
                                '">' + response[i].nom_societe + '</option>';
                        }
                        if (response.length > 0) {
                            $('#societe_id').html(optionsSociete);
                        } else {
                            $('#societe_id').html(
                                '<option value="" selected disabled>Aucune societe pour ce parc</option>'
                            );
                        }

                        $("#societe_id").on("change", function() {
                            let societe_id = $("#societe_id").val()
                        })
                    },
                    error: (error) => {
                        console.log('Request Erreur')
                        console.log(error)
                    }
                })
            })
        })

        //Pour obtenir les informations d'une chambre grace a l'identifiant
        $(document).on('click', '#getInfosChambre', function() {
            let id_chambre = $(this).data('chambre');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                type: 'GET',
                url: '{{ route('chambres.info.gestion') }}',
                dataType: 'JSON',
                data: {
                    id_chambre: id_chambre
                },
                success: (response) => {
                    let created_at = new Date().toLocaleDateString("fr");
                    $("#viewNomChambre").html(response.nom_chambre);
                    $("#viewCatChambre").html(response.nom_categoriechambre);
                    $("#viewHotelChambre").html(response.nom_hotel);
                    // $("#viewModelechambre").html(response.nom_modele);
                    $("#viewClasseChambre").html(response.classe_chambre);
                    $("#viewPrixChambre").html(response.prix_standard_chambre + " FCFA");
                    // $("#viewAnneechambre").html(response.annee_chambre);
                    // $("#viewKilometragechambre").html(response.kilometrage_chambre + " Km");
                    // $("#viewBoitechambre").html(response.boite_vitesse_chambre);
                    // $("#viewCarburantchambre").html(response.carburant_chambre);
                    $("#viewLitsChambre").html(response.nombre_lits_chambre);
                    $("#viewPlacesChambre").html(response.nombre_places_chambre);
                    $("#viewPaysChambre").html(response.nom_pays);
                    $("#viewPaysChambre").html(response.nom_pays);
                    $("#viewSocieteChambre").html(response.nom_societe);
                    $("#viewTypeChambre").html(response.nom_typehebergement);
                    $("#viewVilleChambre").html(response.nom_ville);
                    $("#viewCodeChambre").html(response.code_chambre);
                    $("#viewDateCreatedChambre").html(new Date(response.created_at)
                        .toLocaleDateString());
                    if (response.status_reserver_chambre) {
                        $("#viewStatusRes").html('<span class="badge bg-success">Reservée</span>');
                    } else {
                        $("#viewStatusRes").html('<span class="badge bg-danger">Libre</span>');
                    }
                    $("#infoschambreModal").modal('show');
                },
                error: (error) => {
                    console.log("Response error")
                    console.log(error)
                }

            })
        })

        //Supprimer une chambre grace a l'identifiant
        $(document).on('click', '#deteleChambre', function() {
            let id_chambre = $(this).data('chambre');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                type: 'GET',
                url: '{{ route('chambres.info') }}',
                dataType: 'JSON',
                data: {
                    id_chambre: id_chambre
                },
                success: (response) => {
                    $("#viewMessageDeleteChambre").html(
                        'Etes-vous sur de vouloir supprimer la chambre <strong>' + response
                        .nom_chambre + ' ' + response.nom_categoriechambre +
                        '</strong> de la societe <strong>' + response.nom_societe +
                        '</strong> de l\'hotel <strong>' + response.nom_hotel + '</strong>?');
                    $("#formDeleteChambre").html('<input type="hidden" name="id_chambre" value="' +
                        response.id_chambre +
                        '"> <button type="submit" style="border-radius: 10px;" class="btn btn-danger btn-md"><i class="bx bx-trash"></i> Oui</button>'
                    );
                    $("#deleteChambreModal").modal('show');
                },
                error: (error) => {
                    console.log('Response Erreur Delete chambre')
                    console.log(error)
                }
            })
        })

        //Mettre a jour une chambre grace a l'identifiant
        $(document).on('click', '#updateChambre', function() {
            let id_chambre = $(this).data('chambre');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                type: 'GET',
                url: '{{ route('chambres.info.gestion') }}',
                dataType: 'JSON',
                data: {
                    id_chambre: id_chambre
                },
                success: (response) => {
                    $(".update-chambre").html('<input type="hidden" name="id_chambre" value="' +
                        response.id_chambre + '" id="id_chambre">');

                    $("#updateChambreModal").find('input[name="nom_chambre"]').val(response
                        .nom_chambre);
                    $("#updateChambreModal").find('input[name="description_chambre"]').val(response
                        .description_chambre);
                    // $("#updateChambreModal").find('input[name="date_mise_circul_chambre"]').val(response
                    //     .date_mise_circul_chambre);
                    // $("#updateChambreModal").find('input[name="date_mise_circul_chambre"]').val(response
                    //     .date_mise_circul_chambre);
                    // $("#updateChambreModal").find('input[name="interieur_chambre"]').val(response
                    //     .interieur_chambre);
                    // $("#updateChambreModal").find('input[name="exterieur_chambre"]').val(response
                    //     .exterieur_chambre);
                    // $("#updateChambreModal").find('input[name="puissance_chambre"]').val(response
                    //     .puissance_chambre);
                    $("#updateChambreModal").find('input[name="prix_standard_chambre"]').val(response
                        .prix_standard_chambre);
                    // $("#updateChambreModal").find('select[name="carburant_chambre"]').val(response
                    //     .carburant_chambre);
                    // $("#updateChambreModal").find('select[name="boite_vitesse_chambre"]').val(response
                    //     .boite_vitesse_chambre);

                    $("#updateChambreModal").find('select[name="nombre_places_chambre"]').val(response
                        .nombre_places_chambre);
                    $("#updateChambreModal").find('select[name="nombre_lits_chambre"]').val(response
                        .nombre_lits_chambre);
                    $("#updateChambreModal").find('select[name="classe_chambre"]').val(response
                        .classe_chambre);
                    $("#updateChambreModal").find('select[name="categoriechambre_id"]').val(response
                        .id_categoriechambre);
                    $("#updateChambreModal").find('select[name="hotel_id"]').val(response.id_hotel);
                    $("#updateChambreModal").find('select[name="societe_id"]').val(response
                        .id_societe);
                    $("#updateChambreModal").find('select[name="ville_id"]').val(response
                        .id_ville);
                    $("#updateChambreModal").find('select[name="pays_id"]').val(response
                        .id_pays);
                    $("#updateChambreModal").find('select[name="typehebergement_id"]').val(response
                        .id_typehebergement);

                    if (response.status_chambre) {
                        $("#status_chambre").html(
                            '<option value="1" selected>Active</option><option value="0">Inactive</option>'
                        );
                    } else {
                        $("#status_chambre").html(
                            '<option value="1">Active</option><option value="0" selected>Inactive</option>'
                        );
                    }

                    if (response.status_reserver_chambre) {
                        $("#status_reserver_chambre").html(
                            '<option value="1" selected>Reservée</option><option value="0">Libre</option>'
                        );
                    } else {
                        $("#status_reserver_chambre").html(
                            '<option value="1">Reservée</option><option value="0" selected>Libre</option>'
                        );
                    }

                    $("#viewImagechambre").html('<img width="100" height="100" src="' + response
                        .image_chambre + '" />');

                    $("#updateChambreModal").modal("show");
                },
                error: (error) => {
                    console.log('Response Erreur Modifier chambre')
                    console.log(error)
                }
            })
        })
    </script>
@endpush
