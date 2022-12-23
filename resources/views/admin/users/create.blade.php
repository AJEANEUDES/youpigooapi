@extends('layouts.master')

@section('content')

    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>Créer un Utilisateur</h4>
                <p class="mb-0">Créer un utilisateur</p>
            </div>
        </div>




        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('admin/users/') }}">Utilisateurs</a></li>
                <li class="breadcrumb-item active"><a href="#">Créer utilisateur</a></li>
            </ol>
        </div>
        <div class="card-header d-sm-flex d-block float-end">
            <a href="{{ url('admin/users') }}" class="btn btn-danger float-end">Retour</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Créer Un Utilisateur</h4>
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Désolé!</strong> Il y a eu quelques problèmes avec votre entrée.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <form action="{{ url('admin/users/create-user') }}" method="POST" enctype="multipart/form-data">


                        @csrf

                        <div class="form-validation">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label" for="name">Nom et prénoms
                                            <span class="text-danger">*</span>
                                        </label>


                                        <div class="col-lg-6">
                                            <input type="text" class="form-control  @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ old('name') }}" required
                                                autocomplete="name" placeholder="Enter le nom et le prénoms..">

                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label" for="email">Adresse Email <span
                                                class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control  @error('email') is-invalid @enderror"
                                                id="email" name="email" value="{{ old('email') }}" required
                                                autocomplete="email" placeholder="Votre adresse email valide..">

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                        </div>
                                    </div>



                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label" for="val-password">Mot de passe
                                            <span class="text-danger">*</span>
                                        </label>

                                        <div class="col-lg-6">
                                            <div class="input-group transparent-append">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                                </div>
                                                <input type="password" id="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" required autocomplete="new-password">

                                                <div class="input-group-append show-pass ">
                                                    <span class="input-group-text ">
                                                        <i class="fa fa-eye-slash"></i>
                                                        <i class="fa fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label" for="val-confirm-password">Confirmer
                                            le Mot de Passe <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-6">
                                            <div class="input-group transparent-append">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                                </div>
                                                <input type="password" id="confirm-password"
                                                    class="form-control @error('confirm-password') is-invalid @enderror"
                                                    name="confirm-password" required autocomplete="confirm-password">

                                                <div class="input-group-append show-pass ">
                                                    <span class="input-group-text ">
                                                        <i class="fa fa-eye-slash"></i>
                                                        <i class="fa fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        @error('confirm-password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>



                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label" for="val-suggestions">Roles
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-6">
                                            <div class="form-group">

                                                {!! Form::select('roles[]', $roles, [], ['class' => 'form-control', 'multiple']) !!}

                                                </select>


                                            </div>
                                        </div>


                                    </div>

                                </div>

                                <div>
                                    <button type="submit" class="btn mr-2 btn-primary ">Enregistrer</button>
                                </div>
                                <div>
                                    <a href="{{ url('admin/users') }}"class="btn btn-light">Annuler</a>
                                </div>

                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>

@endsection
