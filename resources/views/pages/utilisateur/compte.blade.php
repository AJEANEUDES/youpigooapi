@extends('layouts.app')
@section('title')
Mon compte :: Youpigoo
@endsection
@section('content')
<!--=============== SHOP ===============-->
<section class="shop section container">
    <h2 class="breadcrumb__title" style="font-size: 16px;">Bienvenue {{ $client->nom_user }} {{ $client->prenoms_user }}
        nous sommes ravies de vous revoir</h2>
    <h3 class="breadcrumb__subtitle">Accueil > <span>Informations personnelles</span></h3>

    <div class="shop__container grid">
        <div class="sidebar">
            <h3 class="filter__title"></h3>

            <div class="filter__content" style="text-align: center;">
                <img style="text-align: center;width: 100px; height: 100px;border-radius: 50%;"
                    src="{{ asset($client->avatar_user) }}" alt="">
                <h3 class="filter__subtitle">{{ $client->nom_user }} {{ $client->prenoms_user }}</h3>
            </div>
            <div class="filter__content">
                <div class="filter" style="cursor: pointer;">
                    <i style="font-size: 22px;" class="bx bx-chevron-right"></i>
                    <p style="font-size: 20px;">
                        <a href="{{ url('client/profile') }}" style="color: #A40000;">Mon compte</a>
                    </p><span></span>
                </div>
                <div class="filter" style="cursor: pointer;">
                    <i style="font-size: 22px;" class="bx bx-chevron-right"></i>
                    <p style="font-size: 20px;">
                        <a href="{{ url('client/reservations/listes') }}" style="color: #463429;">Mes reservations</a>
                    </p><span></span>
                </div>
                <div class="filter" style="cursor: pointer;">
                    <i style="font-size: 22px;" class="bx bx-chevron-right"></i>
                    <p style="font-size: 20px;">
                        <a href="{{ url('client/factures/listes') }}" style="color: #463429;">Mes factures</a>
                    </p><span></span>
                </div>
                <div class="filter" style="cursor: pointer;">
                    <i style="font-size: 22px;" class="bx bx-chevron-right"></i>
                    <p style="font-size: 20px;">
                        <a href="{{ url('client/mot-de-passe') }}" style="color: #463429;">Mot de passe</a>
                    </p><span></span>
                </div>
                <div class="filter" style="cursor: pointer;">
                    <i style="font-size: 22px;" class="bx bx-chevron-right"></i>
                    <a class="dropdown-item d-flex align-items-center" href="{{ url('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        <span style="font-size: 20px;">Se deconnecter</span>
                    </a>
                    <form id="logout-form" action="{{ url('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <form action="{{ url('client/mot-de-passe') }}" method="post" class="contact__form grid" enctype="multipart/form-data" id="utilisateur-update">
            @csrf
            <input type="hidden" name="id_client" id="id_client" value="{{ ($client->id) }}">
            <div class="contact__inputs grid">
                <div class="contact__content">
                    <label for="nom_user" class="contact__label">Nom</label>
                    <input type="text" value="" class="contact__input" name="nom_user" id="nom_user"/>
                </div>
                <div class="contact__content">
                    <label for="prenoms_user" class="contact__label">Prenoms</label>
                    <input type="text" value="" class="contact__input" name="prenoms_user" id="prenoms_user"/>
                </div>
            </div>

            <div class="contact__inputs grid">
                <div class="contact__content">
                    <label for="email_user" class="contact__label">Email</label>
                    <input type="email" value="" class="contact__input" name="email_user" id="email_user"/>
                </div>
                <div class="contact__content">
                    <label for="telephone_user" class="contact__label">Telephone</label>
                    <input type="number" min="0" value="" class="contact__input" name="telephone_user" id="telephone_user"/>
                </div>
            </div>

            <div class="contact__inputs grid">
                <div class="contact__content">
                    <label for="pays_user" class="contact__label">Pays</label>
                    <select name="pays_user" id="pays_user" class="contact__input">
                        <option value="Bénin">Bénin</option>
                        <option value="Burkina Faso">Burkina Faso</option>
                        <option value="Guinée">Guinée</option>
                        <option value="Mali">Mali</option>
                        <option value="Niger">Niger</option>
                        <option value="Sénégal">Sénégal</option>
                        <option value="Togo">Togo</option>
                    </select>
                    <input type="hidden" name="prefix_user" id="prefix_user" value="">
                </div>
                <div class="contact__content">
                    <label for="adresse_user" class="contact__label">Adresse de residence</label>
                    <input type="text" value="" class="contact__input" name="adresse_user" id="adresse_user"/>
                </div>
            </div>

            <div class="contact__content">
                <label for="avatar_user" class="contact__label">Votre photo de profil</label>
                <input type="file" value="" class="contact__input" name="avatar_user" id="avatar_user"/>
            </div>

            <div class="contact__inputs grid">
                <div class="contact__content">
                    <label for="password" class="contact__label">Mot de passe</label>
                    <input type="password" name="password" id="password" class="contact__input" />
                </div>
                <div class="contact__content">
                    <label for="confirmation_password" class="contact__label">Retaper votre mot de passe</label>
                    <input type="password" name="confirmation_password" id="confirmation_password" class="contact__input" />
                </div>
            </div>

            <div>
                <button type="submit" class="button"><i class="bx bx-save"></i> Enregistrer</button>
            </div>
        </form>

    </div>
</section>
@endsection
@push('scripts-inscription-check-countries')
    <script>
        $(document).ready(function(){
            let id_client = $('#id_client').val()
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                type: 'GET',
                url: '{{ url('client/info-client') }}',
                dataType: 'JSON',
                data: {id_client: id_client},
                success: (response)=>{
                    $("#utilisateur-update").find('input[name="nom_user"]').val(response.nom_user);
                    $("#utilisateur-update").find('input[name="prenoms_user"]').val(response.prenoms_user);
                    $("#utilisateur-update").find('input[name="email_user"]').val(response.email_user);
                    $("#utilisateur-update").find('input[name="telephone_user"]').val(response.telephone_user);
                    $("#utilisateur-update").find('input[name="prefix_user"]').val(response.prefix_user);
                    $("#utilisateur-update").find('input[name="adresse_user"]').val(response.adresse_user);
                    $("#utilisateur-update").find('select[name="pays_user"]').val(response.pays_user);
                },
                error: (error)=>{
                    console.log('Response Erreur Infos Client')
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