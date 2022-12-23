<!-------------------------------------------- HEADER ------------------------------------------->

    <header class="header" id="header">
        <nav class="nav container">
            <a href="{{ url('/') }}" class="nav__logo">
                <!--<i class="bx bxs-shopping-bags nav__logo-icon">e-commerce</i>-->
                <img src="{{ asset('assets/img/logo.jpg') }}" alt="" class="nav__logo-mct">
            </a>

            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="{{ url('/') }}" class="nav__link">Accueil
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="{{ url('/reserver-chambre') }}" class="nav__link">Reserver une Chambre</a>
                    </li>
                    <li class="nav__item">
                        <a href="{{ url('/contactez-nous') }}" class="nav__link">Contactez-nous</a>
                    </li>
                    <li class="nav__item">
                        <a href="{{ url('/inscription') }}" class="nav__link">Inscription</a>
                    </li>
                    {{-- <li class="nav__item">
                    <a href="{{ url('about.get') }}"
                        class="nav__link @if (isActiveLink(['about.get'])) active-link @endif">A propos</a>
                </li> --}}


                    @if (Auth::check() && Auth::user()->roles_user == 'Client')
                        <li class="nav__item">
                            <a href="{{ url('client/tableau-de-bord') }}" class="nav__link"
                                {{-- @if (isActiveLink(['utilisateur.dashbord']) ||
                                    isActiveLink(['utilisateur.profile']) ||
                                    isActiveLink(['utilisateur.mdp'])) active-link @endif" --}}
                                    
                                    >Voir mon
                                compte</a>
                        </li>
                    @endif


                </ul>

                <div class="nav__close" id="nav-close">
                    <i class="bx bx-x"></i>
                </div>

            </div>


            @guest
                <div class="nav__btns">
                    <div class="login__toggle">
                        <a style="color: #fff;" href="{{ url('login') }}"
                            class="nav__link button__login">
                            <i style="font-size: 20px;" class="bx bx-user"></i> Se connecter
                        </a>
                    </div>
                    <div class="nav__toggle" id="nav-toggle">
                        <i class="bx bx-grid-alt"></i>
                    </div>
                </div>
            @endguest


            @if (Auth::check())
                {{-- <div class="login__toggle">
            <a style="color: #fff;" href="{{ route('login') }}"
                class="nav__link button__login @if (isActiveLink(['login'])) active-link @endif">
                <i style="font-size: 20px;" class="bx bx-door-open"></i> Se deconnecter
            </a>
        </div> --}}
                <div class="login__toggle">
                    <a class="dropdown-item d-flex align-items-center button__login" href="{{ url('logout') }}"
                        onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                        <span style="font-size: 20px;"><i class="bx bx-door-open"></i> Se deconnecter</span>
                    </a>
                    <form id="logout-form" action="{{ url('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            @endif




        </nav>
    </header>
    <!-- END nav -->
