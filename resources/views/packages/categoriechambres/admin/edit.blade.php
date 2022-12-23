@extends('themes.master')
@section('title')
    Catégorie de chambres - Editer une catégorie de chambre :: Youpigoo
@endsection
@section('content')

    <div class="px-4 container-fluid">

        <div class="card">
            <div class="card-header">
                <h4 class="">Modifier une catégorie de chambre
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



                <form action="{{ url('Admin/categoriechambres/update-categoriechambre/' . $categoriechambre->id_categoriechambre) }}"
                    method="POST" enctype="multipart/form-data">

                    @csrf

                    @method('PUT')

                    <div class="mb-3">
                        <label for="libelle_categoriechambre">Nom de la catégorie de chambre </label>
                        <input type="text" name="libelle_categoriechambre"
                            value="{{ $categoriechambre->libelle_categoriechambre }}" class="form-control">
                    </div>


                    <div class="mb-3">

                        <textarea name="description_categoriechambre" id='mysummernote' rows="5" class="form-control">
                            {{ $categoriechambre->description_categoriechambre }}
                        </textarea>

                    </div>

                    <div class="mb-3">
                        <label for="image_categoriechambre">Image</label>
                        <input type="file" name="image_categoriechambre" class="form-control">
                    </div>




                    <div class="mb-3">
                        <label for="prix_estimatif_categoriechambre">Prix Estimatif Catégorie
                            Chambre
                        </label>
                        <input type="text" name="prix_estimatif_categoriechambre"
                            value="{{ $categoriechambre->prix_estimatif_categoriechambre }}" class="form-control">

                    </div>


                    <div class="mb-3">
                        <label for="status_categoriechambre"> Status </label>
                        <select name="status_categoriechambre" class="form-control">
                            <option value="1" {{ $categoriechambre->status_categoriechambre == '1' ? 'selected' : '' }}>
                                Active</option>
                            <option value="0" {{ $categoriechambre->status_categoriechambre == '0' ? 'selected' : '' }}>
                                Inactive</option>
                        </select>
                    </div>


                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary float-end">Mettre à Jour</button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ url('Admin/categoriechambres/') }}"class="btn btn-danger">Annuler</a>
                    </div>
            </div>
            </form>
        </div>
    </div>
    </div>




@endsection
