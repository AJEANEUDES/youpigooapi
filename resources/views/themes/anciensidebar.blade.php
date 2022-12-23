<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        @if (Auth::user()->roles_user == 'Admin')
            <li class="nav-item">
                <a class="nav-link" {{-- @if (!isActiveLink(['admin.tableaudebord'])) collapsed @endif" --}} href="{{ url('Admin/tableau-de-bord') }}">
                    <i style="font-size: 16px;" class="bi bi-grid"></i>
                    <span style="font-size: 16px;">Tableau de bord</span>
                </a>
            </li>
        @elseif(Auth::user()->roles_user == 'Hotel')
            <li class="nav-item">
                <a class="nav-link" {{-- @if (!isActiveLink(['gestionnairehotel.tableaudebord'])) collapsed @endif" --}} href="{{ url('hotel/tableau-de-bord') }}">
                    <i style="font-size: 16px;" class="bi bi-grid"></i>
                    <span style="font-size: 16px;">Tableau de bord</span>
                </a>
            </li>
        @endif

        <li class="nav-heading">MENU PRINCIPAL</li>

        @if (Auth::user()->roles_user == 'Admin')
            <li class="nav-item">
                <a class="nav-link" {{-- @if (!isActiveLink(['categoriechambres.get'])) collapsed @endif" --}} href="{{ url('Admin/categoriechambres/') }}">
                    <i style="font-size: 16px;" class="bx bx-tag"></i>
                    <span style="font-size: 16px;">Catégories de chambre</span>
                </a>
            </li>

            <!-- End Profile Page Nav -->
        @elseif(Auth::user()->roles_user == 'Hotel')
            <li class="nav-item">
                <a class="nav-link" {{-- @if (!isActiveLink(['hotel/.get.gestion'])) collapsed @endif" --}} href="{{ url('hotel/categoriechambres/') }}">
                    <i style="font-size: 16px;" class="bx bx-tag"></i>
                    <span style="font-size: 16px;">Catégories de chambre</span>
                </a>
            </li>
            <!-- End Profile Page Nav -->
        @endif

        @if (Auth::user()->roles_user == 'Admin')
            <li class="nav-item">
                <a class="nav-link" {{-- @if (!isActiveLink(['villes.get'])) collapsed @endif" --}} href="{{ url('Admin/villes/') }}">
                    <i style="font-size: 16px;" class="bx bx-bus"></i>
                    <span style="font-size: 16px;">Villes</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" {{-- @if (!isActiveLink(['pays.get'])) collapsed @endif" --}} href="{{ url('Admin/pays') }}">
                    <i style="font-size: 16px;" class="bx bx-bus"></i>
                    <span style="font-size: 16px;">Pays</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" {{-- @if (!isActiveLink(['typehebergements.get'])) collapsed @endif" --}} href="{{ url('Admin/typehebergements') }}">
                    <i style="font-size: 16px;" class="bx bx-bus"></i>
                    <span style="font-size: 16px;">Typehebergements</span>
                </a>
            </li>


            <!-- End Profile Page Nav -->
        @elseif(Auth::user()->roles_user == 'Hotel')
            {{-- <li class="nav-item">
            <a class="nav-link @if (!isActiveLink(['modeles-voitures.get.gestion'])) collapsed @endif"
                href="{{ url('modeles-voitures.get.gestion') }}">
                <i style="font-size: 16px;" class="bx bx-bus"></i>
                <span style="font-size: 16px;">Mes modeles</span>
            </a>
        </li> --}}

            <!-- End Profile Page Nav -->
        @endif

        @if (Auth::user()->roles_user == 'Admin')
            <li class="nav-item">
                <a class="nav-link" {{-- @if (!isActiveLink(['chambres.get'])) collapsed @endif" --}} href="{{ url('Admin/chambres') }}">
                    <i style="font-size: 16px;" class="bx bx-car"></i>
                    <span style="font-size: 16px;">Chambres</span>
                </a>
            </li>

            <!-- End Profile Page Nav -->
        @elseif(Auth::user()->roles_user == 'Hotel')
            <li class="nav-item">
                <a class="nav-link" {{-- @if (!isActiveLink(['chambres.get.gestion'])) collapsed @endif" --}} href="{{ url('hotel/chambres/') }}">
                    <i style="font-size: 16px;" class="bx bx-car"></i>
                    <span style="font-size: 16px;">Chambres</span>
                </a>
            </li>

            <!-- End Profile Page Nav -->
        @endif

        @if (Auth::user()->roles_user == 'Admin')
            <li class="nav-item">
                <a class="nav-link" {{-- @if (!isActiveLink(['images-chambres.get'])) collapsed @endif" --}} href="{{ url('Admin/images-chambres') }}">
                    <i style="font-size: 16px;" class="bx bxs-image"></i>
                    <span style="font-size: 16px;">Images pour les chambres</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" {{-- @if (!isActiveLink(['services-chambres.get'])) collapsed @endif" --}} href="{{ url('Admin/services-chambres/') }}">
                    <i style="font-size: 16px;" class="bx bxs-diamond"></i>
                    <span style="font-size: 16px;">services liés à la chambre</span>
                </a>
            </li>


            <!-- End Profile Page Nav -->
        @elseif(Auth::user()->roles_user == 'Hotel')
            <li class="nav-item">
                <a class="nav-link" {{-- @if (!isActiveLink(['images-chambres.get.gestion'])) collapsed @endif" --}} href="{{ url('hotel/images-chambres/') }}">
                    <i style="font-size: 16px;" class="bx bxs-image"></i>
                    <span style="font-size: 16px;">Images pour les chambres</span>
                </a>
            </li>

            <!-- End Profile Page Nav -->

            <li class="nav-item">
                <a class="nav-link" {{-- @if (!isActiveLink(['services-chambres.get.gestion'])) collapsed @endif" --}} href="{{ url('hotel/services-chambres/') }}">
                    <i style="font-size: 16px;" class="bx bxs-diamond"></i>
                    <span style="font-size: 16px;">services liés à la chambre</span>
                </a>
            </li>
        @endif

        @if (Auth::user()->roles_user == 'Admin')
            <li class="nav-item">
                <a class="nav-link" {{-- @if (!isActiveLink(['societes.get'])) collapsed @endif" --}} href="{{ url('Admin/societes/') }}">
                    <i style="font-size: 16px;" class="bx bxs-bank"></i>
                    <span style="font-size: 16px;"> societes</span>
                </a>
            </li>

            <!-- End Profile Page Nav -->

            <li class="nav-item">
                <a class="nav-link " {{-- @if (!isActiveLink(['hotels.get'])) collapsed @endif" --}} href="{{ url('Admin/hotels/') }}">
                    <i style="font-size: 16px;" class="bi bi-bookmarks-fill"></i>
                    <span style="font-size: 16px;">Hotels</span>
                </a>
            </li>

            <li class="nav-heading">GESTION DES RESERVATIONS</li>
            <li class="nav-item">
                <a class="nav-link" {{-- @if (!isActiveLink(['reservations.get'])) collapsed @endif"  --}} href="{{ url('Admin/reservations/') }}">
                    <i style="font-size: 16px;" class="bx bxs-carousel"></i>
                    <span style="font-size: 16px;"> Reservations</span>
                </a>
            </li><!-- End Profile Page Nav -->
            <li class="nav-item">
                <a class="nav-link" {{-- @if (!isActiveLink(['factures.get'])) collapsed @endif"  --}} href="{{ url('Admin/factures/') }}">
                    <i style="font-size: 16px;" class="bx bxs-file-blank"></i>
                    <span style="font-size: 16px;"> Factures</span>
                </a>
            </li><!-- End Profile Page Nav -->


            <!-- End Profile Page Nav -->

            {{-- <li class="nav-item">
            <a class="nav-link @if (!isActiveLink(['services-chambres.get'])) collapsed @endif"
                href="{{ url('services-chambres.get') }}">
                <i style="font-size: 16px;" class="bx bxs-diamond"></i>
                <span style="font-size: 16px;">services liés aux chambres</span>
            </a>
        </li> --}}


            {{-- <li class="nav-heading">GESTION DES RESERVATIONS</li>
            <li class="nav-item">
                <a class="nav-link"  --}}
                
                {{-- @if (!isActiveLink(['reservations.get'])) collapsed @endif" --}} 
                {{-- href="{{ url('reservations.get') }}">
                    <i style="font-size: 16px;" class="bx bxs-carousel"></i>
                    <span style="font-size: 16px;"> Reservations</span>
                </a>
            </li> --}}
            {{-- <li class="nav-item">
                <a class="nav-link @if (!isActiveLink(['factures.get'])) collapsed @endif"
                    href="{{ url('factures.get') }}">
                    <i style="font-size: 16px;" class="bx bxs-file-blank"></i>
                    <span style="font-size: 16px;"> Factures</span>
                </a>
            </li> --}}
            
            <!-- End Profile Page Nav -->

            <li class="nav-heading">GESTION DES UTILISATEURS</li>
            <li class="nav-item">
                <a class="nav-link"
                 {{-- @if (!isActiveLink(['Admin/admins/'])) collapsed @endif" --}}
                    href="{{ url('Admin/administrateur/') }}">
                    <i style="font-size: 16px;" class="bi bi-person"></i>
                    <span style="font-size: 16px;">Administrateurs</span>
                </a>
            </li><!-- End Profile Page Nav -->

            <li class="nav-item">
                <a class="nav-link"
                 {{-- @if (!isActiveLink(['Admin/hotel/'])) collapsed @endif" --}}

                    href="{{ url('Admin/hotel/') }}">
                    <i style="font-size: 16px;" class="bi bi-file-person"></i>
                    <span style="font-size: 16px;"> Hôtel</span>
                </a>
            </li><!-- End Profile Page Nav -->

            <li class="nav-item">
                <a class="nav-link"
                 {{-- @if (!isActiveLink(['clients.get'])) collapsed @endif" --}}
                    href="{{ url('Admin/clients/') }}">
                    <i style="font-size: 16px;" class="bi bi-people"></i>
                    <span style="font-size: 16px;">Clients</span>
                </a>
            </li><!-- End Profile Page Nav -->

            {{-- <li class="nav-heading">AUTRES MENU</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="javascript:void(0)">
                <i style="font-size: 16px;" class="bx bx-comment"></i>
                <span style="font-size: 16px;">Temoignages</span>
            </a>
        </li><!-- End Profile Page Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="javascript:void(0)">
                <i style="font-size: 16px;" class="bx bx-message"></i>
                <span style="font-size: 16px;">Messages</span>
            </a>
        </li><!-- End Register Page Nav --> --}}
        @endif

    </ul>

</aside>
