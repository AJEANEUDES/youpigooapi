@extends('themes.master')
@section('title')
    Hotels - Editer un Hotel :: Youpigoo
@endsection
@section('content')


    <div class="px-4 container-fluid">  

        <div class="card">
            <div class="card-header">
                <h4 class="">Modifier un hotel
                    <a href="{{ url('Admin/hotels/') }}"class="btn btn-danger float-end">Retour</a>
    
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
                
                
    
                <form action="{{url('Admin/hotels/update-hotel/'. $hotel->id_hotel)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                       
                    @method('PUT')
                    
                    
                    <div class="mb-3">
                        <label for="pays_id">Pays</label>
                        <select name="pays_id" class="form-control">
                            <option value="pays_id">... Sélectionner le pays ...</option>
                            @foreach ($pays as $pays)
                                <option value="{{ $pays->id_pays }}"
                                    {{ $hotel->pays_id == $pays->id_pays ? 'selected' : '' }}>{{ $pays->nom_pays }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="ville_id">Ville</label>
                        <select name="ville_id" class="form-control">
                            <option value="ville_id">... Sélectionner la ville ...</option>
                            @foreach ($villes as $ville)
                                <option value="{{ $ville->id_ville }}"
                                    {{ $hotel->ville_id == $ville->id_ville ? 'selected' : '' }}>{{ $ville->nom_ville}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="typehebergement_id">Type</label>
                        <select name="typehebergement_id" class="form-control">
                            <option value="typehebergement_id">... Sélectionner le pays ...</option>
                            @foreach ($typehebergements as $typehebergement)
                                <option value="{{ $typehebergement->id_typehebergement}}"
                                    {{ $hotel->typehebergement_id == $typehebergement->id_typehebergement ? 'selected' : '' }}>{{ $typehebergement->nom_typehebergement }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="user_id">Gestionnaire de l'hôtel</label>
                        <select name="user_id" class="form-control">
                            <option value="user_id">... Sélectionner le pays ...</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id}}"
                                    {{ $hotel->user_id == $user->id ? 'selected' : '' }}>{{ $user->nom_user }}   {{ $user->prenoms_user }} </option>
                            @endforeach
                        </select>
                    </div>



                    <div class="mb-3">
                        <label for="nom_hotel">Nom de l'Hôtel </label>
                        <input type="text" name="nom_hotel"  value="{{ $hotel->nom_hotel}}" class="form-control">
                    </div>


                    <div class="mb-3">
                        <label for="etoile"> Status </label>
                        <select name="etoile" class="form-control">
                            <option value="1" {{$hotel->etoile == '1' ? 'selected':''}} >1 etoile</option>
                            <option value="2" {{$hotel->etoile == '2' ? 'selected':''}} >2 etoiles</option>
                            <option value="3" {{$hotel->etoile == '3' ? 'selected':''}} >3 etoiles</option>
                            <option value="4" {{$hotel->etoile == '4' ? 'selected':''}} >4 etoiles</option>
                            <option value="5" {{$hotel->etoile == '5' ? 'selected':''}} >5 etoiles</option>
                        </select>
                    </div>



                    <div class="mb-3">
                        <label for="adresse_hotel">Description de l'hotel </label>

                        <textarea name="description_hotel" id='mysummernote' rows="5" class="form-control">
                            {{ $hotel->description_hotel }}
                        </textarea>

                    </div>


                    <div class="mb-3">
                        <label for="adresse_hotel">Adresse de l'hotel </label>
                        <input type="text" name="adresse_hotel"  value="{{$hotel->adresse_hotel}}" class="form-control">
                    </div>
                    

                    <div class="mb-3">
                        <label for="telephone1_hotel">Téléphone 1 de l'hotel </label>
                        <input type="text" name="telephone1_hotel"  value="{{$hotel->telephone1_hotel}}" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="telephone2_hotel">Téléphone 2 de l'hotel </label>
                        <input type="text" name="telephone2_hotel"  value="{{$hotel->telephone2_hotel}}" class="form-control">
                    </div>


                      
                    <div class="mb-3">
                        <label for="email_hotel">Email de l'hotel </label>
                        <input type="email" name="email_hotel"  value="{{$hotel->email_hotel}}" class="form-control">
                    </div>

                    
                    <div class="mb-3">
                        <label for="numero_rccm_hotel">Numero rccm de l'hotel</label>
                        <input type="file" name="numero_rccm_hotel" class="form-control">
                    </div>


                    <div class="mb-3">
                        <label for="numero_cnss_hotel">Numero cnss de l'hotel</label>
                        <input type="file" name="numero_cnss_hotel" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="numero_if_hotel">Numero if de l'hotel</label>
                        <input type="file" name="numero_if_hotel" class="form-control">
                    </div>

                    
                    <div class="mb-3">
                        <label for="prix_estimatif_chambre_hotel">Prix estimatif de la chambre
                            d'hotel </label>
                        <input type="text" name="prix_estimatif_chambre_hotel"  
                        value="{{$hotel->prix_estimatif_chambre_hotel}}" class="form-control">
                    </div>



                    <div class="mb-3">  
                        <label for="image_hotel">Image</label>
                        <input type="file" name="image_hotel"  class="form-control">
                    </div>

                

                    <div class="mb-3">
                        <label for="status_hotel"> Status </label>
                        <select name="status_hotel" class="form-control">
                            <option value="1" {{$hotel->status_hotel == '1' ? 'selected':''}} >Active</option>
                            <option value="0" {{$hotel->status_hotel == '0' ? 'selected':''}} >Inactive</option>
                        </select>
                    </div>

                    
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary float-end">Mettre à Jour</button>
                        </div> 
                         <div class="col-md-3">
                            <a href="{{url('Admin/hotels/')}}"class="btn btn-danger">Annuler</a>
                        </div>
                </div> 
                </form>
            </div>
        </div>
    </div>  


    
    
@endsection
