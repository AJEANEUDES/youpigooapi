@extends('themes.master')

@section('title')
    Hôtel - Ajouter un Hôtel :: Youpigoo
@endsection

@section('content')

    <div class="px-4 container-fluid">

        <div class="card">
            <div class="card-header">
                <h4 class="">Ajouter un Hôtel
                    <a href="{{ url('Admin/hotels/') }}"class="btn btn-danger float-end">Retour</a>

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



                <form action="{{ url('Admin/hotels/ajouter-hotel') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card-title">
                        <h4>AJOUT D'UN NOUVEL HOTEL</h4>
                    </div>


                    <div class="mb-3">
                        <label for="nom_hotel" class="form-label">Nom de l'Hôtel</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="nom_hotel"
                            class="form-control @error('nom_hotel') is-invalid @enderror" name="nom_hotel"
                            value="{{ old('nom_hotel') }}" required autocomplete="nom_hotel" autofocus />

                        @error('nom_hotel')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                    </div>

                    <div class="mb-3">
                        <select name="etoile" id="etoile" class="form-control">
                            <option selected disabled>Etoiles</option>
                            <option value="1">1 etoile</option>
                            <option value="2">2 etoiles</option>
                            <option value="3">3 etoiles</option>
                            <option value="4">4 etoiles</option>
                            <option value="5">5 etoiles</option>
                        </select>
                        @if ($errors->has('etoile'))
                            <div class="invalid-feedback">
                                {{ $errors->first('etoile') }}
                            </div>
                        @endif
                    </div>



                    <div class="mb-3">
                        <label for="description_hotel" class="form-label">Description de l'hôtel</label>

                        <textarea name="description_hotel" id='mysummernote' rows="5" required autocomplete="description_hotel" autofocus
                            class="form-control @error('description_hotel') is-invalid @enderror" value="{{ old('description_hotel') }}"></textarea>

                        @error('description_hotel')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label for="pays_id" class="form-label">Pays de l'hôtel</label>
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

                        <label for="ville_id" class="form-label">Ville de l'hôtel</label>
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

                        <label for="user_id" class="form-label">Gestionnaire de l'hôtel</label>
                        <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                            name="user_id" id="user_id">
                            <option selected disabled>-- Selectionnez --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->nom_user }} -- {{ $hotel->prenoms_user }}
                                </option>
                            @endforeach
                        </select>
                    </div>







                    <div class="mb-3">

                        <label for="adresse_hotel" class="form-label">Adresse de l'hotel</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="adresse_hotel"
                            class="form-control @error('adresse_hotel') is-invalid @enderror" name="adresse_hotel"
                            value="{{ old('adresse_hotel') }}" required autocomplete="adresse_hotel" autofocus />

                        @error('adresse_hotel')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>


                    <div class="mb-3">

                        <label for="telephone1_hotel" class="form-label">Téléphone 1 de l'hotel</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="telephone1_hotel"
                            class="form-control @error('telephone1_hotel') is-invalid @enderror" name="telephone1_hotel"
                            value="{{ old('telephone1_hotel') }}" required autocomplete="telephone1_hotel" autofocus />

                        @error('telephone1_hotel')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                    </div>


                    <div class="mb-3">

                        <label for="telephone2_hotel" class="form-label">Téléphone 2 de l'hotel</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="telephone2_hotel"
                            class="form-control @error('telephone2_hotel') is-invalid @enderror" name="telephone2_hotel"
                            value="{{ old('telephone2_hotel') }}" required autocomplete="telephone2_hotel" autofocus />

                        @error('telephone2_hotel')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror


                    </div>


                    <div class="mb-3">
                        <label for="email_hotel" class="form-label"> Email de l'hotel</label>

                        <input id="email_hotel" type="email"
                            class="form-control @error('email_hotel') is-invalid @enderror" name="email_hotel"
                            value="{{ old('email_hotel') }}" required autocomplete="email_hotel">

                        @error('email_hotel')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

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

                        <label for="prix_estimatif_chambre_hotel" class="form-label">Prix estimatif de la chambre
                            d'hotel</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" id="prix_estimatif_chambre_hotel"
                            class="form-control @error('prix_estimatif_chambre_hotel') is-invalid @enderror"
                            name="prix_estimatif_chambre_hotel" value="{{ old('prix_estimatif_chambre_hotel') }}"
                            required autocomplete="prix_estimatif_chambre_hotel" autofocus />

                        @error('prix_estimatif_chambre_hotel')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>






                    <div class="mb-3">
                        <label for="image_hotel">Image</label>
                        <input type="file" name="image_hotel" class="form-control">
                    </div>




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
