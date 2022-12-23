@extends('themes.master')
@section('title')
Gestionnaires Hôtels - Editer un gestionnaire d'hôtel :: Youpigoo
@endsection
@section('content')


    <div class="px-4 container-fluid">  

        <div class="card">
            <div class="card-header">
                <h4 class="">Modifier un gestionnaire d'hôtel
                    <a href="{{ url('Admin/gestionnairehotel/') }}"class="btn btn-danger float-end">Retour</a>
    
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
                
                
    
                <form action="{{url('Admin/gestionnairehotel/update-gestionnairehotel/'.$gestionnaire->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                       
                    @method('PUT')
    
                    <div class="mb-3">
                        <label for="hotel_id">Hôtel</label>
                        <select name="hotel_id" class="form-control">
                            <option value="hotel_id">... Sélectionner l'hôtel ...</option>
                            @foreach ($hotels as $hotel)
                                <option value="{{ $hotel->id_hotel }}"
                                    {{ $gestionnaire->hotel_id == $hotel->id_hotel ? 'selected' : '' }}>{{ $hotel->nom_hotel }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="nom_user">Nom </label>
                        <input type="text" name="nom_user"  value="{{$gestionnaire->nom_user}}" class="form-control">
                    </div>


                    <div class="mb-3">
                        <label for="prenoms_user">Prénoms </label>
                        <input type="text" name="prenoms_user"  value="{{$gestionnaire->prenoms_user}}" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="adresse_user">Adresse </label>
                        <input type="text" name="adresse_user"  value="{{$gestionnaire->adresse_user}}" class="form-control">
                    </div>
                    

                    <div class="mb-3">
                        <label for="telephone_user">Téléphone </label>
                        <input type="text" name="telephone_user"  value="{{$gestionnaire->telephone_user}}" class="form-control">
                    </div>


                    <div class="mb-3">
                        <label for="ville_user">Ville </label>
                        <input type="text" name="ville_user"  value="{{$gestionnaire->ville_user}}" class="form-control">
                    </div>


                    <div class="mb-3">
                        <label for="pays_user">Pays </label>
                        <input type="text" name="pays_user"  value="{{$gestionnaire->pays_user}}" class="form-control">
                    </div>

                    
                    <div class="mb-3">
                        <label for="email_user">Email </label>
                        <input type="email" name="email_user"  value="{{$gestionnaire->email_user}}" class="form-control">
                    </div>
                    
                    <div class="mb-3">  
                        <label for="image_user">Image</label>
                        <input type="file" name="image_user"  class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="roles_user"> Rôles </label>
                        <select name="roles_user" class="form-control">
                            <option value="Admin" {{$gestionnaire->roles_user == 'Admin' ? 'selected':''}} >Admin</option>
                            <option value="Hotel" {{$gestionnaire->roles_user == 'Hotel' ? 'selected':''}} >Hôtel</option>
                            <option value="Compagnie" {{$gestionnaire->roles_user == 'Compagnie' ? 'selected':''}} >Compagnie</option>
                            <option value="Client" {{$gestionnaire->roles_user == 'Client' ? 'selected':''}} >Client</option>
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="status_user"> Status </label>
                        <select name="status_user" class="form-control">
                            <option value="1" {{$gestionnaire->status_user == '1' ? 'selected':''}} >Active</option>
                            <option value="0" {{$gestionnaire->status_user == '0' ? 'selected':''}} >Inactive</option>
                        </select>
                    </div>

                    
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary float-end">Mettre à Jour</button>
                        </div> 
                         <div class="col-md-3">
                            <a href="{{url('Admin/gestionnairehotel/')}}"class="btn btn-danger">Annuler</a>
                        </div>
                </div> 
                </form>
            </div>
        </div>
    </div>  


    
    
@endsection
