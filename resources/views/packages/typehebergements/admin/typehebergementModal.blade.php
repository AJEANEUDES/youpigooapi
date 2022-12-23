

<form action="{{ route('typehebergements.store') }}" method="post" id="typehebergements-store"  enctype="multipart/form-data" >
    @csrf
    <div class="modal fade" id="addTypeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">AJOUT D'UNNOUVEAU TYPE D'HEBERGEMENT</h4> 
                </div>
                <div class="modal-body">
                    <label for="nom_typehebergement" class="form-label">Nom du type d'hébergement</label>
                    <input type="text" style="height: 55px;border-radius: 10px;" name="nom_typehebergement" id="nom_typehebergement"
                        class="form-control form-control-lg" />

                    <label for="description_typehebergement" class="form-label">Description du type d'hébergement</label>
                    <input type="text" style="height: 55px;border-radius: 10px;" name="description_typehebergement"
                        id="description_typehebergement" class="form-control form-control-lg" />

                    <label for="image_typehebergement" class="form-label">Image du type d'hébergement</label>
                    <input type="text" style="height: 55px;border-radius: 10px;" name="image_typehebergement" id="image_typehebergement"
                        class="form-control form-control-lg" />

                    <label for="pays_id" class="form-label">Pays du type d'hébergement</label>
                    <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                        name="pays_id" id="pays_id">
                        <option selected disabled>-- Selectionnez --</option>
                        @foreach ($pays as $payskey)
                            <option value="{{ encodeId($payskey->id_pays) }}">{{ Str::upper($payskey->nom_pays) }}
                            </option>
                        @endforeach
                    </select>

                    <label for="ville_id" class="form-label">Vile du type d'hébergement</label>
                    <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                        name="ville_id" id="ville_id">
                        <option selected disabled>-- Selectionnez --</option>
                        @foreach ($villes as $ville)
                            <option value="{{ encodeId($ville->id_ville) }}">{{ Str::upper($ville->nom_ville) }}
                            </option>
                        @endforeach
                    </select>



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