@extends('themes.master')

@section('title')
    Chambre - Ajouter une chambre :: Youpigoo
@endsection

@section('content')

    <div class="px-4 container-fluid">

        <div class="card">
            <div class="card-header">
                <h4 class="">Ajouter une chambre
                    <a href="{{ url('Admin/chambres/') }}"class="btn btn-danger float-end">Retour</a>

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



                <form action="{{ url('Admin/chambres/ajouter-chambres') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card-title">
                        <h4>AJOUT D'UN NOUVELLE CHAMBRE</h4>
                    </div>


                    <div class="mb-3">
                        <label for="nom_chambre" class="form-label">Nom de la chambre</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="nom_chambre"
                            class="form-control @error('nom_chambre') is-invalid @enderror" name="nom_chambre"
                            value="{{ old('nom_chambre') }}" required autocomplete="nom_chambre" autofocus />

                        @error('nom_chambre')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                    </div>

                 

                    <div class="mb-3">
                        <label for="description_chambre" class="form-label">Description de la chambre</label>

                        <textarea name="description_chambre" id='mysummernote' rows="5" required autocomplete="description_chambre" autofocus
                            class="form-control @error('description_chambre') is-invalid @enderror" value="{{ old('description_chambre') }}"></textarea>

                        @error('description_chambre')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>


                    
                    <div class="mb-3">
                        <label for="classe_chambre" class="form-label">classe de la chambre</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="classe_chambre"
                            class="form-control @error('classe_chambre') is-invalid @enderror" name="classe_chambre"
                            value="{{ old('classe_chambre') }}" required autocomplete="classe_chambre" autofocus />

                        @error('classe_chambre')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                    </div>


                    <div class="mb-3">
                        <label for="nombre_lits_chambre" class="form-label">Nombre de lits dans la chambre</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="nombre_lits_chambre"
                            class="form-control @error('nombre_lits_chambre') is-invalid @enderror" name="nombre_lits_chambre"
                            value="{{ old('nombre_lits_chambre') }}" required autocomplete="nombre_lits_chambre" autofocus />

                        @error('nombre_lits_chambre')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                    </div>


                    <div class="mb-3">
                        <label for="nombre_places_chambre" class="form-label">Nombre de places dans la chambre</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="nombre_places_chambre"
                            class="form-control @error('nombre_places_chambre') is-invalid @enderror" name="nombre_places_chambre"
                            value="{{ old('nombre_places_chambre') }}" required autocomplete="nombre_places_chambre" autofocus />

                        @error('nombre_places_chambre')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                    </div>



                    <div class="mb-3">

                        <label for="prix_standard_chambre" class="form-label">Prix de la chambre
                            d'hotel</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="prix_standard_chambre"
                            class="form-control @error('prix_standard_chambre') is-invalid @enderror"
                            name="prix_standard_chambre" value="{{ old('prix_standard_chambre') }}"
                            required autocomplete="prix_standard_chambre" autofocus />

                        @error('prix_standard_chambre')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>






                    <div class="mb-3">
                        <label for="image_chambre">Image</label>
                        <input type="file" name="image_chambre" class="form-control">
                    </div>


                    <div class="mb-3">

                        <label for="categoriechambre_id" class="form-label">Catégorie de chambre</label>
                        <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                            name="categoriechambre_id" id="categoriechambre_id">
                            <option selected disabled>-- Selectionnez --</option>
                            @foreach ($categoriechambres as $catchambre)
                                <option value="{{ $catchambre->id_categoriechambre }}">
                                    {{ $catchambre->libelle_categoriechambre }}
                                </option>
                            @endforeach
                        </select>
                    </div>



                    <div class="mb-3">

                        <label for="pays_id" class="form-label">Pays de la chambre</label>
                        <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                            name="pays_id" id="pays_id">
                            <option selected disabled>-- Selectionnez --</option>
                            @foreach ($pays as $pays)
                                <option value="{{ $pays->id_pays }}">{{ $pays->nom_pays }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">

                        <label for="ville_id" class="form-label">Ville de la chambre</label>
                        <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                            name="ville_id" id="ville_id">
                            <option selected disabled>-- Selectionnez --</option>
                            @foreach ($villes as $ville)
                                <option value="{{ $ville->id_ville }}">{{ $ville->nom_ville }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="mb-3">

                        <label for="typehebergement_id" class="form-label">Type</label>
                        <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                            name="typehebergement_id" id="typehebergement_id">
                            <option selected disabled>-- Selectionnez --</option>
                            @foreach ($typehebergements as $typehebergement)
                                <option value="{{ $typehebergement->id_typehebergement }}">
                                    {{ $typehebergement->nom_typehebergement }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">

                        <label for="hotel_id" class="form-label">Hotel d'appartenance</label>
                        <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                            name="hotel_id" id="hotel_id">
                            <option selected disabled>-- Selectionnez --</option>
                            @foreach ($hotels as $hotel)
                                <option value="{{ $hotel->id_hotel }}">
                                    {{ $hotel->nom_hotel }}
                                </option>
                            @endforeach
                        </select>
                    </div>




                    {{-- <div class="mb-3">

                        <label for="user_id" class="form-label">Gestionnaire de la chambre</label>
                        <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                            name="user_id" id="user_id">
                            <option selected disabled>-- Selectionnez --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->nom_user }} -- {{ $hotel->prenoms_user }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}



                    <div class="row">


                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary float-end">Enregistrer</button>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ url('Admin/hotels/') }}"class="btn btn-danger">Annuler</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
