@extends('layouts.app')
@section('title')
Mon compte :: Youpigoo
@endsection
@section('content')
<!--=============== SHOP ===============-->
<section class="shop section container">
    <h2 class="breadcrumb__title" style="font-size: 16px;">Bienvenue {{ $client->nom_user }} {{ $client->prenoms_user }}
        nous sommes ravie de vous revoir</h2>
    <h3 class="breadcrumb__subtitle">Accueil > <span>Mon compte</span></h3>

    <div class="shop__container grid">
        <div class="sidebar">
            <h3 class="filter__title"></h3>

            <div class="filter__content" style="text-align: center;">
                <img style="text-align: center;width: 100px; height: 100px;border-radius: 50%;"
                    src="{{ asset($client->avatar_user) }}" alt="">
                <h3 class="filter__subtitle">{{ $client->nom_user }} {{ $client->prenomd_user }}</h3>
            </div>
            <div class="filter__content">
                <div class="filter" style="cursor: pointer;">
                    <i style="font-size: 22px;" class="bx bx-chevron-right"></i>
                    <p style="font-size: 20px;">
                        <a href="{{ route('utilisateur.profile') }}" style="color: #463429;">Mon compte</a>
                    </p><span></span>
                </div>
                <div class="filter" style="cursor: pointer;">
                    <i style="font-size: 22px;" class="bx bx-chevron-right"></i>
                    <p style="font-size: 20px;">
                        <a href="{{ route('reservations.liste') }}" style="color: #A40000;">Mes reservations</a>
                    </p><span></span>
                </div>
                <div class="filter" style="cursor: pointer;">
                    <i style="font-size: 22px;" class="bx bx-chevron-right"></i>
                    <p style="font-size: 20px;">
                        <a href="{{ route('factures.liste') }}" style="color: #463429;">Mes factures</a>
                    </p><span></span>
                </div>
                <div class="filter" style="cursor: pointer;">
                    <i style="font-size: 22px;" class="bx bx-chevron-right"></i>
                    <p style="font-size: 20px;">
                        <a href="{{ route('utilisateur.mdp') }}" style="color: #463429;">Mot de passe</a>
                    </p><span></span>
                </div>
                <div class="filter" style="cursor: pointer;">
                    <i style="font-size: 22px;" class="bx bx-chevron-right"></i>
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        <span style="font-size: 20px;">Se deconnecter</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <div class="contact__inputs">
            <div>
                @if (Session::has('message_callback'))
                <div style="font-size: 25px;font-weight: 500;text-align: center;margin-top: 80px;display: flex;flex-direction: column;">
                    <i style="font-size: 50px;" class="bx bx-car"></i>
                    <div style="font-size: 50px;">Ooops !</div>
                    {{ Session('message_callback') }}
                </div>
                @endif
                @if (Session::has('message_success'))
                <div style="font-size: 25px;font-weight: 500;text-align: center;margin-top: 80px;display: flex;flex-direction: column;">
                    <i style="font-size: 50px;" class="bx bx-shopping-bag"></i>
                    <div style="font-size: 50px;color: green;">Paiement reussie !</div>
                    {{ Session('message_success') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection