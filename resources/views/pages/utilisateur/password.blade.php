@extends('layouts.app')
@section('title')
Mon compte :: Youpigoo
@endsection
@section('content')
<!--=============== SHOP ===============-->
<section class="shop section container">
    <h2 class="breadcrumb__title" style="font-size: 16px;">Bienvenue {{ $client->nom_user }} {{ $client->prenoms_user }}
        nous sommes ravies de vous revoir</h2>
    <h3 class="breadcrumb__subtitle">Accueil > <span>Informations de connexion</span></h3>

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
                        <a href="{{ url('client/profile') }}">Mon compte</a>
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
                        <a href="javascript:void(0)" style="color: #A40000;">Mot de passe</a>
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

        <form action="{{ url('client/mot-de-passe') }}" method="post" class="contact__form grid" >
            @csrf
            <input type="hidden" name="id_client" value="{{ ($client->id) }}">

            <div class="contact__inputs">
                <div class="contact__content" style="margin-bottom: 25px;">
                    <label for="password_old" class="contact__label">Ancien mot de passe</label>
                    <input type="password" class="contact__input" name="password_old" id="password_old"/>
                </div>
                <div class="contact__content" style="margin-bottom: 25px;">
                    <label for="password_new" class="contact__label">Nouveau mot de passe</label>
                    <input type="password" class="contact__input" name="password_new" id="password_new"/>
                </div>
                <div class="contact__content">
                    <label for="confirmation_password" class="contact__label">Retaper le nouveau  mot de passe</label>
                    <input type="password" class="contact__input" name="confirmation_password" id="confirmation_password"/>
                </div>  
                <div style="margin-top: 20px;">
                    <button type="submit" class="button"><i class="bx bx-save"></i> Enregistrer</button>
                </div>
            </div>
        </form>

    </div>
</section>
@endsection