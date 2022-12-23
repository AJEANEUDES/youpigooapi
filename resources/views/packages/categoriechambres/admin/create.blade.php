@extends('themes.master')

@section('title')
    Catégorie de chambre - Ajouter une catégorie de chambre :: Youpigoo
@endsection

@section('content')

    <div class="px-4 container-fluid">

        <div class="card">
            <div class="card-header">
                <h4 class="">Ajouter une Catégoie
                    <a href="{{ url('Admin/categoriechambres/') }}"class="btn btn-danger float-end">Retour</a>

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



                <form action="{{ url('Admin/categoriechambres/ajouter-categoriechambre') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="card-title">
                        <h4>AJOUT D'UN NOUVELLE CATEGORIE DE CHAMBRE</h4>
                    </div>

                    <div class="mb-3">
                        <label for="libelle_categoriechambre" class="form-label">Nom de la catégorie de chambre</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="libelle_categoriechambre"
                            class="form-control @error('libelle_categoriechambre') is-invalid @enderror"
                            name="libelle_categoriechambre" value="{{ old('libelle_categoriechambre') }}" required
                            autocomplete="libelle_categoriechambre" autofocus />

                        @error('libelle_categoriechambre')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                    </div>



                    <div class="mb-3">
                        <label for="description_categoriechambre" class="form-label">Description</label>

                        <textarea name="description_categoriechambre" id='mysummernote' rows="5" required
                            autocomplete="description_categoriechambre" autofocus
                            class="form-control @error('description_categoriechambre') is-invalid @enderror"
                            value="{{ old('description_categoriechambre') }}"></textarea>

                        @error('description_categoriechambre')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>



                    <div class="mb-3">
                        <label for="image_categoriechambre">Image</label>
                        <input type="file" name="image_categoriechambre" class="form-control">
                    </div>


                    <div class="mb-3">

                        <label for="prix_estimatif_categoriechambre" class="form-label">Prix Estimatif Catégorie
                            Chambre</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="prix_estimatif_categoriechambre"
                            class="form-control @error('prix_estimatif_categoriechambre') is-invalid @enderror" name="prix_estimatif_categoriechambre"
                            value="{{ old('prix_estimatif_categoriechambre') }}" required autocomplete="prix_estimatif_categoriechambre" autofocus />

                        @error('prix_estimatif_categoriechambre')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                    </div>



                    <div class="row">


                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary float-end">Enregistrer</button>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ url('Admin/categoriechambres/') }}"class="btn btn-danger">Annuler</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
