<form action="{{ route('chambres.store') }}" method="post" enctype="multipart/form-data" id="chambres-store">
    @csrf
    <div class="modal fade" id="addChambreModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">AJOUT D'UNE NOUVELLE CHAMBRE</h4>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label for="nom_chambre" class="form-label">Nom de la chmabre</label>
                        <input type="text" style="height: 55px;border-radius: 10px;"
                            class="form-control form-control-lg" name="nom_chambre"
                            id="nom_chambre">
                    </div>


                    <div class="col-md-6">
                        <label for="categoriechambre_id" class="form-label">Catégorie de la chambre</label>
                        <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                            name="categoriechambre_id" id="categoriechambre_id">
                            <option selected disabled>-- Selectionnez la catégorie--</option>
                            @foreach ($categoriechambres as $categoriechambre)
                            <option value="{{ ($categoriechambre->id_categoriechambre) }}">
                                {{ Str::upper($categoriechambre->nom_categoriechambre) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="ville_id" class="form-label">Ville de la chambre</label>
                        <select style="height: 55px;border-radius: 10px;text-transform: uppercase;"
                            class="form-control form-control-lg" name="ville_id" id="ville_id"></select>
                    </div>

                    <div class="col-md-6">
                        <label for="pays_id" class="form-label">Pays de la chambre</label>
                        <select style="height: 55px;border-radius: 10px;text-transform: uppercase;"
                            class="form-control form-control-lg" name="pays_id" id="pays_id"></select>
                    </div>

                    <div class="col-md-6">
                        <label for="hotel_id" class="form-label">Hotel de la chambre</label>
                        <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                            name="hotel_id" id="hotel_id">
                            <option selected disabled>-- Selectionnez l' Hotel --</option>
                            @foreach ($hotels as $hotel)
                            <option value="{{ ($hotel->id_hotel) }}">{{ Str::upper($hotel->nom_hotel) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="societe_id" class="form-label">Societe de la chambre</label>
                        <select style="height: 55px;border-radius: 10px;text-transform: uppercase;"
                            class="form-control form-control-lg" name="societe_id" id="societe_id">
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="typehebergement_id" class="form-label">Type</label>
                        <select style="height: 55px;border-radius: 10px;text-transform: uppercase;"
                            class="form-control form-control-lg" name="typehebergement_id" id="typehebergement_id">
                        </select>
                    </div>

{{--                     
                    <div class="col-md-4">
                        <label for="annee_chambre" class="form-label">Année</label>
                        <input type="number" min="0" style="height: 55px;border-radius: 10px;"
                            class="form-control form-control-lg" name="annee_chambre" id="annee_chambre">
                    </div>
                    <div class="col-md-4">
                        <label for="kilometrage_chambre" class="form-label">Kilometrage</label>
                        <input type="number" min="0" style="height: 55px;border-radius: 10px;"
                            class="form-control form-control-lg" name="kilometrage_chambre" id="kilometrage_chambre">
                    </div>
                    <div class="col-md-4">
                        <label for="date_mise_circul_chambre" class="form-label">Date de mise en circulation</label>
                        <input type="date" style="height: 55px;border-radius: 10px;"
                            class="form-control form-control-lg" name="date_mise_circul_chambre"
                            id="date_mise_circul_chambre">
                    </div> --}}
                    {{-- <div class="col-md-4">
                        <label for="carburant_chambre" class="form-label">Carburant</label>
                        <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                            name="carburant_chambre" id="carburant_chambre">
                            <option selected disabled>-- Selectionnez --</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Essence">Essence</option>
                            <option value="Hybride">Hybride</option>
                            <option value="Electrique">Electrique</option>
                            <option value="GLP">GLP</option>
                            <option value="GNV">GNV</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="boite_vitesse_chambre" class="form-label">Boite de vitesse</label>
                        <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                            name="boite_vitesse_chambre" id="boite_vitesse_chambre">
                            <option selected disabled>-- Selectionnez --</option>
                            <option value="Manuelle">Manuelle</option>
                            <option value="Automatique">Automatique</option>
                            <option value="Robotisée">Robotisée</option>
                        </select>
                    </div> --}}
                    <div class="col-md-4">
                        <label for="nombre_places_chambre" class="form-label">Nombre de place</label>
                        <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                            name="nombre_places_chambre" id="nombre_places_chambre">
                            <option selected disabled>-- Selectionnez --</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            {{-- <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="9">9</option> --}}
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="nombre_lits_chambre" class="form-label">Nombre de Lits</label>
                        <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                            name="nombre_lits_chambre" id="nombre_lits_chambre">
                            <option selected disabled>-- Selectionnez --</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="9">9</option>
                        </select>
                    </div>



                    {{-- <div class="col-md-4">
                        <label for="interieur_chambre" class="form-label">Interieur de la chambre (couleur)</label>
                        <input type="text" style="height: 55px;border-radius: 10px;"
                            class="form-control form-control-lg" name="interieur_chambre" id="interieur_chambre">
                    </div>
                    <div class="col-md-4">
                        <label for="exterieur_chambre" class="form-label">Exterieur de la chambre (couleur)</label>
                        <input type="text" style="height: 55px;border-radius: 10px;"
                            class="form-control form-control-lg" name="exterieur_chambre" id="exterieur_chambre">
                    </div>
                    <div class="col-md-4">
                        <label for="puissance_chambre" class="form-label">Puissance de la chambre</label>
                        <input type="text" style="height: 55px;border-radius: 10px;"
                            class="form-control form-control-lg" name="puissance_chambre" id="puissance_chambre">
                    </div> --}}
                    <div class="col-md-6">
                        <label for="prix_standard_chambre" class="form-label">Prix de la chambre</label>
                        <input type="number" min="0" style="height: 55px;border-radius: 10px;"
                            class="form-control form-control-lg" name="prix_standard_chambre" id="prix_standard_chambre">
                    </div>
                    <div class="col-md-6">
                        <label for="image_chambre" class="form-label">Image de la chambre</label>
                        <input type="file" style="height: 55px;border-radius: 10px;"
                            class="form-control form-control-lg" name="image_chambre" id="image_chambre">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" style="border-radius: 10px;" class="btn btn-danger btn-md"
                        data-bs-dismiss="modal"> <i class="bx bx-exit"></i> Annuler</button>
                    <button type="submit" style="border-radius: 10px;" class="btn btn-success btn-md"><i
                            class="bx bx-save"></i> Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
</form>