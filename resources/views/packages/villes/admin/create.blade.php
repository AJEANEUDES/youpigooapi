@extends('themes.master')

@section('title')
    villes - Ajouter une ville :: Youpigoo
@endsection

@section('content')

    <div class="px-4 container-fluid">

        <div class="card">
            <div class="card-header">
                <h4 class="">Ajouter une ville
                    <a href="{{ url('Admin/villes/') }}"class="btn btn-danger float-end">Retour</a>

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



                <form action="{{ url('Admin/villes/ajouter-ville') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card-title">
                        <h4>AJOUT D'UN NOUVELLE VILLE</h4>
                    </div>

                    <div class="mb-3">
                        <label for="nom_ville" class="form-label">Nom de la ville</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="nom_ville"
                            class="form-control @error('nom_ville') is-invalid @enderror" name="nom_ville"
                            value="{{ old('nom_ville') }}" required autocomplete="nom_ville" autofocus />

                        @error('nom_ville')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                    </div>


                    <div class="mb-3">
                        <label for="description_ville" class="form-label">Description de la ville</label>

                        <textarea name="description_ville" id='mysummernote' rows="5" required autocomplete="description_ville" autofocus
                            class="form-control @error('description_ville') is-invalid @enderror" value="{{ old('description_ville') }}"></textarea>

                        @error('description_ville')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>


                    <div class="mb-3">
                        <label for="pays_id" class="form-label">Pays de la ville</label>
                        <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                            name="pays_id" id="pays_id">
                            <option selected disabled>-- Selectionnez le pays --</option>
                            @foreach ($pays as $pays)
                                <option value="{{ ($pays->id_pays) }}">{{($pays->nom_pays) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="image_ville">Image</label>
                        <input type="file" name="image_ville" class="form-control">
                    </div>


                    <div class="row">


                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary float-end">Enregistrer</button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ url('Admin/villes/') }}"class="btn btn-danger">Annuler</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
