@extends('themes.master')
@section('title')
    Administrateurs - Editer un Administrateur :: Youpigoo
@endsection
@section('content')


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
