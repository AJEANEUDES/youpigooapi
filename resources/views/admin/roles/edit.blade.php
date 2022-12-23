
@extends('layouts.master')

@section('content')

    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>Modifier un Rôle</h4>
                <p class="mb-0">Modifier un Rôle</p>
            </div>
        </div>




        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('admin/roles/') }}">Rôles</a></li>
                <li class="breadcrumb-item active"><a href="#">Modifier Rôle</a></li>
            </ol>
        </div>
        <div class="card-header d-sm-flex d-block float-end">
            <a href="{{ url('admin/roles') }}" class="btn btn-danger float-end">Retour</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Modifier Un Rôle</h4>
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
                

                    <form action="{{ url('admin/roles/update/' .  $role->id) }}" method="POST" enctype="multipart/form-data">


                        @csrf
                        @method('PUT')


                        <div class="form-validation">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label" for="name">Nom du rôle
                                            <span class="text-danger">*</span>
                                        </label>


                                        <div class="col-lg-6">
                                            <input type="text" class="form-control  @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ $role->name }}" required
                                                autocomplete="name" placeholder="Enter le nom et le prénoms..">

                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="val-suggestions">Permissions
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            @foreach($permission as $value)
                                                <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                                {{ $value->name }}</label>
                                            <br/>
                                            @endforeach
                                        </div>
                                    </div>


                                </div>

                            </div>

                            <div>
                                <button type="submit" class="btn mr-2 btn-primary ">Mettre à Jour</button>
                            </div>
                            <div>
                                <a href="{{ url('admin/roles') }}"class="btn btn-light">Annuler</a>
                            </div>

                        </div>
                </div>
                </form>

            </div>
        </div>
    </div>

    </div>

@endsection
