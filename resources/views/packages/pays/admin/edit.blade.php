@extends('themes.master')
@section('title')
    Pays - Editer un Pays :: Youpigoo
@endsection
@section('content')


    <div class="px-4 container-fluid">  

        <div class="card">
            <div class="card-header">
                <h4 class="">Modifier un Pays
                    <a href="{{ url('Admin/pays/') }}"class="btn btn-danger float-end">Retour</a>
    
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
                
                
    
                <form action="{{url('Admin/pays/update-pays/'. $pays->id_pays)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                       
                    @method('PUT')
    
                    <div class="mb-3">
                        <label for="nom_pays">Nom du pays </label>
                        <input type="text" name="nom_pays"  value="{{ $pays->nom_pays}}" class="form-control">
                    </div>

                    <div class="mb-3">

                        <textarea name="description_pays" id='mysummernote' rows="5" class="form-control">
                            {{ $pays->description_pays }}
                        </textarea>

                    </div>


                    <div class="mb-3">  
                        <label for="image_pays">Image</label>
                        <input type="file" name="image_pays"  class="form-control">
                    </div>

                

                    <div class="mb-3">
                        <label for="status_pays"> Status </label>
                        <select name="status_pays" class="form-control">
                            <option value="1" {{$pays->status_pays == '1' ? 'selected':''}} >Active</option>
                            <option value="0" {{$pays->status_pays == '0' ? 'selected':''}} >Inactive</option>
                        </select>
                    </div>

                    
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary float-end">Mettre à Jour</button>
                        </div> 
                         <div class="col-md-3">
                            <a href="{{url('Admin/pays/')}}"class="btn btn-danger">Annuler</a>
                        </div>
                </div> 
                </form>
            </div>
        </div>
    </div>  


    
    
@endsection
