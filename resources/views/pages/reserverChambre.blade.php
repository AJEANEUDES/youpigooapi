@extends('layouts.app')
@section('title')
    Reserver une Chambre :: Youpigoo
@endsection
@section('content')
    <!--=============== SHOP ===============-->
    <section class="shop section container">
        <h2 class="breadcrumb__title">Reserver une Chambre</h2>
        <h3 class="breadcrumb__subtitle">Accueil > <span>Reserver une Chambre</span></h3>

        <div style="margin-bottom: 50px;">
            <form action="" class="newsletter__form">
                <input type="text" class="newsletter__input" id="search"
                    placeholder="Rechercher par chambre, categoriechambre, prix, hotel, etc...">
                {{-- <button class="button">Rechercher</button> --}}
            </form>
        </div>

        <div class="shop__container grid">
            <div class="sidebar">
                <h3 class="filter__title">Selectionnez Filtres</h3>

                <div class="filter__content">
                    <h3 class="filter__subtitle">Prix</h3>
                    <div class="filter">
                        <p>Min </p>
                        <input type="number" min="0" name="prix_min"
                            style="border: 1px solid #000;width: 100px;border-radius: 5px;" class="newsletter__input"
                            id="prix_min" />
                        <p>Max </p>
                        <input type="number" min="0" name="prix_max"
                            style="border: 1px solid #000;width: 100px;border-radius: 5px;" class="newsletter__input"
                            id="prix_max" />
                    </div>
                </div>

                <div class="filter__content">
                    <h3 class="filter__subtitle">Categorie de chambres</h3>
                    @foreach ($categoriechambres as $categoriechambre)
                        <div class="filter" id="data_categoriechambre">
                            <input type="checkbox" name="categoriechambre_id" class="categoriechambre_id"
                                value="{{ $categoriechambre->id_categoriechambre }}" />
                            <p>{{ $categoriechambre->libelle_categoriechambre }}</p><span></span>
                        </div>
                    @endforeach

                </div>

                <div class="filter__content">
                    <h3 class="filter__subtitle">Hotel de la chambre</h3>
                    @foreach ($hotels as $hotel)
                        <div class="filter">
                            <input type="checkbox" name="hotel_id" class="hotel_id" value="{{ $hotel->id_hotel }}" />
                            <p>{{ $hotel->nom_hotel }}</p><span></span>
                        </div>
                    @endforeach

                </div>


                <div class="filter__content">
                    <h3 class="filter__subtitle">Ville de la chambre</h3>
                    @foreach ($villes as $ville)
                        <div class="filter">
                            <input type="checkbox" name="ville_id" class="ville_id" value="{{ $ville->id_ville }}" />
                            <p>{{ $ville->nom_ville }}</p><span></span>
                        </div>
                    @endforeach

                </div>


                <div class="filter__content">
                    <h3 class="filter__subtitle">Pays de la chambre</h3>
                    @foreach ($pays as $payskey)
                        <div class="filter">
                            <input type="checkbox" name="pays_id" class="pays_id" value="{{ $payskey->id_pays }}" />
                            <p>{{ $payskey->nom_pays }}</p><span></span>
                        </div>
                    @endforeach

                </div>


                <div class="filter__content">
                    <h3 class="filter__subtitle">Type</h3>
                    @foreach ($typehebergements as $type)
                        <div class="filter">
                            <input type="checkbox" name="typehbergement_id" class="typehbergement_id"
                                value="{{ $type->id_type }}" />
                            <p>{{ $type->nom_typehebergement }}</p><span></span>
                        </div>
                    @endforeach

                </div>




                <div class="filter__content">
                    <h3 class="filter__subtitle">Nombre de lits</h3>
                    <div class="filter">
                        <p>Min </p>
                        <input type="number" name="nombre_lits_min"
                            style="border: 1px solid #000;width: 100px;border-radius: 5px;" class="newsletter__input"
                            id="nombre_lits_min" />
                        <p>Max </p>
                        <input type="number" name="kilo_max"
                            style="border: 1px solid #000;width: 100px;border-radius: 5px;" class="newsletter__input"
                            id="kilo_max" />
                    </div>
                </div>


                <div class="filter__content">
                    <h3 class="filter__subtitle">Nombre de places</h3>
                    <div class="filter">
                        <p>Min </p>
                        <input type="number" name="nombre_places_min"
                            style="border: 1px solid #000;width: 100px;border-radius: 5px;" class="newsletter__input"
                            id="nombre_places_min" />
                        <p>Max </p>
                        <input type="number" name="nombre_places_max"
                            style="border: 1px solid #000;width: 100px;border-radius: 5px;" class="newsletter__input"
                            id="nombre_places_max" />
                    </div>
                </div>


                <div class="filter__content">
                    <h3 class="filter__subtitle">Classe de la chambre</h3>
                    <div class="filter">
                        <input type="checkbox" name="classe_chambre" id="classe_chambre" value="classe_chambre" />
                        <p>classe chambre</p><span></span>
                    </div>
                </div>

            </div>

            <div id="dataChambrePaginate">
                @include('pages.data.dataChambre')
            </div>
        </div>

        {{-- <div class="">
            {!! $chambres->links() !!}
           
        </div> --}}
    </section>
@endsection

@push('script-reserver-chambre')
    <script>
        //Pagination avec ajax
        $(document).ready(function() {
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault()
                let page = $(this).attr('href').split('page=')[1];
                getAllChambres(page);
                searchPriceChambre(page);
                searchClasseChambre(page);
                searchNombreLits(page);
                searchNombrePlaces(page);
              
            })

            //Search global
            $('#search').on('keyup', function() {
                $value = $(this).val();
                getAllChambres(1);
            })

            //Search prix min
            $('#prix_min').on('keyup', function() {
                $prix_min = $(this).val();
                searchPriceChambre(1);
            })

            //Search prix max
            $('#prix_max').on('keyup', function() {
                $prix_max = $(this).val();
                searchPriceChambre(1);
            })

            //Search categoriechambre
            $(".categoriechambre_id").on('click', function() {
                let categoriechambres = [];
                $(".categoriechambre_id").each(function() {
                    if ($(this).is(":checked")) {
                        categoriechambres.push($(this).val())
                    }
                })
                let categoriechambreChambre = categoriechambres.toString()

                $.ajax({
                    type: 'GET',
                    url: '{{ route('fetch.chambre') }}' + '?page=' + 1,
                    data: {
                        'categoriechambres': categoriechambreChambre,
                    },
                    success: (chambres) => {
                        $("#dataChambrePaginate").html(chambres)
                    },
                    error: (error) => {
                        console.log("Paginate Chambre Erreur")
                        console.log(error)
                    }
                })
            })

            //Search hotel
            $(".hotel_id").on('click', function() {
                let hotels = [];
                $(".hotel_id").each(function() {
                    if ($(this).is(":checked")) {
                        hotels.push($(this).val())
                    }
                })
                let hotelChambre = hotels.toString()

                $.ajax({
                    type: 'GET',
                    url: '{{ route('fetch.chambre') }}' + '?page=' + 1,
                    data: {
                        'hotels': hotelChambre,
                    },
                    success: (chambres) => {
                        $("#dataChambrePaginate").html(chambres)
                    },
                    error: (error) => {
                        console.log("Paginate Chambre Erreur")
                        console.log(error)
                    }
                })
            })

            //Search ville
            $(".ville_id").on('click', function() {
                let villes = [];
                $(".ville_id").each(function() {
                    if ($(this).is(":checked")) {
                        villes.push($(this).val())
                    }
                })
                let villeChambre = villes.toString()

                $.ajax({
                    type: 'GET',
                    url: '{{ route('fetch.chambre') }}' + '?page=' + 1,
                    data: {
                        'villes': villeChambre,
                    },
                    success: (chambres) => {
                        $("#dataChambrePaginate").html(chambres)
                    },
                    error: (error) => {
                        console.log("Paginate Chambre Erreur")
                        console.log(error)
                    }
                })
            })


            //Search pays
            $(".pays_id").on('click', function() {
                let pays = [];
                $(".pays_id").each(function() {
                    if ($(this).is(":checked")) {
                        payss.push($(this).val())
                    }
                })
                let paysChambre = pays.toString()

                $.ajax({
                    type: 'GET',
                    url: '{{ route('fetch.chambre') }}' + '?page=' + 1,
                    data: {
                        'payss': paysChambre,
                    },
                    success: (chambres) => {
                        $("#dataChambrePaginate").html(chambres)
                    },
                    error: (error) => {
                        console.log("Paginate Chambre Erreur")
                        console.log(error)
                    }
                })
            })




            //Search typehebergement
            $(".typehebergement_id").on('click', function() {
                let typehebergement = [];
                $(".typehebergement_id").each(function() {
                    if ($(this).is(":checked")) {
                        typehebergements.push($(this).val())
                    }
                })
                let typehebergementChambre = typehebergements.toString()

                $.ajax({
                    type: 'GET',
                    url: '{{ route('fetch.chambre') }}' + '?page=' + 1,
                    data: {
                        'typehebergements': typehebergementChambre,
                    },
                    success: (chambres) => {
                        $("#dataChambrePaginate").html(chambres)
                    },
                    error: (error) => {
                        console.log("Paginate Chambre Erreur")
                        console.log(error)
                    }
                })
            })




            //Search nombre de lits min
            $('#nombre_lits_min').on('keyup', function() {
                $nombre_lits_min = $(this).val();
                searchNombreLits(1);
            })

            //Search nombre de lits max
            $('#nombre_lits_max').on('keyup', function() {
                $nombre_lits_max = $(this).val();
                searchNombreLits(1);
            })

            //Search nombre de places min
            $('#nombre_places_min').on('keyup', function() {
                $nombre_places_min = $(this).val();
                searchNombrePlaces(1);
            })

            //Search Search nombre de places max
            $('#nombre_places_max').on('keyup', function() {
                $nombre_places_max = $(this).val();
                searchNombrePlaces(1);
            })

            //Search classe chambre
            $('#classe_chambre').on('click', function() {
                $classe_chambre = $(this).val();
                searchClasseChambre(1);
            })

        })

        function searchPriceChambre(page) {
            let prixMin = $('#prix_min').val();
            let prixMax = $('#prix_max').val();

            $.ajax({
                type: 'GET',
                url: '{{ route('fetch.chambre') }}' + '?page=' + page,
                data: {
                    'prix_min': prixMin,
                    'prix_max': prixMax,
                },
                success: (chambres) => {
                    $("#dataChambrePaginate").html(chambres)
                },
                error: (error) => {
                    console.log("Paginate Chambre Erreur")
                    console.log(error)
                }
            })
        }

        function searchNombrePlaces(page) {
            let nbrePlaceMin = $('#nombre_places_min').val();
            let nbrePlaceMax = $('#nombre_places_max').val();

            $.ajax({
                type: 'GET',
                url: '{{ route('fetch.chambre') }}' + '?page=' + page,
                data: {
                    'nombre_places_min': nbrePlaceMin,
                    'nombre_places_max': nbrePlaceMax,
                },
                success: (chambres) => {
                    $("#dataChambrePaginate").html(chambres)
                },
                error: (error) => {
                    console.log("Paginate Chambre Erreur")
                    console.log(error)
                }
            })
        }

        function searchNombreLits(page) {
            let nbreLitsMin = $('#nombre_lits_min').val();
            let nbreLitsMax = $('#nombre_lits_max').val();

            $.ajax({
                type: 'GET',
                url: '{{ route('fetch.chambre') }}' + '?page=' + page,
                data: {
                    'nombre_lits_min': nbreLitsMin,
                    'nombre_lits_max': nbreLitsMax,
                },
                success: (chambres) => {
                    $("#dataChambrePaginate").html(chambres)
                },
                error: (error) => {
                    console.log("Paginate Chambre Erreur")
                    console.log(error)
                }
            })
        }

        function searchClasseChambre(page) {
            let classechambre = $('#classe_chambre').val();
            $.ajax({
                type: 'GET',
                url: '{{ route('fetch.chambre') }}' + '?page=' + page,
                data: {
                    'classe_chambre': classechambre,
                },
                success: (chambres) => {
                    $("#dataChambrePaginate").html(chambres)
                },
                error: (error) => {
                    console.log("Paginate Chambre Erreur")
                    console.log(error)
                }
            })
        }




        function getAllChambres(page) {
            let searchChambre = $('#search').val();
            $.ajax({
                type: 'GET',
                url: '{{ route('fetch.chambre') }}' + '?page=' + page,
                data: {
                    'search': searchChambre,
                },
                success: (chambres) => {
                    $("#dataChambrePaginate").html(chambres)
                },
                hambres
                error: (error) => {
                    console.log("Paginate Chambre Erreur")
                    console.log(error)
                }
            })
        }
    </script>
@endpush
