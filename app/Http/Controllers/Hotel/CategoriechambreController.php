<?php

namespace App\Http\Controllers\Hotel;


use App\Http\Controllers\Controller;
use App\Models\Categoriechambre;
use App\Models\Gestionnairesociete;
use App\Models\Hotel;
use Illuminate\Support\Str;
use App\Models\Societe;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class CategoriechambreController extends Controller
{

    protected $categoriechambre;

    public function guard()
    {
        return Auth::guard();
    }


    public function __construct()
    {
        $this->middleware('auth:api');
        $this->categoriechambre = $this->guard()->user();
    }

    public function roleUser()
    {
        return Auth::user()->roles_user == "Hotel";
    }


    public function getCategorieChambre()
    {

        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Hotel") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas l'accès à un compte hôtel",
            ]);
        }
         else {

        $categoriechambres = Categoriechambre::select('categoriechambres.*')
            ->orderByDesc('categoriechambres.created_at')
            ->get();

            
        return view('packages.categoriechambres.admin.categoriechambre', compact(['categoriechambres']));

        return response()->json([
            "status" => true,
            "reload" => false,
            "message" => "LISTE DES CATEGORIES DE CHAMBRE",
            "data" => $categoriechambres
        ]);
    }

    }


    public function infoCategorieChambre(Request $request, $id_categoriechambre)
    {

        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Hotel") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas l'accès à un compte hôtel",
            ]);
        }
         else {


        $categoriechambre = Categoriechambre::where(
            "id_categoriechambre",
            $id_categoriechambre
        )->exists();

        if ($categoriechambre) {

            $info = Categoriechambre::find($id_categoriechambre);

            $categoriechambre = Categoriechambre::where('id_categoriechambre', ($request->id_categoriechambre))
                ->select('categoriechambres.*',  'users.*')
                ->join('users', 'users.id', '=', 'categoriechambres.created_by')
                ->orderByDesc('categoriechambres.created_at')
                ->first();


            return response()->json([
                "status" => true,
                "reload" => false,
                "title" => "INFO SUR LA CATEGORIE DE CHAMBRE",
                "info sur la categorie de la chambre" => $info

            ], 200);
        } else {
            return response()->json(
                [
                    "status" => false,
                    "reload" => false,
                    "title" => "INFO SUR LA CATEGORIE DE CHAMBRE",
                    "message" => "Aucune catégorie de chambre n'a été trouvée.",


                ],
                404
            );
        }
    }

    }

    public function storeCategoriechambre(Request $request)
    {
        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Hotel") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas l'accès à un compte hôtel",
            ]);
        }
         else {

        $messages = [
            "libelle_categoriechambre.required" => "Le libelle de la catégorie de la chambre est requis",
            "libelle_categoriechambre.max" => "Le libelle de la catégorie de la chambre est trop long",
            "libelle_categoriechambre.unique" => "Le libelle existe deja dans le système",


            "description_categoriechambre.required" => "La description de la catégorie de chambre est requise",
            "description_categoriechambre.max" => "La description de la catégorie de chambre est trop longue",

            "prix_estimatif_categoriechambre.required" => "Le prix de la catégorie de chambre est requis",

            "image_categoriechambre.mimes" => "L'image de la catégorie de chambre que vous avez selectionnez est invalide",
            "image_categoriechambre.max" => "La taille de l'image de la catégorie de chambre est trop lourde",

        ];


        $validator = Validator::make($request->all(), [
            "libelle_categoriechambre" => "bail|required|max:100
            |unique:categoriechambres,libelle_categoriechambre",

            "description_categoriechambre" => "bail|required",
            "prix_estimatif_categoriechambre" => "bail|required",

            "image_categoriechambre" => "bail|max:2048000",
            "image_categoriechambre.*" => "bail|mimes:jpeg,jpg,png",


        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "ENREGISTREMENT DE LA CATEGORIE DE CHAMBRE",
            "message" => $validator->errors()->first()
        ]);


        $categoriechambre = new categoriechambre();
        $categoriechambre->libelle_categoriechambre = $request->libelle_categoriechambre;
        $categoriechambre->description_categoriechambre = $request->description_categoriechambre;
        $categoriechambre->slug_categoriechambre = Str::slug("categoriechambre-" . $request->libelle_categoriechambre);
        $categoriechambre->prix_estimatif_categoriechambre = $request->prix_estimatif_categoriechambre;
        $categoriechambre->status_categoriechambre =  true ;

        if ($request->hasfile('image_categoriechambre')) {
            $file = $request->file('image_categoriechambre');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('storage/uploads/', $filename);
            $categoriechambre->image_categoriechambre = $filename;
        }

        $categoriechambre->created_by = Auth::id();

        $categoriechambre->save();


        return response()->json([
            "status" => true,
            "reload" => false,
            "title" => "ENREGISTREMENT DE LA CATEGORIE DE CHAMBRE",
            "message" => "la catégorie de chambre  " . $categoriechambre->libelle_categoriechambre . " a été ajoutée avec succes"
        ]);

    }
    }


    public function updateCategorieChambre(Request $request, $id_categoriechambre)

    {

        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Hotel") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas l'accès à un compte hôtel",
            ]);
        }
         else {


        $messages = [

            "libelle_categoriechambre.required" => "Le libelle de la catégorie de la chambre est requis",
            "description_categoriechambre.required" => "La description de la catégorie de chambre est requise",
            "prix_estimatif_categoriechambre.required" => "Le prix de la catégorie de chambre est requis",

            "image_categoriechambre.mimes" => "L'image de la catégorie de chambre que vous avez selectionnez est invalide",
            "image_categoriechambre.max" => "La taille de l'image de la catégorie de chambre est trop lourde",


        ];


        $validator = Validator::make($request->all(), [
            "libelle_categoriechambre" => "bail|required|max:500",
            "description_categoriechambre" => "bail|required",
            "prix_estimatif_categoriechambre" => "bail|required",


            "image_categoriechambre" => "bail|max:2048000",
            "image_categoriechambre.*" => "bail|mimes:jpeg,jpg,png",


        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MISE A JOUR DE LA CATEGORIE DE CHAMBRE",
            "message" => $validator->errors()->first()
        ]);

        $categoriechambre = Categoriechambre::where("id_categoriechambre", $id_categoriechambre)
            ->exists();

        if ($categoriechambre) {

            $categoriechambre = Categoriechambre::findOrFail($id_categoriechambre);
            $categoriechambre->libelle_categoriechambre = $request->libelle_categoriechambre;
            $categoriechambre->description_categoriechambre = $request->description_categoriechambre;
            $categoriechambre->slug_categoriechambre = Str::slug("categoriechambre-" . $request->libelle_categoriechambre);
            $categoriechambre->prix_estimatif_categoriechambre = $request->prix_estimatif_categoriechambre;
            $categoriechambre->status_categoriechambre =  $request->status_categoriechambre == true ? '1' : '0';

            if ($request->hasfile('image_categoriechambre')) {

                $destination = 'storage/uploads/' . $categoriechambre->image_categoriechambre;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $file = $request->file('image_categoriechambre');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/uploads/', $filename);
                $categoriechambre->image_categoriechambre = $filename;
            }

            $categoriechambre->update();



            return response()->json([
                "status" => true,
                "reload" => false,
                "title" => "MISE A JOUR DE LA CATEGORIE DE CHAMBRE",
                "message" => "la catégorie de chambre " . $categoriechambre->libelle_categoriechambre . " a été modifiée avec succes"
            ]);
        } else {

            return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "MISE A JOUR DE LA CATEGORIE DE CHAMBRE",
                "message" => "Erreur de mise à jour"
            ]);

          
        }
    }

    }


    public function deleteCategorieChambre($id_categoriechambre)
    {
        
        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Hotel") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas l'accès à un compte hôtel",
            ]);
        }
         else {


        $categoriechambre = Categoriechambre::where("id_categoriechambre", $id_categoriechambre)
            ->exists();

        if ($categoriechambre) {
            $categoriechambre = Categoriechambre::findOrFail($id_categoriechambre);

            $destination = 'storage/uploads/' . $categoriechambre->image_categoriechambre;

            if (File::exists($destination)) {
                File::delete($destination);
            }

            $categoriechambre->delete();

            return response()->json([
                "status" => true,
                "reload" => true,
                "title" => "SUPPRESSION DE LA CATEGORIE DE CHAMBRE",
                "message" => "la catégorie de chambre " . $categoriechambre->libelle_categoriechambre . " a été bien supprimée dans le système"
            ]);

        } else {
            return response()->json([
                "status" => false,
                "reload" => true,
                "title" => "SUPPRESSION DE LA CATEGORIE DE CHAMBRE",
                "message" => "Catégorie de chambre introuvable"
            ]);

           
        }
    }

    }
}
