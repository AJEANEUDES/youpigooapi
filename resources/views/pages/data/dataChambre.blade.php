<div class="shop__items grid">

    @foreach ($chambres as $chambre)
        <a href="{{ url('/details-chambre', [$chambre->slug_categoriechambre, $chambre->slug_hotel, $chambre->id_chambre]) }}"
            class="" data-categoriechambre="{{ $chambre->slug_categoriechambre }}"
            data-hotel="{{ $chambre->slug_hotel }}" data-details="{{ $chambre->id_chambre }}">
            <div class="shop__content">

                @if ($chambre->status_reserver_chambre)
                    <div class="shop__tag">Reservée</div>
                @endif


                <img src="{{ asset($chambre->image_chambre) }}"
                    alt="{{ $chambre->nom_categoriechambre }} {{ $chambre->nom_hotel }}" class="shop__img">
                <h3 class="shop__title" style="color: rgb(24, 22, 22);">{{ $chambre->nom_categoriechambre }}
                    {{ $chambre->nom_hotel }}</h3>
                <span class="shop__subtitle">{{ $chambre->nombre_lits_chambre }} - {{ $chambre->classe_chambre }} -
                    {{ $chambre->nombre_places_chambre }}
                </span>
                <div class="shop__prices">
                    <span class="shop__price">{{ $chambre->prix_standard_chambre }} FCFA</span>
                </div>
            </div>
        </a>
        @empty
            <div class="shop__data__empty">
                <i class="bx bx-car shop__data__empty__icon"></i>
                <span class="shop__data__empty__oops">Ooops!</span>
                <p class="shop__data__empty__message">Aucun resultat pour cette recherche</p>
            </div>
        @endempty
    @endforeach





</div>
