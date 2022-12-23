@extends('themes.master')
@section('title')
    Administrateurs - Editer un Administrateur :: Youpigoo
@endsection
@section('content')
    {{-- <div class="pagetitle">
        <h1 style="font-size: 25px;">
            Administrateurs
            <i class="bx bx-plus-circle" data-bs-toggle="modal" data-bs-target="#addAdminModal"
                style="position: relative;top: 10px;font-size: 40px;color: #2DAF07;cursor: pointer;"></i>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Accueil</a></li>
                <li class="breadcrumb-item active">Editer un Administrateur</li>
            </ol>
        </nav>
    </div>


    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body" style="overflow: auto;">
                        <h5 class="card-title" style="text-align: center;font-size: 25px;">Modifier un administrateur
                        </h5>

                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif


                        <form action="{{ url('Admin/administrateur/update-admin/' . $admin->id) }}" method="post"
                            enctype="multipart/form-data">


                            @csrf
                            @method('PUT')



                       


                                <div class=" row g-3">
                                    <div class="col-md-6">
                                        <label for="nom_user" class="form-label">Nom</label>
                                        <input type="text" style="height: 55px;border-radius: 10px;" name="nom_user"
                                            id="nom_user" placeholder="Enter le nom" value="{{ $admin->nom_user }}" 
                                            class="form-control  @error('nom_user') is-invalid @enderror" />

                                            @error('nom_user')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                    <div class="col-md-6">
                                        <label for="prenoms_user" class="form-label">Prenoms</label>
                                        <input type="text" style="height: 55px;border-radius: 10px;" name="prenoms_user"
                                            id="prenoms_user" value="{{ $admin->prenoms_user }}" 
                                            class="form-control  @error('prenoms_user') is-invalid @enderror" />

                                            @error('prenoms_user')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                    <div class="col-md-6">
                                        <label for="adresse_user" class="form-label">Adresse</label>
                                        <input type="text" style="height: 55px;border-radius: 10px;" name="adresse_user"
                                            id="adresse_user" value="{{ $admin->adresse_user }}" 
                                            class="form-control  @error('adresse_user') is-invalid @enderror" />

                                            @error('adresse_user')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror


                                    </div>
                                    <div class="col-md-6">
                                        <label for="telephone_user" class="form-label">Telephone</label>
                                        <input type="text" style="height: 55px;border-radius: 10px;"
                                            name="telephone_user" id="telephone_user"
                                            value="{{ $admin->telephone_user }}" 
                                            class="form-control  @error('telephone_user') is-invalid @enderror" />

                                            @error('telephone_user')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror


                                    </div>
                                    <div class="col-md-6">
                                        <label for="ville_user" class="form-label">Ville</label>
                                        <input type="text" style="height: 55px;border-radius: 10px;" name="ville_user"
                                            id="ville_user"  value="{{ $admin->ville_user }}" 
                                            class="form-control  @error('ville_user') is-invalid @enderror" />

                                            @error('ville_user')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                        
                                    </div>
                                    <div class="col-md-6">
                                        <label for="pays_user" class="form-label">Pays</label>
                                        <input type="text" style="height: 55px;border-radius: 10px;" name="pays_user"
                                            id="pays_user"  value="{{ $admin->pays_user }}" 
                                            class="form-control  @error('pays_user') is-invalid @enderror" />

                                            @error('pays_user')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                        
                                    </div>


                                    <div class="col-md-6">
                                        <label for="roles_user" class="form-label">Rôles</label>
                                        <select style="height: 55px;border-radius: 10px;text-transform: uppercase;"
                                            class="form-control form-control-lg" name="roles_user" id="roles_user">
                                        </select>
                                    </div>



                                    <div class="col-md-6">
                                        <label for="email_user" class="form-label">Email</label>
                                        <input type="text" style="height: 55px;border-radius: 10px;" name="email_user"
                                            id="email_user" value="{{ $admin->email_user }}" 
                                            class="form-control  @error('email_user') is-invalid @enderror" />

                                            @error('email_user')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                        
                                    </div>


                                </div>
                                <br>
                                <br>
                                <div>
                                    <a href="{{ url('Admin/administrateur/') }}"class="mr-2 btn btn-danger">Annuler</a>
                                </div>

                                      <div>
                                        <button type="submit" class="mr-2 btn btn-primary ">Mettre à Jour</button>
                                    </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section> --}}


    <div class="px-4 container-fluid">  

        <div class="card">
            <div class="card-header">
                <h4 class="">Modifier un Administrateur
                    <a href="{{ url('Admin/administrateur/') }}"class="btn btn-danger float-end">Retour</a>
    
                </h4>
            </div>
            <div class="card-body">
    
                @if($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error )
                        <div>{{$error}}</div>
                    @endforeach
                </div>    
                @endif   
                
                
    
                <form action="{{url('Admin/administrateur/update-admin/'.$admin->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                       
                    @method('PUT')
    
                    <div class="mb-3">
                        <label for="nom_user">Nom </label>
                        <input type="text" name="nom_user"  value="{{$admin->nom_user}}" class="form-control">
                    </div>


                    <div class="mb-3">
                        <label for="prenoms_user">Prénoms </label>
                        <input type="text" name="prenoms_user"  value="{{$admin->prenoms_user}}" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="adresse_user">Adresse </label>
                        <input type="text" name="adresse_user"  value="{{$admin->adresse_user}}" class="form-control">
                    </div>
                    

                    <div class="mb-3">
                        <label for="telephone_user">Téléphone </label>
                        <input type="text" name="telephone_user"  value="{{$admin->telephone_user}}" class="form-control">
                    </div>


                    <div class="mb-3">
                        <label for="ville_user">Ville </label>
                        <input type="text" name="ville_user"  value="{{$admin->ville_user}}" class="form-control">
                    </div>


                    <div class="mb-3">
                        <label for="pays_user">Pays </label>
                        <input type="text" name="pays_user"  value="{{$admin->pays_user}}" class="form-control">
                    </div>

                    
                    <div class="mb-3">
                        <label for="email_user">Email </label>
                        <input type="email" name="email_user"  value="{{$admin->email_user}}" class="form-control">
                    </div>
                    
                    <div class="mb-3">  
                        <label for="image_user">Image</label>
                        <input type="file" name="image_user"  class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="roles_user"> Rôles </label>
                        <select name="roles_user" class="form-control">
                            <option value="Admin" {{$admin->roles_user == 'Admin' ? 'selected':''}} >Admin</option>
                            <option value="Hôtel" {{$admin->roles_user == 'Hôtel' ? 'selected':''}} >Hôtel</option>
                            <option value="Compagnie" {{$admin->roles_user == 'Compagnie' ? 'selected':''}} >Compagnie</option>
                            <option value="Client" {{$admin->roles_user == 'Client' ? 'selected':''}} >Client</option>
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="status_user"> Status </label>
                        <select name="status_user" class="form-control">
                            <option value="1" {{$admin->status_user == '1' ? 'selected':''}} >Active</option>
                            <option value="0" {{$admin->status_user == '0' ? 'selected':''}} >Inactive</option>
                        </select>
                    </div>

                    
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary float-end">Mettre à Jour</button>
                        </div> 
                         <div class="col-md-3">
                            <a href="{{url('Admin/administrateur/')}}"class="btn btn-danger">Annuler</a>
                        </div>
                </div> 
                </form>
            </div>
        </div>
    </div>  


    
    
@endsection
