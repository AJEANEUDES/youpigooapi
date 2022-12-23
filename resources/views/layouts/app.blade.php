{{-- Le fichier app.blade.php est le fichier principal de la vue du côté client --}}

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Youpigoo, site de reservation de chambre, etc..." name="description">
    <meta content="Youpigoo," name="keywords">
    <meta content="e-Media" name="author">

    <!--=============== BOXICONS ===============-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!--=============== SWIPER CSS ===============-->
    <link rel="stylesheet" href="{{ asset('oldassets/css/swiper-bundle.min.css') }}">

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="{{ asset('oldassets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('oldassets/css/colors/color-1.css') }}">

    <title>@yield('title')</title>

    <!-- Favicons -->



</head>






<body>

    @include('layouts.header')

 

    <!--=============== CART (show-cart)===============-->
    <div class="cart" id="cart">
        <i class="bx bx-x cart__close" id="cart-close"></i>
        <h2 class="cart__title-center">Mon Panier</h2>

        <div class="cart__container">
            <article class="cart__card">
                <div class="cart__box">
                    <img src="{{ asset('oldassets/img/product-2.png') }}" alt="" class="cart__img" />
                </div>
                <div class="cart__details">
                    <h3 class="cart__title">Windbreaker</h3>
                    <span class="cart__price">50000000</span>
                    <div class="cart__amount">
                        <div class="cart__amount-content">
                            <span class="cart__amount-box">
                                <i class="bx bx-minus"></i>
                            </span>
                            <span class="cart__amount-number">1</span>
                            <span class="cart__amount-box">
                                <i class="bx bx-plus"></i>
                            </span>
                        </div>

                        <i class="bx bx-trash-alt cart__amount-trash"></i>
                    </div>
                </div>
            </article>

            <article class="cart__card">
                <div class="cart__box">
                    <img src="{{ asset('oldassets/img/product-2.png') }}" alt="" class="cart__img" />
                </div>
                <div class="cart__details">
                    <h3 class="cart__title">Cardigan Hoodie</h3>
                    <span class="cart__price">20000000</span>
                    <div class="cart__amount">
                        <div class="cart__amount-content">
                            <span class="cart__amount-box">
                                <i class="bx bx-minus"></i>
                            </span>
                            <span class="cart__amount-number">1</span>
                            <span class="cart__amount-box">
                                <i class="bx bx-plus"></i>
                            </span>
                        </div>

                        <i class="bx bx-trash-alt cart__amount-trash"></i>
                    </div>
                </div>
            </article>

            <article class="cart__card">
                <div class="cart__box">
                    <img src="{{ asset('oldassets/img/product-2.png') }}" alt="" class="cart__img" />
                </div>
                <div class="cart__details">
                    <h3 class="cart__title">Cartoon</h3>
                    <span class="cart__price">15000000</span>
                    <div class="cart__amount">
                        <div class="cart__amount-content">
                            <span class="cart__amount-box">
                                <i class="bx bx-minus"></i>
                            </span>
                            <span class="cart__amount-number">1</span>
                            <span class="cart__amount-box">
                                <i class="bx bx-plus"></i>
                            </span>
                        </div>

                        <i class="bx bx-trash-alt cart__amount-trash"></i>
                    </div>
                </div>
            </article>
        </div>

        <div class="cart__prices">
            <span class="cart__prices-item">3 items</span>
            <span class="cart__prices-total">75000000</span>
        </div>
    </div>


    <!--=============== MAIN ===============-->
    <main class="main">
        @yield('content')
    </main>




    @include('layouts.footer')


    {{-- 
    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen">
        <svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4"
                stroke="#eeeeee" />
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4"
                stroke-miterlimit="10" stroke="#F96D00" />
        </svg>
    </div> --}}


    <!--=============== SCROLL UP ===============-->
    <a href="#" class="scrollup" id="scroll-up">
        <i class="bx bx-up-arrow-alt scrollup__icon"></i>
    </a>




    <!--=============== STYLE SWITCHER ===============-->

    <!--=============== SWIPER JS ===============-->
    <script src="{{ asset('oldassets/js/swiper-bundle.min.js') }}"></script>

    <!--=============== JS ===============-->
    <script src="{{ asset('oldassets/js/main.js') }}"></script>

    <!-- Vendor JS Files -->
    <script src="{{ asset('js/jQuery-3.6.js') }}"></script>
    <script src="{{ asset('js/utils.min.js') }}"></script>
    <script src="{{ asset('js/sweet-alert.js') }}"></script>
    <script src="{{ asset('js/notiflix-aio-3.2.4.min.js') }}"></script>

    @stack('script-reserver-voiture')
    @stack('script-details')
    @stack('scripts-inscription-check-countries')

    <script src="{{ asset('js/request-app.js') }}"></script>


</body>

</html>
