@extends('themes.main')
@section('title')
    Services :: Youpigoo
@endsection
@section('main')
    <div class="pagetitle">
        <h1 style="font-size: 25px;">
            Services
            <i class="bx bx-plus-circle" data-bs-toggle="modal" data-bs-target="#addServiceModal"
                style="position: relative;top: 10px;font-size: 40px;color: #2DAF07;cursor: pointer;"></i>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Accueil</a></li>
                <li class="breadcrumb-item active">Services</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body" style="overflow: auto;">
                        <h5 class="card-title" style="text-align: center;font-size: 25px;">Listes des services de chambre
                        </h5>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">CODE SERVICE</th>
                                    <th scope="col">NOM SERVICE</th>
                                    <th scope="col">NOM CHAMBRE</th>
                                    <th scope="col">STATUS</th>
                                    <th scope="col">DATE & HEURE</th>
                                    <th scope="col">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($services as $service)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $service->code_service }}</td>
                                        <td>{{ $service->nom_service }}</td>
                                        <td>{{ $service->nom_chambre }}</td>
                                        <td>
                                            @if ($service->status_service)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $service->created_at }}</td>
                                        <td>
                                            <button class="btn btn-success"
                                                data-service="{{ encodeId($service->id_service) }}" id="updateService">
                                                <i class="bi bi-pen"></i>
                                            </button>
                                            <button class="btn btn-secondary"
                                                data-service="{{ encodeId($service->id_service) }}" id="getInfosService">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-danger"
                                                data-service="{{ encodeId($service->id_service) }}" id="deleteService">
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

    {{-- Modal pour voir plus d'info sur le service --}}
    <div class="modal fade" id="infosServiceModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">INFORMATION DU SERVICE</h4>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6 text-center">
                        <span>Nom du service</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewNomService"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Description du service</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewDescService"></div>
                    </div>


                    <div class="col-md-6 text-center">
                        <span>Code du service</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewCodeService"></div>
                    </div>

                    <div class="col-md-6 text-center">
                        <span>Service attribué à la chambre</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewChambreService"></div>
                    </div>


                    <div class="col-md-6 text-center">
                        <span>Date & Heure</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewDateCreated"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Status</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewStatusService"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Operateur (creer par)</span>
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

    {{-- Modal pour modifier un service de voiture --}}
    <div class="modal fade" id="updateServiceModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('services-chambres.update.gestion') }}" method="post" id="services-chambres-update-gestion">
                    @csrf
                    <div class="update-service"></div>
                    <div class="modal-header">
                        <h4 class="modal-title">MISE A JOUR DU SERVICE</h4>
                    </div>
                    <div class="modal-body">
                        <label for="nom_service" class="form-label">Nom du service</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" name="nom_service" id="nom_service"
                            value="" class="form-control form-control-lg" />


                        <label for="description_service" class="form-label">Description du service</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" name="description_service"
                            id="description_service" value="" class="form-control form-control-lg" />


                            <label for="chambre_id" class="form-label">Chambre attribué à ce service</label>
                            <select style="height: 55px;border-radius: 10px;" name="chambre_id" id="chambre_id"
                                class="form-control form-control-lg ">
    
                                @foreach ($chambres as $chambre)
                                    <option value="{{ $chambre->id_chambre }}">{{ $chambre->nom_chambre }}</option>
                                @endforeach
                            </select>


                        <label for="status_service" class="form-label mt-3">Status du
                            service</label>
                        <select style="height: 55px;border-radius: 10px;" name="status_service" id="status_service"
                            class="form-control form-control-lg">

                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" style="border-radius: 10px;" class="btn btn-danger btn-md"
                            data-bs-dismiss="modal">
                            <i class="bx bx-exit"></i> Annuler
                        </button>
                        <button type="submit" style="border-radius: 10px;" class="btn btn-success btn-md"><i
                                class="bx bx-save"></i>
                            Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal pour supprimer un service --}}
    <div class="modal fade" id="deleteServiceModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">SUPPRESSION DU SERVICE</h4>
                </div>
                <div class="modal-body">
                    <p style="text-align: center;" id="viewMessageDeleteService"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" style="border-radius: 10px;" class="btn btn-secondary btn-md"
                        data-bs-dismiss="modal"> <i class="bx bx-exit"></i> Non</button>
                    <form action="{{ route('services-chambres.delete.gestion') }}" method="post" id="services-chambres-delete-gestion">
                        @csrf
                        <div id="formDeleteService"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('packages.services.gestionnaire.serviceModal')
@endsection
@push('script-services')
    <script>
        //Modifier un service grace a l'identifiant
        $(document).on('click', '#updateService', function() {
            let id_service = $(this).data('service')

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                type: 'GET',
                url: '{{ route('services.info.gestion') }}',
                dataType: 'JSON',
                data: {
                    id_service: id_service
                },
                success: (response) => {
                    $("#updateServiceModal").find('input[name="nom_service"]').val(response
                    .nom_service);
                    $("#updateServiceModal").find('input[name="description_service"]').val(response
                    .description_service);
                    $("#updateServiceModal").find('select[name="chambre_id"]').val(response.id_chambre);

                    if (response.status_service) {
                        $("#status_service").html(
                            '<option value="1" selected>Active</option><option value="0">Inactive</option>'
                            );
                    } else {
                        $("#status_service").html(
                            '<option value="1">Active</option><option value="0" selected>Inactive</option>'
                            );
                    }
                    $(".update-service").html('<input type="hidden" name="id_service" value="' +
                        response.id_service + '">');
                    $("#updateServiceModal").modal("show");
                },
                error: (error) => {
                    console.log('Response Erreur Update Service')
                    console.log(error)
                }
            })
        })

        //Obtenir plus d'information sur un service
        $(document).on('click', '#getInfosService', function() {
            let id_service = $(this).data('service')

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                type: 'GET',
                url: '{{ route('services.info.gestion') }}',
                dataType: 'JSON',
                data: {
                    id_service: id_service
                },
                success: (response) => {
                let created_at = new Date().toLocaleDateString("fr");
                    $("#viewNomService").html(response.nom_service);
                    $("#viewDescService").html(response.description_service);
                    $("#viewChambreService").html(response.nom_chambre);
                    $("#viewCodeService").html(response.code_service);
                    $("#viewDateCreated").html(response.created_at);
                    if (response.status_service) {
                        $("#viewStatusService").html('<span class="badge bg-success">Active</span>');
                    } else {
                        $("#viewStatusService").html('<span class="badge bg-danger">Inactive</span>');
                    }
                    $("#operateur").html(response.nom_user + " " + response.prenoms_user);
                    $("#infosServiceModal").modal('show');
                },
                error: (error) => {
                    console.log('Response Erreur Infos Service')
                    console.log(error)
                }
            })
        })

        //Supprimer un service grace a l'identifiant
        $(document).on('click', '#deleteService', function() {
            let id_service = $(this).data('service');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                type: 'GET',
                url: '{{ route('services.info.gestion') }}',
                dataType: 'JSON',
                data: {
                    id_service: id_service
                },
                success: (response) => {
                    $("#viewMessageDeleteService").html(
                        'Etes-vous sur de vouloir supprimer le service <strong>' + response
                        .nom_service + '</strong>?');
                    $("#formDeleteService").html('<input type="hidden" name="id_service" value="' +
                        response.id_service +
                        '"> <button type="submit" style="border-radius: 10px;" class="btn btn-danger btn-md"><i class="bx bx-trash"></i> Oui</button>'
                        );
                    $("#deleteServiceModal").modal('show');
                },
                error: (error) => {
                    console.log('Response Erreur Delete Service')
                    console.log(error)
                }
            })
        })
    </script>
@endpush
