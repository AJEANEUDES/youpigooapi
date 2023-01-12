<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoriechambre;
use App\Models\Chambre;
use App\Models\Gestionnairesociete;
use App\Models\Hotel;
use App\Models\Pays;
use App\Models\Societe;
use App\Models\Typehebergement;
use App\Models\Ville;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ChambreController extends Controller
{

    protected $chambre;

    public function guard()
    {
        return Auth::guard();
    }


    public function __construct()
    {
        $this->middleware('auth:api');
        $this->chambre = $this->guard()->user();
    }

    public function roleUser()
    {
        return Auth::user()->roles_user == "Admin";
    }




    public function getChambre()
    {


        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Admin") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas un administrateur",
            ]);
        } else {


            $chambres = Chambre::with("categoriechambres")->get();
            $chambres = Chambre::with("hotels")->get();
            $chambres = Chambre::with("typehebergements")->get();
            $chambres = Chambre::with("pays")->get();
            $chambres = Chambre::with("villes")->get();

            $categoriechambres = Categoriechambre::where('status_categoriechambre', true)->get();
            $hotels = Hotel::where('status_hotel', true)->get();
            $typehebergements = Typehebergement::where('status_typehebergement', true)->get();
            $villes = Ville::where('status_ville', true)->get();
            $pays = Pays::where('status_pays', true)->get();

            // $chambres = Chambre::where('status_chambre', true)->orderByDesc('created_at')->get();
            $chambres_reservees = Chambre::where('status_reserver_chambre', true)->orderByDesc('created_at')->get();

            return response()->json(
                [
                    "status" => true,
                    "reload" => true,
                    "title" => "LISTE DES CHAMBRES",
                    "Chambres" =>  $chambres,
                    "message" => "LISTE DES CHAMBRES RESERVEES",
                    "Chambres reservées" => $chambres_reservees,


                ],
                200

            );
        }
    }



    public function getSpecChambre(Request $request): JsonResponse
    {
        $chambre = Chambre::find(($request->id_chambre));
        return response()->json(['data' => $chambre]);
    }


    public function infoChambre(Request $request, $id_chambre)
    {

        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Admin") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas un administrateur",
            ]);
        } else {


            $chambre = Chambre::with("categoriechambres")->get();
            $chambre = Chambre::with("hotels")->get();
            $chambre = Chambre::with("typehebergements")->get();
            $chambre = Chambre::with("pays")->get();
            $chambre = Chambre::with("villes")->get();
            $chambre = chambre::where("id_chambre", $id_chambre)->exists();

            if ($chambre) {

                $info = chambre::find($id_chambre);


                $chambre = chambre::where('id_chambre', ($request->id_chambre))
                    ->select(
                        'chambres.*',
                        'categoriechambres.*',
                        'typehebergements.*',
                        'hotels.*',
                        'villes.*',
                        'pays.*'
                    )

                    ->join('categoriechambres', 'categoriechambres.id_categoriechambre', '=', 'chambres.categoriechambre_id')
                    ->join('typehebergements', 'typehebergements.id_typehebergement', '=', 'chambres.typehebergement_id')
                    ->join('hotels', 'hotels.id_hotel', '=', 'chambres.hotel_id')
                    ->join('villes', 'villes.id_ville', '=', 'chambres.ville_id')
                    ->join('pays', 'pays.id_pays', '=', 'chambres.pays_id')
                    ->first();


                return response()->json(
                    [
                        "status" => true,
                        "reload" => true,
                        "title" => "INFORMATION SUR LA CHAMBRE",
                        "data" =>  $info,


                    ],
                    200

                );
            } else {

                return response()->json(
                    [
                        "status" => false,
                        "reload" => false,
                        "title" => "INFORMATION SUR LA CHAMBRE",
                        "message" =>  "Aucune chambre trouvé",


                    ],
                    200

                );
            }
        }
    }


    public function storeChambre(Request $request)
    {

        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Admin") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas un administrateur",
            ]);
        } else {


            $messages = [

                "categoriechambre_id.required" => "La catégorie de la chambre est requise",
                "typehebergement_id.required" => "Le type d'hebergement de la chambre est requis",
                "hotel_id.required" => "L'hôtel  de la chambre est requise",
                "ville_id.required" => "La ville  de la chambre est requise",
                "pays_id.required" => "Le pays  de la chambre est requis",

                "nom_chambre.required" => "Le nom de la chambre est requis",
                "nom_chambre.max" => "Le nom de la chambre est trop long",
                "nom_chambre.unique" => "Cette chambre existe deja dans le système",

                "description_chambre.required" => "La description de la chambre est requise",
                "description_chambre.max" => "La description de la chambre est trop longue",

                "nombre_lits_chambre.required" => "Le nombre de lits dans la chambre est requis",
                "nombre_places_chambre.required" => "Le nombre de places dans la chambre est requis",
                "prix_standard_chambre.required" => "Le prix de la chambre est requis",
                "classe_chambre.required" => "La classe  de la chambre est requise",

                "image_chambre.mimes" => "L'image de la chambre que vous avez selectionnez est invalide",
                "image_chambre.max" => "La taille de l'image de la chambre est trop lourde",


            ];


            $validator = Validator::make($request->all(), [
                "categoriechambre_id" => "bail|required",
                "hotel_id" => "bail|required",
                "ville_id" => "bail|required",
                "pays_id" => "bail|required",
                "typehebergement_id" => "bail|required",

                "nom_chambre" => "bail|required|max:500|unique:chambres,nom_chambre",
                "description_chambre" => "bail|required",
                "classe_chambre" => "bail|required",
                "nombre_lits_chambre" => "bail|required",
                "nombre_places_chambre" => "bail|required",
                "prix_standard_chambre" => "bail|required",


                "image_chambre" => "bail|max:2048000",
                "image_chambre.*" => "bail|mimes:jpeg,jpg,png",


            ], $messages);

            if ($validator->fails()) return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "ENREGISTREMENT DE LA CHAMBRE",
                "message" => $validator->errors()->first()
            ]);


            $chambre = new Chambre();
            $chambre->nom_chambre = $request->nom_chambre;
            $chambre->slug_chambre = Str::slug("chambre-" . $request->nom_chambre);
            $chambre->description_chambre = $request->description_chambre;
            $chambre->classe_chambre = $request->classe_chambre;
            $chambre->nombre_lits_chambre = $request->nombre_lits_chambre;
            $chambre->nombre_places_chambre = $request->nombre_places_chambre;
            $chambre->prix_standard_chambre = $request->prix_standard_chambre;
            $chambre->image_chambre = $request->image_chambre;
            $chambre->status_chambre =   true;
            $chambre->status_reserver_chambre = false;
            $chambre->categoriechambre_id = ($request->categoriechambre_id);
            $chambre->typehebergement_id = $request->typehebergement_id;
            $chambre->hotel_id = ($request->hotel_id);
            $chambre->ville_id = $request->ville_id;
            $chambre->pays_id = $request->pays_id;


            $chambre->created_by = Auth::id();

            if ($request->hasfile('image_chambre')) {
                $file = $request->file('image_chambre');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/uploads/', $filename);
                $chambre->image_chambre = $filename;
            }


            $chambre->save();


            return response()->json([
                "status" => true,
                "reload" => true,
                "title" => "ENREGISTREMENT DE LA CHAMBRE",
                "message" => "La chambre " . $chambre->nom_chambre . " a été ajoutée avec succes"
            ]);
        }
    }



    public function updateChambre(Request $request, $id_chambre)

    {

        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Admin") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas un administrateur",
            ]);
        } else {

            $messages = [
                "categoriechambre_id.required" => "La catégorie de la chambre est requise",
                "typehebergement_id.required" => "Le type d'hebergement de la chambre est requis",
                "hotel_id.required" => "L'hôtel  de la chambre est requise",
                "ville_id.required" => "La ville  de la chambre est requise",
                "pays_id.required" => "Le pays  de la chambre est requis",

                "nom_chambre.required" => "Le nom de la chambre est requis",
                "description_chambre.required" => "La description de la chambre est requise",
                "description_chambre.max" => "La description de la chambre est trop longue",
                "nombre_lits_chambre.required" => "Le nombre de lits dans la chambre est requis",
                "nombre_places_chambre.required" => "Le nombre de places dans la chambre est requis",
                "prix_standard_chambre.required" => "Le prix de la chambre est requis",
                "classe_chambre.required" => "La classe  de la chambre est requise",

                "image_chambre.required" => "L'image de la chambre est requise",
                "image_chambre.mimes" => "L'image de la chambre que vous avez selectionnez est invalide",
                "image_chambre.max" => "La taille de l'image de la chambre est trop lourde",




            ];


            $validator = Validator::make($request->all(), [
                "categoriechambre_id" => "bail|required",
                "hotel_id" => "bail|required",
                "ville_id" => "bail|required",
                "pays_id" => "bail|required",
                "typehebergement_id" => "bail|required",

                "nom_chambre" => "bail|required|max:500",
                "description_chambre" => "bail|required",
                "classe_chambre" => "bail|required",
                "nombre_lits_chambre" => "bail|required",
                "nombre_places_chambre" => "bail|required",
                "prix_standard_chambre" => "bail|required",


                "image_chambre" => "bail|required",
                "image_chambre" => "bail|max:2048000",
                "image_chambre.*" => "bail|mimes:jpeg,jpg,png",



            ], $messages);

            if ($validator->fails()) return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "MISE A JOUR DE LA CHAMBRE",
                "message" => $validator->errors()->first()
            ]);

            $chambre = Chambre::where("id_chambre", $id_chambre)->exists();

            if ($chambre) {

                $chambre = Chambre::findOrFail($request->id_chambre);
                $chambre->nom_chambre = $request->nom_chambre;
                $chambre->slug_chambre = Str::slug("chambre-" . $request->nom_chambre);
                $chambre->description_chambre = $request->description_chambre;

                $chambre->categoriechambre_id = $request->categoriechambre_id;
                $chambre->typehebergement_id = $request->typehebergement_id;
                $chambre->hotel_id = $request->hotel_id;
                $chambre->ville_id = $request->ville_id;
                $chambre->pays_id = $request->pays_id;

                $chambre->classe_chambre = $request->classe_chambre;
                $chambre->nombre_lits_chambre = $request->nombre_lits_chambre;
                $chambre->nombre_places_chambre = $request->nombre_places_chambre;
                $chambre->prix_standard_chambre = $request->prix_standard_chambre;
                $chambre->status_chambre =  $request->status_chambre == true ? '1' : '0';
                $chambre->status_reserver_chambre =  $request->status_reserver_chambre == true ? '1' : '0';


                if ($request->hasfile('image_chambre')) {

                    $destination = 'storage/uploads/' . $chambre->image_chambre;
                    if (File::exists($destination)) {
                        File::delete($destination);
                    }

                    $file = $request->file('image_chambre');
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move('storage/uploads/', $filename);
                    $chambre->image_chambre = $filename;
                }


                $chambre->update();


                return response()->json([
                    "status" => true,
                    "reload" => false,
                    "title" => "MISE A JOUR DE LA CHAMBRE",
                    "message" => "La chambre  " . $chambre->nom_chambre . " a été modifiée avec succes"
                ]);
            } else {
                return response()->json([
                    "status" => true,
                    "reload" => false,
                    "title" => "MISE A JOUR DE LA CHAMBRE",
                    "message" => "Erreur de mise à jour"
                ]);
            }
        }
    }




    public function deleteChambre(Request $request, $id_chambre)
    {
        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Admin") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas un administrateur",
            ]);
        } else {

            $chambre = Chambre::where("id_chambre", $id_chambre)->exists();

            if ($chambre) {

            $chambre = Chambre::findOrFail($id_chambre);


                $destination = 'storage/uploads/' . $chambre->image_chambre;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $chambre->delete();

                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "title" => "SUPPRESSION DE LA CHAMBRE",
                    "message" => "La chambre  " . $chambre->nom_chambre . " a été bien supprimée dans le système"
                ]);
            } else {

                return response()->json([
                    "status" => false,
                    "reload" => true,
                    "title" => "SUPPRESSION DE LA CHAMBRE",
                    "message" => "Chambre introuvable"
                ]);
            }
        }
    }
}
