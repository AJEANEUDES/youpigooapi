@extends('layouts.app')
@section('title')
Mes reservations :: Youpigoo
@endsection
@section('content')
<!--=============== SHOP ===============-->
<section class="shop section container">
    <h2 class="breadcrumb__title" style="font-size: 16px;">Bienvenue {{ $client->nom_user }} {{ $client->prenom_user }} nous sommes ravi de vous revoir</h2>
    <h3 class="breadcrumb__subtitle">Accueil > <span>Mes factures</span></h3>

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
                        <a href="{{ url('client/profile') }}" style="color: #463429;">Mon compte</a>
                    </p><span></span>
                </div>
                <div class="filter" style="cursor: pointer;">
                    <i style="font-size: 22px;" class="bx bx-chevron-right"></i>
                    <p style="font-size: 20px;">
                        <a href="{{ url('client/reservation/listes') }}" style="color: #463429;">Mes reservations</a>
                    </p><span></span>
                </div>
                <div class="filter" style="cursor: pointer;">
                    <i style="font-size: 22px;" class="bx bx-chevron-right"></i>
                    <p style="font-size: 20px;">
                        <a href="{{ url('client/reservation-factures.liste') }}" style="color: #A40000;">Mes factures</a>
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

        <div class="contact__form">
            <table class="table datatable">
                <thead>
                    <tr style="text-align: center;">
                        <th scope="col" style="padding: 20px;">ID</th>
                        <th scope="col" style="padding: 20px;">CHAMBRE</th>
                        <th scope="col" style="padding: 20px;">DATE & HEURE</th>
                        <th scope="col" style="padding: 20px;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mes_factures as $mes_facture)
                    <tr style="text-align: center;">
                        <td scope="row" style="padding: 0 20px 0 20px;">{{ $loop->iteration }}</td>
                        <td scope="row" style="padding: 0 20px 0 20px;">{{ $mes_facture->libelle_categoriechambre }} {{ $mes_facture->nom_hotel }} {{ $mes_facture->nom_chambre }}</td>
                        <td scope="row" style="padding: 0 20px 0 20px;">{{ $mes_facture->created_at }}</td>
                        <td scope="row" style="padding: 0 20px 0 20px;">
                            <a href="{{ asset($mes_facture->path_facture) }}" target="blank">Telecharger la facture</a>    
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection