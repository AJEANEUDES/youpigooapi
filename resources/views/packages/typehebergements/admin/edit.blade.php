@extends('themes.master')
@section('title')
    Type d'hébergements - Editer un type :: Youpigoo
@endsection
@section('content')


    <div class="px-4 container-fluid">  

        <div class="card">
            <div class="card-header">
                <h4 class="">Modifier un type d'hébergement
                    <a href="{{ url('Admin/types/') }}"class="btn btn-danger float-end">Retour</a>
    
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
                
                
    
                <form action="{{url('Admin/types/update-type/'. $type->id_typehebergement)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                       
                    @method('PUT')
                    
                    
                    <div class="mb-3">
                        <label for="pays_id">Pays</label>
                        <select name="pays_id" class="form-control">
                            <option value="pays_id">... Sélectionner le pays ...</option>
                            @foreach ($pays as $pays)
                                <option value="{{ $pays->id_pays }}"
                                    {{ $type->pays_id == $pays->id_pays ? 'selected' : '' }}>{{ $pays->nom_pays }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="ville_id">Ville</label>
                        <select name="ville_id" class="form-control">
                            <option value="ville_id">... Sélectionner la ville ...</option>
                            @foreach ($villes as $ville)
                                <option value="{{ $ville->id_ville }}"
                                    {{ $type->ville_id == $ville->id_ville ? 'selected' : '' }}>{{ $ville->nom_ville }}</option>
                            @endforeach
                        </select>
                    </div>




                    <div class="mb-3">
                        <label for="nom_typehebergement">Nom du type d'hébergement </label>
                        <input type="text" name="nom_typehebergement"  value="{{ $type->nom_typehebergement}}" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="description_typehebergement" class="form-label">Description du type d'hébergement</label>

                        <textarea name="description_typehebergement" id='mysummernote' rows="5" class="form-control">
                            {{ $type->description_typehebergement }}
                        </textarea>

                    </div>



                    <div class="mb-3">  
                        <label for="image_typehebergement">Image</label>
                        <input type="file" name="image_typehebergement"  class="form-control">
                    </div>

                

                    <div class="mb-3">
                        <label for="status_typehebergement"> Status </label>
                        <select name="status_typehebergement" class="form-control">
                            <option value="1" {{$type->status_typehebergement == '1' ? 'selected':''}} >Active</option>
                            <option value="0" {{$type->status_typehebergement == '0' ? 'selected':''}} >Inactive</option>
                        </select>
                    </div>

                    
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary float-end">Mettre à Jour</button>
                        </div> 
                         <div class="col-md-3">
                            <a href="{{url('Admin/types/')}}"class="btn btn-danger">Annuler</a>
                        </div>
                </div> 
                </form>
            </div>
        </div>
    </div>  


    
    
@endsection
