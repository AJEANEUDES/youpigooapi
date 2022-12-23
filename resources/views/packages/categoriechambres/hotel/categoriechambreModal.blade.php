<form action="{{ route('categoriechambres.store.gestion') }}" enctype="multipart/form-data" method="post"
    id="categoriechambres-store-gestion">
    @csrf
    <div class="modal fade" id="addCategorieChambreModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">AJOUT D'UNE NOUVELLE CATEGORIE DE CHAMBRE</h4>
                </div>
                <div class="modal-body">
                    <label for="libelle_categoriechambre" class="form-label">Nom de la catégorie de chambre</label>
                    <input type="text" style="height: 55px;border-radius: 10px;" name="libelle_categoriechambre"
                        id="libelle_categoriechambre" class="form-control form-control-lg" />

                    <label for="description_categoriechambre" class="form-label">Description</label>
                    <input type="text" style="height: 55px;border-radius: 10px;" name="description_categoriechambre"
                        id="description_categoriechambre" class="form-control form-control-lg" />

                    <label for="description_categoriechambre" class="form-label">Description</label>
                    <input type="text" style="height: 55px;border-radius: 10px;" name="description_categoriechambre"
                        id="description_categoriechambre" class="form-control form-control-lg" />

                    <label for="prix_estimatif_categoriechambre" class="form-label">Prix Estimatif Catégorie
                        Chambre</label>
                    <input type="text" style="height: 55px;border-radius: 10px;"
                        name="prix_estimatif_categoriechambre" id="prix_estimatif_categoriechambre"
                        class="form-control form-control-lg" />

                    <label for="image_categoriechambre" class="form-label">Image de la categorie de chambre</label>
                    <input type="file" style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                        name="image_categoriechambre" id="image_categoriechambre">
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
