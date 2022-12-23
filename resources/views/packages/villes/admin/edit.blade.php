@extends('themes.master')
@section('title')
    villes - Editer une ville :: Youpigoo
@endsection
@section('content')


    <div class="px-4 container-fluid">  

        <div class="card">
            <div class="card-header">
                <h4 class="">Modifier un ville
                    <a href="{{ url('Admin/villes/') }}"class="btn btn-danger float-end">Retour</a>
    
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
                
                
    
                <form action="{{url('Admin/villes/update-ville/'. $ville->id_ville)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                       
                    @method('PUT')
                    
                    
                    <div class="mb-3">
                        <label for="pays_id">Pays</label>
                        <select name="pays_id" class="form-control">
                            <option value="pays_id">... Sélectionner le pays ...</option>
                            @foreach ($pays as $pays)
                                <option value="{{ $pays->id_pays }}"
                                    {{ $ville->pays_id == $pays->id_pays ? 'selected' : '' }}>{{ $pays->nom_pays }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nom_ville">Nom de la ville </label>
                        <input type="text" name="nom_ville"  value="{{ $ville->nom_ville}}" class="form-control">
                    </div>

                    <div class="mb-3">

                        <textarea name="description_ville" id='mysummernote' rows="5" class="form-control">
                            {{ $ville->description_ville }}
                        </textarea>

                    </div>



                    <div class="mb-3">  
                        <label for="image_ville">Image</label>
                        <input type="file" name="image_ville"  class="form-control">
                    </div>

                

                    <div class="mb-3">
                        <label for="status_ville"> Status </label>
                        <select name="status_ville" class="form-control">
                            <option value="1" {{$ville->status_ville == '1' ? 'selected':''}} >Active</option>
                            <option value="0" {{$ville->status_ville == '0' ? 'selected':''}} >Inactive</option>
                        </select>
                    </div>

                    
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary float-end">Mettre à Jour</button>
                        </div> 
                         <div class="col-md-3">
                            <a href="{{url('Admin/villes/')}}"class="btn btn-danger">Annuler</a>
                        </div>
                </div> 
                </form>
            </div>
        </div>
    </div>  


    
    
@endsection
