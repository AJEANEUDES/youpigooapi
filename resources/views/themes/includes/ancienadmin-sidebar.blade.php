<div class="deznav">
    <div class="deznav-scroll">
        <div class="main-profile">
            <div class="image-bx">
                <img src="{{ asset('admin/images/Untitled-1.jpg') }}" alt="">
                <a href="javascript:void(0);"><i class="fa fa-cog" aria-hidden="true"></i></a>
            </div>
            <h5 class="name"><span class="font-w400">Bonjour,</span> {{ Auth::user()->nom }}
                 {{ Auth::user()->prenoms }}</h5>
                 
            <p class="email"><a href="/cdn-cgi/l/email-protection" class="__cf_email__"
                    data-cfemail="95f8f4e7e4e0f0efefefefd5f8f4fcf9bbf6faf8">{{ Auth::user()->email }}</a></p>
        </div>
        <ul class="metismenu" id="menu">
            <li class="nav-label first"> Menu Principal</li>
            <li><a class="has-arrow ai-icon" href="{{ url('admin/tableaudebord') }}" aria-expanded="false">
                    <i class="flaticon-144-layout"></i>
                    <span class="nav-text">Tableau de Bord</span>
                </a>


            </li>

            <li class="nav-label">Gestion des Utilisateurs</li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-077-menu-1"></i>
                    <span class="nav-text">Utilisateurs, Rôles et <br> Permissions</span>
                </a>
                <ul aria-expanded="false">

                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Utilisateurs
                        </a>
                        <ul aria-expanded="false">
                            <li><a class="nav-link" href="{{ url('admin/users') }}">Liste des Utilisateurs</a></li>

                            <li><a href="{{ url('admin/users/create-user') }}"> Créer un Utilisateur</a></li>
                        </ul>
                    </li>



                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Rôles
                        </a>
                        <ul aria-expanded="false">
                            <li><a class="nav-link" href="{{ url('admin/roles') }}">Liste des Rôles</a></li>

                            <li><a href="{{ url('admin/roles/create-role') }}"> Créer un Rôle</a></li>
                        </ul>
                    </li>


                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Permissions
                        </a>
                        <ul aria-expanded="false">
                            <li><a class="nav-link" href="{{ url('admin/permissions') }}">Liste des Permissions</a></li>

                            <li><a href="{{ url('admin/permissions/create-permission') }}"> Créer un Permission</a></li>
                        </ul>
                    </li>

                </ul>
            </li>


            <li class="nav-label">Gestion des Hôtels </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-077-menu-1"></i>
                    <span class="nav-text">Apps</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="app-profile.html">Profile</a></li>
                    <li><a href="post-details.html">Post Details</a></li>
                    <li><a href="page-chat.html">Chat<span class="badge badge-xs badge-danger">New</span></a>
                    </li>
                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Project<span
                                class="badge badge-xs badge-danger">New</span></a>
                        <ul aria-expanded="false">
                            <li><a href="project-list.html">Project List</a></li>
                            <li><a href="project-card.html">Project Card</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">User<span
                                class="badge badge-xs badge-danger">New</span></a>
                        <ul aria-expanded="false">
                            <li><a href="user-list-datatable.html">User List</a></li>
                            <li><a href="user-list-column.html">User Card</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Contact<span
                                class="badge badge-xs badge-danger">New</span></a>
                        <ul aria-expanded="false">
                            <li><a href="contact-list.html">Contact List</a></li>
                            <li><a href="contact-card.html">Contact Card</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Email</a>
                        <ul aria-expanded="false">
                            <li><a href="email-compose.html">Compose</a></li>
                            <li><a href="email-inbox.html">Inbox</a></li>
                            <li><a href="email-read.html">Read</a></li>
                        </ul>
                    </li>
                    <li><a href="app-calender.html">Calendar</a></li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Shop</a>
                        <ul aria-expanded="false">
                            <li><a href="ecom-product-grid.html">Product Grid</a></li>
                            <li><a href="ecom-product-list.html">Product List</a></li>
                            <li><a href="ecom-product-detail.html">Product Details</a></li>
                            <li><a href="ecom-product-order.html">Order</a></li>
                            <li><a href="ecom-checkout.html">Checkout</a></li>
                            <li><a href="ecom-invoice.html">Invoice</a></li>
                            <li><a href="ecom-customers.html">Customers</a></li>
                        </ul>
                    </li>
                </ul>
            </li>


            <li class="nav-label">Gestion des Chambres</li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-077-menu-1"></i>
                    <span class="nav-text">Catégories, Chambres</span>
                </a>
                <ul aria-expanded="false">

                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Catégories de Chambres</a>
                        <ul aria-expanded="false">
                            <li><a href="user-list-datatable.html">Liste des Catégories de Chambres</a></li>
                            <li><a href="user-list-column.html">Créer une Catégorie</a></li>
                        </ul>
                    </li>
                </ul>
            </li>






            

            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-061-puzzle"></i>
                    <span class="nav-text">Charts</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="chart-flot.html">Flot</a></li>
                    <li><a href="chart-morris.html">Morris</a></li>
                    <li><a href="chart-chartjs.html">Chartjs</a></li>
                    <li><a href="chart-chartist.html">Chartist</a></li>
                    <li><a href="chart-sparkline.html">Sparkline</a></li>
                    <li><a href="chart-peity.html">Peity</a></li>
                </ul>
            </li>
            <li class="nav-label">components</li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-003-diamond"></i>
                    <span class="nav-text">Bootstrap</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="ui-accordion.html">Accordion</a></li>
                    <li><a href="ui-alert.html">Alert</a></li>
                    <li><a href="ui-badge.html">Badge</a></li>
                    <li><a href="ui-button.html">Button</a></li>
                    <li><a href="ui-modal.html">Modal</a></li>
                    <li><a href="ui-button-group.html">Button Group</a></li>
                    <li><a href="ui-list-group.html">List Group</a></li>
                    <li><a href="ui-media-object.html">Media Object</a></li>
                    <li><a href="ui-card.html">Cards</a></li>
                    <li><a href="ui-carousel.html">Carousel</a></li>
                    <li><a href="ui-dropdown.html">Dropdown</a></li>
                    <li><a href="ui-popover.html">Popover</a></li>
                    <li><a href="ui-progressbar.html">Progressbar</a></li>
                    <li><a href="ui-tab.html">Tab</a></li>
                    <li><a href="ui-typography.html">Typography</a></li>
                    <li><a href="ui-pagination.html">Pagination</a></li>
                    <li><a href="ui-grid.html">Grid</a></li>

                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-053-heart"></i>
                    <span class="nav-text">Plugins</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="uc-select2.html">Select 2</a></li>
                    <li><a href="uc-nestable.html">Nestedable</a></li>
                    <li><a href="uc-noui-slider.html">Noui Slider</a></li>
                    <li><a href="uc-sweetalert.html">Sweet Alert</a></li>
                    <li><a href="uc-toastr.html">Toastr</a></li>
                    <li><a href="map-jqvmap.html">Jqv Map</a></li>
                    <li><a href="uc-lightgallery.html">Light Gallery</a></li>
                </ul>
            </li>
            <li><a href="widget-basic.html" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-381-settings-2"></i>
                    <span class="nav-text">Widget</span>
                </a>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-044-file"></i>
                    <span class="nav-text">Forms</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="form-element.html">Form Elements</a></li>
                    <li><a href="form-wizard.html">Wizard</a></li>
                    <li><a href="form-editor-summernote.html">Summernote</a></li>
                    <li><a href="form-pickers.html">Pickers</a></li>
                    <li><a href="form-validation-jquery.html">Jquery Validate</a></li>
                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-network"></i>
                    <span class="nav-text">Table</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="table-bootstrap-basic.html">Bootstrap</a></li>
                    <li><a href="table-datatable-basic.html">Datatable</a></li>
                </ul>
            </li>
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-049-copy"></i>
                    <span class="nav-text">Pages</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="page-register.html">Register</a></li>
                    <li><a href="page-login.html">Login</a></li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Error</a>
                        <ul aria-expanded="false">
                            <li><a href="page-error-400.html">Error 400</a></li>
                            <li><a href="page-error-403.html">Error 403</a></li>
                            <li><a href="page-error-404.html">Error 404</a></li>
                            <li><a href="page-error-500.html">Error 500</a></li>
                            <li><a href="page-error-503.html">Error 503</a></li>
                        </ul>
                    </li>
                    <li><a href="page-lock-screen.html">Lock Screen</a></li>
                </ul>
            </li>
        </ul>

    </div>
</div>
