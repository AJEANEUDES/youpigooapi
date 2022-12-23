@extends('themes.main')
@section('title')
    Clients :: Youpigoo
@endsection
@section('main')
    <div class="pagetitle">
        <h1 style="font-size: 25px;">Clients</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Accueil</a></li>
                <li class="breadcrumb-item active">Clients</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body" style="overflow: auto;">
                        <h5 class="card-title" style="text-align: center;font-size: 25px;">Listes des clients
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
                                        <th scope="col">CODE CLIENT</th>
                                        <th scope="col">NOM</th>
                                        <th scope="col">PRENOMS</th>
                                        <th scope="col">ROLE</th>
                                        <th scope="col">STATUS</th>
                                        <th scope="col">DATE & HEURE</th>
                                        <th scope="col">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clients as $client)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $client->code_user }}</td>
                                            <td>{{ $client->nom_user }}</td>
                                            <td>{{ $client->prenoms_user }}</td>
                                            <td>{{ $client->role_user }}</td>
                                            <td>
                                                @if ($client->status_user)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $client->created_at }}</td>
                                            <td>
                                                <button class="btn btn-success" data-client="{{ encodeId($client->id) }}"
                                                    id="updateClient">
                                                    <i class="bi bi-pen"></i>
                                                </button>
                                                <button class="btn btn-secondary" data-client="{{ encodeId($client->id) }}"
                                                    id="getInfosClient">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-danger" data-client="{{ encodeId($client->id) }}"
                                                    id="deleteClient">
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

    {{-- Modal pour voir plus d'info sur le client --}}
    <div class="modal fade" id="infosClientModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">INFORMATIONS SUR LE CLIENT</h4>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6 text-center">
                        <span>Nom</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewNomClient"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Prenoms</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewPrenomsClient"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Email</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewEmailClient"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Telephone</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewTelephoneClient"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Adresse</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewAdresseClient"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Pays</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewPaysClient"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Ville</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewVilleClient"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Code du client</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewCodeClient"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Date & Heure</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewDateCreated"></div>
                    </div>
                    <div class="col-md-6 text-center">
                        <span>Status</span>
                        <div style="font-weight: 600;text-transform: uppercase;" id="viewStatusClient"></div>
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

    {{-- Modal pour modifier un client --}}
    <div class="modal fade" id="updateClientModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('clients.update') }}" method="post" id="clients-update">
                    @csrf
                    <div class="update-client"></div>
                    <div class="modal-header">
                        <h4 class="modal-title">MISE A JOUR DU CLIENT</h4>
                    </div>
                    <div class="modal-body">
                        <label for="status_user" class="form-label mt-3">Status du
                            client</label>
                        <select style="height: 55px;border-radius: 10px;" name="status_user" id="status_user"
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

    {{-- Modal pour supprimer un client --}}
    <div class="modal fade" id="deleteClientModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">SUPPRESSION DU CLIENT</h4>
                </div>
                <div class="modal-body">
                    <p style="text-align: center;" id="viewMessageDeleteClient"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" style="border-radius: 10px;" class="btn btn-secondary btn-md"
                        data-bs-dismiss="modal"> <i class="bx bx-exit"></i> Non</button>
                    <form action="{{ route('clients.delete') }}" method="post" id="clients-delete">
                        @csrf
                        <div id="formDeleteClient"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts-clients')
    <script>
        // Modifier un service grace a l'identifiant

        $(document).on('click', '#updateClient', function() {
            let id_client = $(this).data('client')

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                type: 'GET',
                url: '{{ route('clients.info') }}',
                dataType: 'JSON',
                data: {
                    id_client: id_client
                },
                success: (response) => {
                    if (response.status_user) {
                        $("#status_user").html(
                            '<option value="1" selected>Active</option><option value="0">Inactive</option>'
                            );
                    } else {
                        $("#status_user").html(
                            '<option value="1">Active</option><option value="0" selected>Inactive</option>'
                            );
                    }
                    $(".update-client").html('<input type="hidden" name="id_client" value="' + response
                        .id + '">');
                    $("#updateClientModal").modal("show");
                },
                error: (error) => {
                    console.log('Response Erreur Update Client')
                    console.log(error)
                }
            })
        })

        //Obtenir plus d'information sur un service
        $(document).on('click', '#getInfosClient', function() {
            let id_client = $(this).data('client')

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                type: 'GET',
                url: '{{ route('clients.info') }}',
                dataType: 'JSON',
                data: {
                    id_client: id_client
                },
                success: (response) => {
                    $("#viewNomClient").html(response.nom_user);
                    $("#viewPrenomsClient").html(response.prenoms_user);
                    $("#viewTelephoneClient").html(response.prefix_user + "" + response.telephone_user);
                    $("#viewAdresseClient").html(response.adresse_user);
                    $("#viewPaysClient").html(response.pays_user);
                    $("#viewVilleClient").html(response.ville_user);
                    $("#viewEmailClient").html(response.email_user);
                    $("#viewCodeClient").html(response.code_user);
                    $("#viewDateCreated").html(response.created_at);
                    if (response.status_user) {
                        $("#viewStatusClient").html('<span class="badge bg-success">Active</span>');
                    } else {
                        $("#viewStatusClient").html('<span class="badge bg-danger">Inactive</span>');
                    }
                    $("#infosClientModal").modal('show');
                },
                error: (error) => {
                    console.log('Response Erreur Infos Client')
                    console.log(error)
                }
            })
        })

        //Supprimer un service grace a l'identifiant
        $(document).on('click', '#deleteClient', function() {
            let id_client = $(this).data('client');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                type: 'GET',
                url: '{{ route('clients.info') }}',
                dataType: 'JSON',
                data: {
                    id_client: id_client
                },
                success: (response) => {
                    $("#viewMessageDeleteClient").html(
                        'Etes-vous sur de vouloir supprimer le client <strong>' + response
                        .nom_user + ' ' + response.prenom_user + '</strong>?');
                    $("#formDeleteClient").html('<input type="hidden" name="id_client" value="' +
                        response.id +
                        '"> <button type="submit" style="border-radius: 10px;" class="btn btn-danger btn-md"><i class="bx bx-trash"></i> Oui</button>'
                        );
                    $("#deleteClientModal").modal('show');
                },
                error: (error) => {
                    console.log('Response Erreur Delete Client')
                    console.log(error)
                }
            })
        })
    </script>
@endpush
