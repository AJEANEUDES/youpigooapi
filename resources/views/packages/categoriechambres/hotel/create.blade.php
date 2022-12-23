@extends('themes.master')

@section('title')
    Administrateurs - Ajouter un Administrateur :: Youpigoo
@endsection

@section('content')

    <div class="px-4 container-fluid">

        <div class="card">
            <div class="card-header">
                <h4 class="">Ajouter un Administrateur
                    <a href="{{ url('Admin/administrateur/') }}"class="btn btn-danger float-end">Retour</a>

                </h4>
            </div>
            <div class="card-body"> 

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif



                <form action="{{ url('Admin/administrateur/ajouter-admin') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card-title">
                        <h4>AJOUT D'UN NOUVEL ADMIN</h4>
                    </div>

                    <div class="mb-3">
                        <label for="nom_user" class="form-label">Nom</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="nom_user"
                            class="form-control @error('nom_user') is-invalid @enderror" name="nom_user"
                            value="{{ old('nom_user') }}" required autocomplete="nom_user" autofocus />

                        @error('nom_user')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                    </div>



                    <div class="mb-3">
                        <label for="prenoms_user" class="form-label">Prénoms</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="prenoms_user"
                            class="form-control @error('prenoms_user') is-invalid @enderror" name="prenoms_user"
                            value="{{ old('prenoms_user') }}" required autocomplete="prenoms_user" autofocus />

                        @error('prenoms_user')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>



                    <div class="mb-3">

                        <label for="adresse_user" class="form-label">Adresse</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="adresse_user"
                            class="form-control @error('adresse_user') is-invalid @enderror" name="adresse_user"
                            value="{{ old('adresse_user') }}" required autocomplete="adresse_user" autofocus />

                        @error('adresse_user')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>



                    <div class="mb-3">
                        <label for="image_user">Image</label>
                        <input type="file" name="image_user" class="form-control">
                    </div>


                    <div class="mb-3">

                        <label for="telephone_user" class="form-label">Téléphone</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="telephone_user"
                            class="form-control @error('telephone_user') is-invalid @enderror" name="telephone_user"
                            value="{{ old('telephone_user') }}" required autocomplete="telephone_user" autofocus />

                        @error('telephone_user')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                    </div>

                    <div class="mb-3">

                        <label for="ville_user" class="form-label">Ville</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="ville_user"
                            class="form-control @error('ville_user') is-invalid @enderror" name="ville_user"
                            value="{{ old('ville_user') }}" required autocomplete="ville_user" autofocus />

                        @error('ville_user')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                    </div>


                    <div class="mb-3">

                        <label for="pays_user" class="form-label">Pays</label>
                        
                        <input type="text" style="height: 55px;border-radius: 10px;" id="pays_user"
                            class="form-control @error('pays_user') is-invalid @enderror" name="pays_user"
                            value="{{ old('pays_user') }}" required autocomplete="pays_user" autofocus />

                        @error('pays_user')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                    </div>

                    <div class="mb-3">
                        <label for="email_user" class="form-label">Adresse Email</label>

                        <input id="email_user" type="email" class="form-control @error('email_user') is-invalid @enderror"
                            name="email_user" value="{{ old('email_user') }}" required autocomplete="email_user">

                        @error('email_user')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>

                    {{-- <div class="mb-3">
                        <label for=""> Rôle </label>
                        <select name="roles_user"  value="{{ old('roles_user') }}" class="form-control">
                        </select>
                    </div>
                   
                    
                    <div class="mb-3">
                        <label for=""> Status </label>
                        <select name="status_user"   value="{{ old('status_user') }}" class="form-control">
                        </select>
                    </div>
                    --}}

                    <div class="mb-3 row">
                        <label for="password" class="form-label">Mot de Passe</label>

                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>

                    <div class="mb-3 row">
                        <label for="confirmation_password" class="form-label">Retaper le mot de Passe</label>

                            <input id="confirmation_password" type="password" class="form-control"
                             name="confirmation_password" required autocomplete="new-password">
                             
                    </div>

                
                   

                    <div class="row">
                        

                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary float-end">Enregistrer</button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ url('Admin/administrateur/') }}"class="btn btn-danger">Annuler</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
