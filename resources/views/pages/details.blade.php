@extends('layouts.app')
@section('title')
Details :: Youpigoo
@endsection
@section('content')
<!--=============== MAIN ===============-->
<main class="main">
    <!--=============== DETAILS ===============-->
    <section class="details section container">
        <h2 class="breadcrumb__title">Details</h2>
        <h3 class="breadcrumb__subtitle">Accueil > <span>Details</span></h3>

        <div class="details__container grid">
            <div class="product__images grid">
                <div class="product__img">
                    @if ($chambre->status_reserver_chambre)
                    <div class="details__img-tag">Reservée</div>
                    @endif
                    <img src="{{ $chambre->image_chambre }}" alt="">
                </div>
                @foreach ($images as $image)
                <div class="product__img" style="margin-top: -60px;">
                    <img style="display: none;" src="{{ $image->path_image }}" alt="">
                </div>
                @endforeach
                <div class="modal-btn product__img">
                    Voir toutes les photos ({{ $image_count + 2 }})
                    <img style="display: none;" src="{{ $chambre->image_chambre }}" alt="">
                </div>
            </div>
            <form action="{{ route('reservations.store') }}" method="post" id="reservations-store">
                @csrf

                {{-- Data --}}
                @if (Auth::check())
                <input type="hidden" name="client" value="{{ encodeId(Auth::id()) }}">
                <input type="hidden" name="chambre" value="{{ encodeId($chambre->id_chambre) }}">
                <input type="hidden" name="societe" value="{{ encodeId($chambre->societe_id) }}">
                @endif

                <div class="product__info">
                    <p class="details__subtitle">{{ $chambre->libelle_categoriechambre }} > {{ $chambre->nom_hotel }} 
                        > {{ $chambre->nom_chambre }}</p>
                    <h3 class="details__title">{{ $chambre->libelle_categoriechambre }} ,{{ $chambre->nom_hotel }} 
                 ,{{ $chambre->nom_chambre }}</h3>

                    {{-- <div class="rating">
                        <div class="stars">
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                            <i class="bx bx-star"></i>
                        </div>
                        <span class="reviews__count">40+ Reviews</span>
                    </div> --}}

                    <div class="details__prices">
                        <span class="details__price">{{ $chambre->prix_standard_chambre }} FCFA</span>
                        {{-- <span class="details__discount">17000000</span>
                        <span class="discount__percentage">50% OFF</span> --}}
                    </div>

                    <div class="details__description">
                        <h3 class="description__title">Nos services liés à la chambre</h3>
                        <div class="description__details">
                            @forelse ($services as $service)
                            <div class="filter">
                                <input type="checkbox" name="service[]" id="service" value="{{ $service->id_service }}" />
                                <p>{{ $service->nom_service }}</p>
                            </div>
                            @empty
                            <div class="filter">
                                <p>Aucun service pour l'instant</p><span></span>
                            </div>
                            @endforelse
                            <a href="javascript:void(0)" class="modal-btn modal-trigger">Voir toutes les informations</a>
                        </div>
                    </div>

                    {{-- <div class="cart__amount">
                        <div class="cart__amount-content">
                            <span class="cart__amount-box">
                                <i class="bx bx-minus"></i>
                            </span>
                            <span class="cart__amount-number">1</span>
                            <span class="cart__amount-box">
                                <i class="bx bx-plus"></i>
                            </span>
                        </div>

                        <i class="bx bx-heart cart__amount-heart"></i>
                    </div> --}}
                    @if (Auth::check())
                    <button type="submit" class="button">Reserver</button>
                    @else
                    <a href="{{ route('login') }}" class="button">Reserver</a>
                    @endif
                </div>
            </form>
        </div>
    </section>

    <!--=============== RELATED PRODUCTS ===============-->
    <section class="related__products section">
        <h2 class="section__title">Nos chambres d'hotels <span class="modal-btn" style="font-size: 38px;font-weight: 600;"> {{ $chambre->nom_chambre }}{{ $chambre->nom_hotel }}</span></h2>
        <div class="new__container container">
            <div class="swiper new-swiper">
                <div class="swiper-wrapper">
                    @foreach ($chambres as $chambre)
                    <div class="new__content swiper-slide">
                        <a href="{{ route('details.chambre.get', [$chambre->slug_categoriechambre, $chambre->slug_hotel, encodeId($chambre->id_chambre)]) }}"
                            class="">
                            @if ($chambre->status_reserver_chambre)
                            <div class="new__tag">Reservée</div>
                            @endif
                            <img src="{{ $chambre->image_chambre }}" alt="" class="new__img">
                            <h3 class="new__title">{{ $chambre->libelle_categoriechambre }} {{ $chambre->nom_hotel }}</h3>
                            <span class="new__subtitle">{{ $chambre->description_chambre }} - {{ $chambre->classe_chambre }} - {{ $chambre->nom_ville
                                }} - {{ $chambre->nom_pays
                                }}
                                 -  - {{ $chambre->nombre_lits_chambre }} - {{ $chambre->nombre_places_chambre }}</span>
                            <div class="new__prices">
                                <span class="new__price">{{ $chambre->prix_standard_chambre }} FCFA</span>
                                {{-- <span class="new__discount">15000000</span> --}}
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</main>

 <!--=============== LIGHTBOX ===============-->
 <div class="lightbox">
    <div class="lightbox__content">
        <div class="lightbox__close">&times;</div>
        <img src="{{ $chambre->image_chambre }}" alt="" class="lightbox__img">
        <div class="lightbox__caption">
            <div class="caption__text">Youpigoo</div>
            <div class="caption__counter"></div>
        </div>
    </div>

    <div class="lightbox__controls">
        <div class="prev__item" onclick="prevItem()"><i class="bx bx-chevron-left"></i></div>
        <div class="next__item" onclick="nextItem()"><i class="bx bx-chevron-right"></i></div>
    </div>
</div>

<div class="modal-container">
    <div class="overlay modal-trigger"></div>
    <div class="modal">
        <button class="close-modal modal-trigger">x Fermer</button>
        <h2>Informations de la chambre</h2>

        <div class="modal__infos">
            <div>
                <h4 class="text-fw">Nom de la chambre</h4>
                <h2>{{ $chambre->nom_chambre }}</h2>
            </div>
            <div>
                <h4 class="text-fw">Categorie de chambre</h4>
                <h2>{{ $chambre->libelle_categoriechambre }}</h2>
            </div>
            <div>
                <h4 class="text-fw">Type</h4>
                <h2>{{ $chambre->nom_typehebergement }}</h2>
            </div>
            <div>
                <h4 class="text-fw">Hotel</h4>
                <h2>{{ $chambre->nom_hotel }}</h2>
            </div>
            <div>
                <h4 class="text-fw">Ville</h4>
                <h2>{{ $chambre->nom_ville }}</h2>
            </div>
            <div>
                <h4 class="text-fw">Pays</h4>
                <h2>{{ $chambre->nom_pays }}</h2>
            </div>
            <div>
                <h4 class="text-fw">Description</h4>
                <h2>{{ $chambre->description_chambre }}</h2>
            </div>
        </div>

        <div class="modal__infos">
            <div>
                <h4 class="text-fw">classe de la chambre</h4>
                <h2>{{ $chambre->classe_chambre }}</h2>
            </div>
            {{-- <div>
                <h4 class="text-fw">Année</h4>
                <h2>{{ $chambre->annee_chambre }}</h2>
            </div>
            <div>
                <h4 class="text-fw">Carburant</h4>
                <h2>{{ $chambre->carburant_chambre }}</h2>
            </div> --}}
        </div>

        {{-- <div class="modal__infos">
            <div>
                <h4 class="text-fw">Interieur</h4>
                <h2>{{ $chambre->interieur_chambre }}</h2>
            </div>
            <div>
                <h4 class="text-fw">Exterieur</h4>
                <h2>{{ $chambre->exterieur_chambre }}</h2>
            </div>
            <div>
                <h4 class="text-fw">Kilometrage</h4>
                <h2>{{ $chambre->kilometrage_chambre }} Km</h2>
            </div>
        </div> --}}

        <div class="modal__infos">
            <div>
                <h4 class="text-fw">Nombre de places</h4>
                <h2>{{ $chambre->nombre_places_chambre }}</h2>
            </div>

            <div>
                <h4 class="text-fw">Nombre de Lits</h4>
                <h2>{{ $chambre->nombre_lits_chambre }}</h2>
            </div>


            {{-- <div>
                <h4 class="text-fw">Date de mise en circulation</h4>
                <h2>{{ $chambre->date_mise_circul_chambre }}</h2>
            </div> --}}
        </div>

        {{-- <div style="display: flex;justify-content: space-between;margin-bottom: 10px;">
            <div>
                <h4>Année</h4>
                <h3>{{ $chambre->annee_chambre }}</h3>
            </div>
            <div>
                <h4>Carburant</h4>
                <h3>{{ $chambre->carburant_chambre }}</h3>
            </div>
            <div>
                <h4>Interieur</h4>
                <h3>{{ $chambre->interieur_chambre }}</h3>
            </div>
            <div>
                <h4>Exterieur</h4>
                <h3>{{ $chambre->exterieur_chambre }}</h3>
            </div>
        </div> --}}

        {{-- <div style="display: flex;justify-content: space-between;margin-bottom: 10px;">

            <div>
                <h4>Nbre de place</h4>
                <h3>{{ $chambre->nbres_place_chambre }}</h3>
            </div>
        </div>

        <div style="display: flex;justify-content: space-between;margin-bottom: 10px;">
            <div>
                <h4>Nbre de place</h4>
                <h3>{{ $chambre->nbres_place_chambre }}</h3>
            </div>
        </div> --}}
    </div>
</div>

@endsection
@push('script-details')
<script>
    const productItems = document.querySelectorAll(".product__img");
        const totalProductItems = productItems.length;
        const lightbox = document.querySelector(".lightbox");
        const lightboxImg = document.querySelector(".lightbox__img");
        const lightboxClose = document.querySelector(".lightbox__close");
        const lightboxCounter = document.querySelector(".caption__counter");

        let itemIndex = 0;

        for (let i = 0; i < totalProductItems; i++) {
            productItems[i].addEventListener("click", function () {
                itemIndex = i;
                changeItem();
                toggleLightbox();
            })
        }

        function nextItem(){
            if(itemIndex === totalProductItems - 1){
                itemIndex = 0;
            }else{
                itemIndex++;
            }
            changeItem();
        }

        function prevItem(){
            if(itemIndex === 0){
                itemIndex = totalProductItems - 1;
            }else{
                itemIndex--;
            }
            changeItem();
        }

        function toggleLightbox() {
            lightbox.classList.toggle("open");
        }

        function changeItem() {
            imgSrc = productItems[itemIndex].querySelector(".product__img img").getAttribute("src");
            lightboxImg.src = imgSrc;
            lightboxCounter.innerHTML = (itemIndex + 1) + " à " + totalProductItems;
        }

        //close lightbox
        lightbox.addEventListener("click", function(){
            if(event.target === lightboxClose || event.target === lightbox){
                toggleLightbox();
            }
        })
</script>
@endpush