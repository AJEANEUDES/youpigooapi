@extends('themes.master')
@section('title')
Tableau de bord :: Youpigoo
@endsection
@section('content')
<div class="pagetitle">
    <h1>Tableau de bord</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Accueil</a></li>
            <li class="breadcrumb-item active">Tableau de bord</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-12">
            <div class="row">
                <!-- Sales Card -->
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">categoriechambres</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bx-tag"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $categoriechambres }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Sales Card -->

                <!-- Revenue Card -->
                {{-- <div class="col-xxl-3 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Modeles</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bx-bus"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $modeles }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <!-- End Revenue Card -->

                <!-- Customers Card -->
                <div class="col-xxl-3 col-xl-12">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">chambres</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bx-car"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $chambres }}</h6>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- End Customers Card -->

                <!-- Customers Card -->
                <div class="col-xxl-3 col-xl-12">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Images de chambres</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bx-image"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ $image_chambres }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Customers Card -->

            </div>
        </div><!-- End Left side columns -->
    </div>
</section>
@endsection