<form action="{{ route('services-chambres.store') }}" method="post" id="services-chambres-store">
    @csrf
    <div class="modal fade" id="addServiceModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">AJOUT D'UN NOUVEAU SERVICE</h4>
                </div>
                <div class="modal-body">
                    <label for="nom_service" class="form-label">Nom du service</label>
                    <input type="text" style="height: 55px;border-radius: 10px;" name="nom_service" id="nom_service"
                        class="form-control form-control-lg" />

                        <label for="description_service" class="form-label">Description du service</label>
                        <input type="text" style="height: 55px;border-radius: 10px;" name="description_service" id="nom_service"
                            class="form-control form-control-lg" />

                            <label for="chambre_id" class="form-label">Chambre pour le service</label>
                            <select style="height: 55px;border-radius: 10px;" class="form-control form-control-lg"
                                name="chambre_id" id="chambre_id">
                                <option selected disabled>-- Selectionnez --</option>
                                @foreach ($chambres as $chambre)
                                    <option value="{{ encodeId($chambre->id_chambre) }}">{{ Str::upper($chambre->nom_chambre) }}
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