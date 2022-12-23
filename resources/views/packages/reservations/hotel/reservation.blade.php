@extends('themes.main')
@section('title')
Reservations :: Youpigoo
@endsection
@section('main')
<div class="pagetitle">
    <h1 style="font-size: 25px;">Reservations</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Accueil</a></li>
            <li class="breadcrumb-item active">Reservations</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body" style="overflow: auto;">
                    <h5 class="card-title" style="text-align: center;font-size: 25px;">Listes des reservations
                    </h5>
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">CODE RESERVATION</th>
                                <th scope="col">CHAMBRE</th>
                                {{-- <th scope="col">SERVICES</th> --}}
                                <th scope="col">FACTURE</th>
                                <th scope="col">CLIENT</th>
                                <th scope="col">ETAT</th>
                                <th scope="col">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservations as $reservation)
                            <tr>
                                <th scope="row">{{ $reservation->code_reservation }}</th>
                                <td>{{ $reservation->nom_chambre }} {{ $reservation->nom_hotel }}</td>
                              
                                {{-- <td>
                                    @if ($listes_services[$reservation->id_reservation] == "")
                                    <span class="badge bg-warning">Aucun service lié à la chambre</span>
                                    @else
                                    <span class="badge bg-secondary">{{ $listes_services[$reservation->id_reservation]
                                        }}</span>
                                    @endif
                                </td> --}}

                                <td>
                                    <a href="{{ $reservation->facture_reservation }}" class="btn btn-secondary"><i class="bx bxs-file"></i> Telecharger</a>
                                </td>

                                <td>{{ $reservation->nom_user }} {{ $reservation->prenoms_user }}</td>
                                <td>
                                    @if($reservation->status_reservation)
                                    <span class="badge bg-success">Payée</span>
                                    @else
                                    <span class="badge bg-danger">Non Payée</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-success"
                                        data-reservation="{{ encodeId($reservation->id_reservation) }}"
                                        id="sendFacture">
                                        <i class="bi bi-send"></i>
                                    </button>
                                    <button class="btn btn-secondary"
                                        data-reservation="{{ encodeId($reservation->id_reservation) }}"
                                        id="getInfosReservation">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-danger"
                                        data-reservation="{{ encodeId($reservation->id_reservation) }}"
                                        id="annulationReservation">
                                        <i class="bx bx-exit"></i>
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
                    <h5 class="card-title" style="text-align: center;font-size: 25px;">Listes des reservations annulées 
                    </h5>
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">CODE RESERVATION</th>
                                <th scope="col">CHAMBRE</th>
                                {{-- <th scope="col">SERVICES</th> --}}
                                <th scope="col">SOCIETE</th>
                                <th scope="col">HOTEL</th>
                                <th scope="col">CLIENT</th>
                                <th scope="col">ETAT</th>
                                {{-- <th scope="col">ACTIONS</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservations_annul as $reservation_annul)
                            <tr>
                                <th scope="row">{{ $reservation->code_reservation }}</th>
                                <td>{{ $reservation_annul->nom_chambre }}  {{ $reservation_annul->nom_hotel }}</td>
                               
                                {{-- <td>
                                    @if ($listes_services_annul[$reservation_annul->id_reservation] == "")
                                        <span class="badge bg-warning">Aucun service liée à ce service</span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ $listes_services_annul[$reservation_annul->id_reservation]}}
                                        </span>
                                    @endif
                                </td> --}}

                                <td>{{ Str::upper($reservation_annul->nom_societe) }}</td>
                                <td>{{ $reservation_annul->nom_user }} {{ $reservation_annul->prenoms_user }}</td>
                                <td>
                                    @if($reservation_annul->status_annulation)
                                    <span class="badge bg-warning">Annulée</span>
                                    @endif
                                </td>

                                {{-- <td>
                                    <button class="btn btn-success"
                                        data-reservation="{{ encodeId($reservation->id_reservation) }}"
                                        id="sendFacture">
                                        <i class="bi bi-send"></i>
                                    </button>
                                    <button class="btn btn-secondary"
                                        data-reservation_annul="{{ encodeId($reservation_annul->id_reservation) }}"
                                        id="getInfosReservation">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-danger"
                                        data-reservation="{{ encodeId($reservation->id_reservation) }}"
                                        id="annulationReservation">
                                        <i class="bx bx-exit"></i>
                                    </button>
                                </td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Modal pour voir plus d'info sur la reservation --}}
<div class="modal fade" id="infosReservationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">INFORMATION D'UNE RESERVATION</h4>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-6 text-center">
                    <span>Code reservation</span>
                    <div style="font-weight: 600;text-transform: uppercase;" id="viewCodeReservation"></div>
                </div>
                <div class="col-md-6 text-center">
                    <span>Prix reservation</span>
                    <div style="font-weight: 600;text-transform: uppercase;" id="viewPrixReservation"></div>
                </div>
                <div class="col-md-6 text-center">
                    <span>Chambre</span>
                    <div style="font-weight: 600;text-transform: uppercase;" id="viewChambre"></div>
                </div>
                <div class="col-md-6 text-center">
                    <span>Code Chambre</span>
                    <div style="font-weight: 600;text-transform: uppercase;" id="viewCodeChambre"></div>
                </div>
                <div class="col-md-6 text-center">
                    <span>Prix de la Chambre</span>
                    <div style="font-weight: 600;text-transform: uppercase;" id="viewPrixChambre"></div>
                </div>
                <div class="col-md-6 text-center">
                    <span>Societe</span>
                    <div style="font-weight: 600;text-transform: uppercase;" id="viewSociete"></div>
                </div>
                <div class="col-md-6 text-center">
                    <span>Hotel</span>
                    <div style="font-weight: 600;text-transform: uppercase;" id="viewHotel"></div>
                </div>
                <div class="col-md-6 text-center">
                    <span>Date & Heure</span>
                    <div style="font-weight: 600;text-transform: uppercase;" id="viewDateCreated"></div>
                </div>
                <div class="col-md-6 text-center">
                    <span>Status</span>
                    <div style="font-weight: 600;text-transform: uppercase;" id="viewStatusReservation"></div>
                </div>
                <div class="col-md-12 text-center">
                    <span>Identité du client</span>
                    <div style="font-weight: 600;text-transform: uppercase;" id="viewClient"></div>
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

{{-- Modal de l'envoie de facture --}}
<div class="modal fade" id="sendFactureModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">ENVOYER UNE FACTURE</h4>
            </div>
            <div class="modal-body">
                <form action="{{ route('factures.store.gestion') }}" method="post" enctype="multipart/form-data" id="factures-store-gestion">
                    @csrf
                    <input type="hidden" name="reservation" id="reservation" value="">
                    <input type="hidden" name="client" id="client" value="">
                    <label for="facture" class="form-label">Facture</label>
                    <input type="file" style="height: 55px;border-radius: 10px;" name="path_facture" id="path_facture"
                        class="form-control form-control-lg"/>
                    <button type="submit" style="border-radius: 10px;" class="btn btn-success btn-md mt-3"><i
                            class="bx bx-save"></i> Enregistrer</button>
                            <button type="button" style="border-radius: 10px;" class="btn btn-danger btn-md mt-3"
                    data-bs-dismiss="modal"> <i class="bx bx-exit"></i> Annuler</button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

{{-- Modal d'annulation de reservation --}}
<div class="modal fade" id="annulReservationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">ANNULATION DE LA RESERVATION</h4>
            </div>
            <div class="modal-body">
                <form action="{{ route('reservations.annulation') }}" method="post" id="reservations-annulation">
                    @csrf
                    <input type="hidden" name="reservation" id="reservation" value="">
                    <label for="facture" class="form-label">Motif de l'annulation</label>
                    <textarea style="border-radius: 10px;" name="motif_reservation" id="motif_reservation"
                        class="form-control form-control-lg" rows="5"></textarea>
                    <button type="submit" style="border-radius: 10px;" class="btn btn-success btn-md mt-3"><i
                            class="bx bx-save"></i> Enregistrer</button>
                            <button type="button" style="border-radius: 10px;" class="btn btn-danger btn-md mt-3"
                    data-bs-dismiss="modal"> <i class="bx bx-exit"></i> Annuler</button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts-reservations')
    <script>
        //Pour obtenir les informations d'une reservation grace a l'identifiant
        $(document).on('click', '#getInfosReservation', function(){
            let id_reservation = $(this).data('reservation');
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                type: 'GET',
                url: '{{ route('reservations.info.gestion') }}',
                dataType: 'JSON',
                data: {id_reservation:id_reservation},
                success: (response)=>{
                    $("#viewchambre").html(response.nom_chambre+" "+response.nom_hotel);
                    $("#viewPrixchambre").html(response.prix_chambre+" FCFA");
                    $("#viewCodechambre").html(response.code_chambre);
                    $("#viewCodeReservation").html(response.code_reservation);
                    $("#viewSociete").html(response.nom_societe);
                    $("#viewHotel").html(response.nom_hotel);
                    $("#viewPrixReservation").html(response.prix_reservation+" FCFA");
                    $("#viewClient").html(response.nom_user+" "+response.prenoms_user);
                    $("#viewDateCreated").html(response.created_at);
                    if(response.status_reservation){
                        $("#viewStatusReservation").html('<span class="badge bg-success">Payée</span>');
                    }else{
                        $("#viewStatusReservation").html('<span class="badge bg-danger">Non Payée</span>');
                    }
                    $("#infosReservationModal").modal('show');
                },
                error: (error)=>{
                    console.log("Response error")
                    console.log(error)
                }
                
            })
        })

        // Envoie de facture a un client
        $(document).on('click', '#sendFacture', function(){
            let id_reservation = $(this).data('reservation')
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                type: 'GET',
                url: '{{ route('reservations.info.gestion') }}',
                dataType: 'JSON',
                data: {id_reservation:id_reservation},
                success: (response)=>{
                    $("#sendFactureModal").find('input[name="reservation"]').val(response.id_reservation)
                    $("#sendFactureModal").find('input[name="client"]').val(response.id);
                    $("#sendFactureModal").modal('show')
                },
                error: (error)=>{
                    console.log('Response Erreur Infos Reservation')
                    console.log(error)
                }
            })
        })

        // Annulation de reservation
        $(document).on('click', '#annulationReservation', function(){
            let id_reservation = $(this).data('reservation')
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                type: 'GET',
                url: '{{ route('reservations.info.gestion') }}',
                dataType: 'JSON',
                data: {id_reservation:id_reservation},
                success: (response)=>{
                    $("#annulReservationModal").find('input[name="reservation"]').val(response.id_reservation)
                    $("#annulReservationModal").modal('show')
                },
                error: (error)=>{
                    console.log('Response Erreur Infos Reservation')
                    console.log(error)
                }
            })
        })
    </script>
@endpush