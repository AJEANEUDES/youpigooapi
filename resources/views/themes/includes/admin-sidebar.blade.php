<div id="layoutSidenav_nav">


    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link {{ Request::Is('Admin/tableau-de-bord') ? 'active' : '' }} "
                    href="{{ url('Admin/tableau-de-bord') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Tableau de Bord
                </a>
                <div class="sb-sidenav-menu-heading">MENU PRINCIPAL</div>

                <a class="nav-link {{ Request::Is('Admin/categoriechambres') ? 'active' : '' }} "
                    href="{{ url('Admin/categoriechambres') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Catégories
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-right"></i></div>
                </a>


                <a class="nav-link {{ Request::Is('Admin/pays') ? 'active' : '' }} " href="{{ url('Admin/pays') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Pays
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-right"></i></div>
                </a>


                <a class="nav-link {{ Request::Is('Admin/villes') ? 'active' : '' }} " href="{{ url('Admin/villes') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    villes
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-right"></i></div>
                </a>


                <a class="nav-link {{ Request::Is('Admin/types') ? 'active' : '' }} " href="{{ url('Admin/types') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Type Hebergements
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-right"></i></div>
                </a>

                <a class="nav-link {{ Request::Is('Admin/hotels') ? 'active' : '' }} " href="{{ url('Admin/hotels') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Hôtels
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-right"></i></div>
                </a>




                <a class="nav-link {{ Request::Is('admin/posts') || Request::Is('admin/add-post') || Request::Is('admin/edit-post/*') ? 'collapse active' : 'collapsed' }} "
                    href="#" data-bs-toggle="collapse" data-bs-target="#collapsePosts" aria-expanded="false"
                    aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Articles
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ Request::Is('admin/posts') || Request::Is('admin/add-post') || Request::Is('admin/edit-post/*') ? 'show ' : '' }}"
                    id="collapsePosts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ Request::Is('admin/add-post') ? 'active' : '' }} "
                            href="{{ url('admin/add-post') }}">Ajouter un Article</a>
                        <a class="nav-link {{ Request::Is('admin/posts') || Request::Is('admin/edit-post/*') ? 'active' : '' }}  ? 'active':'' }}"
                            href="{{ url('admin/posts') }}">Voir les Articles</a>
                    </nav>
                </div>

                <div class="sb-sidenav-menu-heading">GESTION DES UTILISATEURS</div>

                <a class="nav-link {{ Request::Is('Admin/administrateur/') ? 'active' : '' }} "
                    href="{{ url('Admin/administrateur/') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Administrateurs
                </a>

                <a class="nav-link {{ Request::Is('Admin/administrateur/') ? 'active' : '' }} "
                    href="{{ url('Admin/administrateur/') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Hôtels
                </a>

                <a class="nav-link {{ Request::Is('Admin/administrateur/') ? 'active' : '' }} "
                    href="{{ url('Admin/administrateur/') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Compagnies
                </a>



                <a class="nav-link {{ Request::Is('Admin/administrateur/') ? 'active' : '' }} "
                    href="{{ url('Admin/administrateur/') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Clients
                </a>


                <div class="sb-sidenav-menu-heading">Addons</div>
                <a class="nav-link" href="charts.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Charts
                </a>
                <a class="nav-link" href="tables.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Tables
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            Start Bootstrap
        </div>
    </nav>
</div>
