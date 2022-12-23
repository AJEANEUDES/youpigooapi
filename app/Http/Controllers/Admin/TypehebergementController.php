<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Pays;
use App\Models\Typehebergement;
use Illuminate\Support\Str;
use App\Models\Ville;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TypehebergementController extends Controller
{
    public function getTypeHebergement()
    {

        $typehebergements = Typehebergement::with("villes")->get();
        $typehebergements = Typehebergement::with("pays")->get();

        // $villes = Ville::where('status_ville', true)->orderByDesc('created_at')->get();
        // $pays = Pays::where('status_pays', true)->orderByDesc('created_at')->get();

        // $typehebergements = DB::table('typehebergements')
        //     ->select('typehebergements.*', 'users.*', 'villes.*', 'pays.*')
        //     ->join(
        //         'villes',
        //         'villes.id_ville',
        //         '=',
        //         'typehebergements.ville_id'
        //     )



        //     ->join('pays', 'pays.id_pays', '=', 'typehebergements.pays_id')
        //     ->join('users', 'users.id', '=', 'typehebergements.created_by')
        //     ->orderByDesc('typehebergements.created_at')
        //     ->get();


        // return view('packages.typehebergements.admin.typehebergement', compact([
        //     'typehebergements',  'villes', 'pays'



        // ]));


        return response()->json(
            [
                "status" => 1,
                "message" => "Liste des Types d'Hébergements",
                "data" => $typehebergements
            ],
            200
        );
    }


    public function getSpecTypeHebergement(Request $request): JsonResponse
    {
        $typehbergement = Typehebergement::find(($request->id_typehbergement));
        return response()->json(['data' => $typehbergement]);
    }


    public function getSpecTypeHotel(Request $request)
    {
        $hotel = Hotel::where('hotel_id', ($request->hotel_id))->get();
        return response()->json($hotel);
    }





    public function infoTypeHebergement(Request $request, $id_typehebergement)
    {

        $typehebergement = Typehebergement::with("villes")->get();
        $typehebergement = Typehebergement::with("pays")->get();
        $typehebergement = Typehebergement::where(
            "id_typehebergement",
            $id_typehebergement
        )->exists();

        if ($typehebergement) {

            $info = Typehebergement::find($id_typehebergement);


            // $typehebergement = Typehebergement::where('id_typehebergement', ($request->id_typehebergement))
            //     ->select(
            //         'typehebergements.*',
            //         'villes.*',
            //         'pays.*',
            //         'users.*',
            //     )

            //     ->join(
            //         'villes',
            //         'villes.id_ville',
            //         '=',
            //         'typehebergements.ville_id'
            //     )
            //     ->join(
            //         'pays',
            //         'pays.id_pays',
            //         '=',
            //         'typehebergements.pays_id'
            //     )

            //     ->join('users', 'users.id', '=', 'typehebergements.created_by')
            //     ->orderByDesc('typehebergements.created_at')
            //     ->first();
            // return response()->json($typehebergement);

            return response()->json([
                "status" => true,
                "reload" => false,
                "title" => "INFO SUR  LE TYPE",
                "data" => $info
            ], 200);
        } else {
            return response()->json(
                [
                    "status" => false,
                    "reload" => false,
                    "title" => "INFO SUR  LE TYPE",
                    "message" => "Aucun type n'a été trouvé.",

                ],
                404
            );
        }
    }


    public function createTypeHebergement()
    {
        $pays = Pays::where('status_pays', true)->orderByDesc('created_at')->get();
        $villes = Ville::where('status_ville', true)->orderByDesc('created_at')->get();

        return view('packages.typehebergements.admin.create', compact('pays', 'villes'));
    }



    public function storeTypeHebergement(Request $request)
    {
        $messages = [
            "ville_id.required" => "La ville du type d'hébergement est requise",
            "pays_id.required" => "Le pays du type d'hébergement est requis",

            "nom_typehebergement.required" => "Le nom du type d'hébergement est requis",
            "nom_typehebergement.max" => "Le nom du type d'hébergement est trop long",
            "nom_typehebergement.unique" => "Ce type existe deja dans le système",
            "description_typehebergement.required" => "La description du type est requise",

        ];


        $validator = Validator::make($request->all(), [

            "ville_id" => "bail|required",
            "pays_id" => "bail|required",
            "nom_typehebergement" => "bail|required|max:200|unique:typehebergements,nom_typehebergement",
            "description_typehebergement" => "bail|required",


        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "ENREGISTREMENT DU TYPE D'HEBERGEMENT",
            "message" => $validator->errors()->first()
        ]);


        $typehebergement = new Typehebergement();
        $typehebergement->nom_typehebergement = $request->nom_typehebergement;
        $typehebergement->description_typehebergement = $request->description_typehebergement;
        $typehebergement->slug_typehebergement = Str::slug("type-" . $request->nom_typehebergement);
        $typehebergement->status_typehebergement = true;
        $typehebergement->ville_id = $request->ville_id;
        $typehebergement->pays_id = $request->pays_id;
        $typehebergement->image_typehebergement = $request->image_typehebergement;
        $typehebergement->created_by = Auth::id();

        if ($request->hasfile('image_typehebergement')) {
            $file = $request->file('image_typehebergement');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('storage/uploads/', $filename);
            $typehebergement->image_typehebergement = $filename;
        }

        $typehebergement->save();

        return response()->json([
            "status" => true,
            "reload" => false,
            "title" => "ENREGISTREMENT DU TYPE D'HEBERGEMENT",
            "message" => "Le type " . $typehebergement->nom_typehebergement . "  a été ajoutée avec succes"
        ]);

        return redirect('Admin/types/')->with('message', 'Type ajoutée avec succès');
    }



    public function editTypeHebergement($id_typehebergement)
    {
        $pays = Pays::where('status_pays', true)->orderByDesc('created_at')->get();
        $villes = Ville::where('status_ville', true)->orderByDesc('created_at')->get();
        $type = Typehebergement::find($id_typehebergement);
        return view('packages.typehebergements.admin.edit', compact('villes', 'pays', 'type'));
    }





    public function updateTypeHebergement(Request $request, $id_typehebergement)

    {
        $messages = [
            "ville_id.required" => "La ville du type d'hébergement est requise",
            "pays_id.required" => "Le pays du type d'hébergement est requis",

            "nom_typehebergement.required" => "Le nom du type d'hébergement est requis",
            "description_typehebergement.required" => "La description du type est requise",

            "image_typehebergement.mimes" => "L'image du type d'hébergement que vous avez selectionnez est invalide",
            "image_typehebergement.max" => "La taille de l'image du type d'hébergement est trop lourde",


        ];


        $validator = Validator::make($request->all(), [

            "ville_id" => "bail|required",
            "pays_id" => "bail|required",
            "nom_typehebergement" => "bail|required|max:200",
            "description_typehebergement" => "bail|required",

            "image_typehebergement" => "bail|max:2048000",
            "image_typehebergement.*" => "bail|mimes:jpeg,jpg,png",


        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MISE A JOUR DU TYPE D'HEBERGEMENT",
            "message" => $validator->errors()->first()
        ]);



        $typehebergement = Typehebergement::where("id_typehebergement", $id_typehebergement)
            ->exists();

        if ($typehebergement) {



            $typehebergement = Typehebergement::findOrFail($request->id_typehebergement);

            $typehebergement->nom_typehebergement = $request->nom_typehebergement;
            $typehebergement->slug_typehebergement = Str::slug("type-" . $request->nom_typehebergement);
            $typehebergement->status_typehebergement =  $request->status_typehebergement == true ? '1' : '0';

            $typehebergement->ville_id = $request->ville_id;
            $typehebergement->pays_id = $request->pays_id;

            if ($request->hasfile('image_typehebergement')) {

                $destination = 'storage/uploads/' . $typehebergement->image_typehebergement;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $file = $request->file('image_typehebergement');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/uploads/', $filename);
                $typehebergement->image_typehebergement = $filename;
            }


            $typehebergement->update();


            return response()->json([
                "status" => true,
                "reload" => false,
                "title" => "MISE A JOUR DU TYPE D'HEBERGEMENT",
                "message" => "Le type" . $typehebergement->nom_typehebergement . " a été ajoutée avec succes"
            ]);



            return redirect('Admin/types/')->with('message', ' Type modifiée avec succès');
        } else {

            return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "MISE A JOUR DU TYPE D'HEBERGEMENT",
                "message" => "Erreur de mise à jour"
            ]);

            return redirect('Admin/types/editer-type/', $typehebergement->id_typehebergement)
                ->with('message', 'Erreur de mise à jour');
        }
    }


    public function deleteTypeHebergement(Request $request, $id_typehebergement)
    {

        $typehebergement = Typehebergement::where("id_typehebergement", $id_typehebergement)
            ->exists();

        if ($typehebergement) {



            $typehebergement = Typehebergement::findOrFail($request->id_typehebergement);
            $destination = 'storage/uploads/' . $typehebergement->image_typehebergement;

            if (File::exists($destination)) {
                File::delete($destination);
            }

            $typehebergement->delete();


            return response()->json([
                "status" => true,
                "reload" => true,
                "title" => "SUPPRESSION DU TYPE D'HEBERGEMENT",
                "message" => "Le type d'hébergement " . $typehebergement->nom_typehebergement . " a été bien supprimée dans le système"
            ]);

            return redirect('Admin/types/')->with('message', ' Type supprimée avec succès');
        } else {
            return response()->json([
                "status" => false,
                "reload" => true,
                "title" => "SUPPRESSION DE LA VILLE",
                "message" => "Ville introuvable"
            ]);

            return redirect('Admin/types/delete-type/', $typehebergement->id_typehebergement)
                ->with('message', 'Erreur de suppression du type');
        }
    }
}
