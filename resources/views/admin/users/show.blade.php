@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4>Détails des informations sur un utilisateur</h4>
                        <span>Informations Utilisateur</span>
                    </div>
                    <a href="{{ url('admin/users') }}" class="btn btn-danger light">Retour</a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-4 col-sm-6-center">
            <div class="card user-card">
                <div class="card-body pb-0-center">
                    <div class="d-flex mb-3 align-items-center">
                        <div class="dz-media mr-3">
                            <img src="{{ asset('admin/images/users/pic1.jpg') }}" alt="">
                        </div>
                        <div>
                        </div>
                    </div>
                    <p class="fs-12">
                         <h5 class="title"><a href="javascript:void(0);">{{ $user->name }}</a></h5>
                    </p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <span class="mb-0 title">Email</span> :
                            <span class="text-black btn btn-primary btn-xs">
                                {{ $user->email }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="mb-0 title">Téléphone</span> :
                            <span class="text-black ml-2">{{ $user->telephone }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="mb-0 title">Pays</span> :
                            <span class="text-black desc-text ml-2">{{ $user->pays }}</span>
                        </li>
                    </ul>
                </div>
               
            </div>
        </div>
    </div>
@endsection
