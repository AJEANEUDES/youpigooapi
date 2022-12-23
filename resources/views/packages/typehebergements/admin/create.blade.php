@extends('themes.master')

@section('title')
    Type d'hébergements - Ajouter un type :: Youpigoo
@endsection

@section('content')

    <div class="px-4 container-fluid">

        <div class="card">
            <div class="card-header">
                <h4 class="">Ajouter un type
                    <a href="{{ url('Admin/types/') }}"class="btn btn-danger float-end">Retour</a>

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



                <form action="{{ url('Admin/types/ajouter-type') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card-title">
                        <h4>AJOUT D'UN NOUVEAU TYPE</h4>
                    </div>

                    <div class="mb-3">
                        <label for="nom_typehebergement" class="form-label">Nom du type d'hébergement</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="nom_typehebergement"
                            class="form-control @error('nom_typehebergement') is-invalid @enderror" name="nom_typehebergement"
                            value="{{ old('nom_typehebergement') }}" required autocomplete="nom_typehebergement" autofocus />

                        @error('nom_typehebergement')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                    </div>


                    <div class="mb-3">
                        <label for="description_typehebergement" class="form-label">Description du type d'hébergement</label>

                        <textarea name="description_typehebergement" id='mysummernote' rows="5" required autocomplete="description_typehebergement" autofocus
                            class="form-control @error('description_typehebergement') is-invalid @enderror" value="{{ old('description_typehebergement') }}"></textarea>

                        @error('description_typehebergement')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>


                    <div class="mb-3">
                        <label for="pays_id" class="form-label">Pays du type d'hébergement</label>
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

                        <label for="ville_id" class="form-label">Vile du type d'hébergement</label>
                        <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                            name="ville_id" id="ville_id">
                            <option selected disabled>-- Selectionnez la ville--</option>
                            @foreach ($villes as $ville)
                                <option value="{{ ($ville->id_ville) }}">{{ ($ville->nom_ville) }}
                                </option>
                            @endforeach
                        </select>
                    </div>



                    <div class="mb-3">
                        <label for="image_typehebergement">Image</label>
                        <input type="file" name="image_typehebergement" class="form-control">
                    </div>


                    <div class="row">


                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary float-end">Enregistrer</button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ url('Admin/types/') }}"class="btn btn-danger">Annuler</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
