@extends('themes.master')

@section('title')
    Pays - Ajouter un pays :: Youpigoo
@endsection

@section('content')

    <div class="px-4 container-fluid">

        <div class="card">
            <div class="card-header">
                <h4 class="">Ajouter un pays
                    <a href="{{ url('Admin/pays/') }}"class="btn btn-danger float-end">Retour</a>

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



                <form action="{{ url('Admin/pays/ajouter-pays') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card-title">
                        <h4>AJOUT D'UN NOUVEAU PAYS</h4>
                    </div>

                    <div class="mb-3">
                        <label for="nom_pays" class="form-label">Nom  du Pays</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="nom_pays"
                            class="form-control @error('nom_pays') is-invalid @enderror" name="nom_pays"
                            value="{{ old('nom_pays') }}" required autocomplete="nom_pays" autofocus />

                        @error('nom_pays')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                    </div>


                    <div class="mb-3">
                        <label for="description_pays" class="form-label">Description du pays</label>

                        <textarea name="description_pays" id='mysummernote' rows="5" required
                            autocomplete="description_pays" autofocus
                            class="form-control @error('description_pays') is-invalid @enderror"
                            value="{{ old('description_pays') }}"></textarea>

                        @error('description_pays')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>


                    <div class="mb-3">
                        <label for="image_pays">Image</label>
                        <input type="file" name="image_pays" class="form-control">
                    </div>


                    <div class="row">
                        

                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary float-end">Enregistrer</button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ url('Admin/pays/') }}"class="btn btn-danger">Annuler</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
