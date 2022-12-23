@extends('themes.main')
@section('title')
Mon compte :: Youpigoo
@endsection
@section('main')
<div class="pagetitle">
    <h1 style="font-size: 25px;">Mon compte</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Accueil</a></li>
            <li class="breadcrumb-item active">Mon compte</li>
        </ol>
    </nav>
</div>

<section class="section profile">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <img src="{{ asset(Auth::user()->avatar_user) }}" alt="Profile" class="rounded-circle">
                    <h2>{{ Auth::user()->nom_user }} {{ Auth::user()->prenoms_user }}</h2>
                    <h3 class="mt-3" style="font-weight: 600;font-size: 25px;">Informations de connexion</h3>
                    <form action="{{ route('hotel.update.mdp') }}" method="post" id="hotel-update-mdp">
                        @csrf
                        <input type="hidden" name="id_hotel" class="id_hotel" value="{{ encodeId(Auth::id()) }}">
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <label for="password_old" class="form-label">Ancien mot de passe</label>
                                <input type="password" style="height: 55px;border-radius: 10px;" name="password_old" id="password_old"
                                    class="form-control form-control-lg" value="" />
                            </div>
                            <div class="col-md-6 mt-3 mb-3">
                                <label for="password_new" class="form-label">Nouveau mot de passe</label>
                                <input type="password" style="height: 55px;border-radius: 10px;" name="password_new"
                                    id="password_new" class="form-control form-control-lg"
                                    value="" />
                            </div>
                            <div class="col-md-6 mt-3 mb-3">
                                <label for="confirmation_password" class="form-label">Retaper le nouveau mot de passe</label>
                                <input type="password" style="height: 55px;border-radius: 10px;" name="confirmation_password"
                                    id="confirmation_password" class="form-control form-control-lg"
                                    value="" />
                            </div>
                            <div class="col-md-6 mt-3">
                                <button style="height: 55px;border-radius: 10px;" class="btn btn-success btn-lg"><i
                                        class="bx bx-save"></i> Enregistrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                    <h3 class="mt-3" style="font-weight: 600;font-size: 25px;">Votre Identité</h3>
                    <form action="{{ route('gestionnairehotel.profile.update') }}" method="post" enctype="multipart/form-data" id="gestionnairehotel-profile-update">
                        @csrf
                        <input type="hidden" name="id_hotel" class="id_hotel" value="{{ encodeId(Auth::id()) }}">
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <label for="nom_user" class="form-label">Nom</label>
                                <input type="text" style="height: 55px;border-radius: 10px;" name="nom_user" id="nom_user"
                                    class="form-control form-control-lg" value="" />
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="prenoms_user" class="form-label">Prenoms</label>
                                <input type="text" style="height: 55px;border-radius: 10px;" name="prenoms_user"
                                    id="prenoms_user" class="form-control form-control-lg"
                                    value="" />
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="telephone_user" class="form-label">Telephone</label>
                                <input type="text" style="height: 55px;border-radius: 10px;" name="telephone_user"
                                    id="telephone_user" class="form-control form-control-lg"
                                    value="" />
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="email_user" class="form-label">Email</label>
                                <input type="text" style="height: 55px;border-radius: 10px;" name="email_user"
                                    id="email_user" class="form-control form-control-lg"
                                    value="" />
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="adresse_user" class="form-label">Adresse</label>
                                <input type="text" style="height: 55px;border-radius: 10px;" name="adresse_user"
                                    id="adresse_user" class="form-control form-control-lg"
                                    value="" />
                            </div>
                            <div class="col-md-6 mt-2">
                                <label for="pays_user" class="form-label">Pays</label>
                                <select style="height: 55px;border-radius: 10px;" name="pays_user" id="pays_user"
                                    class="form-control form-control-lg">
                                    <option value="Bénin">Bénin</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Guinée">Guinée</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Sénégal">Sénégal</option>
                                    <option value="Togo">Togo</option>
                                </select>
                                <input type="hidden" name="prefix_user" id="prefix_user" value="">
                                <input type="hidden" name="status_user" id="status_user" value="">
                            </div>
                            <div class="col-md-12 mt-2 mb-2">
                                <label for="avatar_user" class="form-label">Votre photo de profile</label>
                                <input type="file" style="height: 55px;border-radius: 10px;" name="avatar_user"
                                    id="avatar_user" class="form-control form-control-lg"
                                    value="" />
                            </div>
                            <div class="col-md-6 mt-3">
                                <button style="height: 55px;border-radius: 10px;" class="btn btn-success btn-lg"><i
                                        class="bx bx-save"></i> Enregistrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts-inscription-check-countries')
    <script>
        $(document).ready(function(){
            let id_gestionnaire_hotel = $('.id_gestionnaire_hotel').val()
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                type: 'GET',
                url: '{{ route('gestionnairehotel.info.gestion') }}',
                dataType: 'JSON',
                data: {id_gestionnaire_hotel: id_gestionnaire_hotel},
                success: (response)=>{
                    $("#gestionnairehotel-profile-update").find('input[name="nom_user"]').val(response.nom_user);
                    $("#gestionnairehotel-profile-update").find('input[name="prenoms_user"]').val(response.prenoms_user);
                    $("#gestionnairehotel-profile-update").find('input[name="email_user"]').val(response.email_user);
                    $("#gestionnairehotel-profile-update").find('input[name="telephone_user"]').val(response.telephone_user);
                    $("#gestionnairehotel-profile-update").find('input[name="prefix_user"]').val(response.prefix_user);
                    $("#gestionnairehotel-profile-update").find('input[name="adresse_user"]').val(response.adresse_user);
                    $("#gestionnairehotel-profile-update").find('select[name="pays_user"]').val(response.pays_user);
                },
                error: (error)=>{
                    console.log('Response Erreur Infos Gestionnaire hotel')
                    console.log(error)
                }
            })

            $("#pays_user").on("change", function(){
                let pays_user = $("#pays_user").val()
                switch(pays_user){
                    case "Bénin": 
                        $("#prefix_user").val(229)
                    break;
                    case "Burkina Faso":
                        $("#prefix_user").val(226)
                    break;
                    case "Guinée":
                        $("#prefix_user").val(224)
                    break;
                    case "Mali":
                        $("#prefix_user").val(223)
                    break;
                    case "Niger":
                        $("#prefix_user").val(227)
                    break;
                    case "Sénégal":
                        $("#prefix_user").val(221)
                    break;
                    case "Togo":
                        $("#prefix_user").val(228)
                    break;
                }
            })
        })
    </script>
@endpush