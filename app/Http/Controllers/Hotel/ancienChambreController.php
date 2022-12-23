<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Categoriechambre;
use App\Models\Chambre;
use App\Models\Gestionnairesociete;
use App\Models\Hotel;
use App\Models\Pays;
use App\Models\Societe;
use App\Models\Typehebergement;
use App\Models\Ville;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChambreController extends Controller
{

    public function getChambre()
    {
        $hotel_user_id = Auth::id();
        $hotel = Hotel::where('hotel_user_id', $hotel_user_id)->first();

        $hotels = Hotel::where('id_hotel', $hotel->hotel_id)->where('status_hotel', true)->first();
        $categoriechambres = Categoriechambre::where('status_categoriechambre', true)->get();
        $typehebergements = Typehebergement::where('status_typehebergement', true)->get();
        $pays = Pays::where('status_pays', true)->get();
        $villes = Ville::where('status_ville', true)->get();

        $chambres = Chambre::where('hotel_id', $hotels->id_hotel )->where('status_chambre', true)->orderByDesc('created_at')->get();
        $chambres_reservees = Chambre::where('hotel_id', $hotels->id_hotel)
            ->where('status_reserver_chambre', true)
            ->orderByDesc('created_at')->get();

        return view('packages.chambres.hotel.chambre', compact([
            'hotels', 'categoriechambres', 'chambres',
            'typehebergements', 'chambres_reservees',
            'villes', 'pays'

        ]));
    }



    public function getSpecChambre(Request $request): JsonResponse
    {
        $chambre = Chambre::find(($request->id_chambre));
        return response()->json(['data' => $chambre]);
    }




    public function infoChambre(Request $request)
    {
        $chambre = Chambre::where('id_chambre', ($request->id_chambre))
            ->select(
                'chambres.*',
                'categoriechambres.*',
                'typehebergements.*',
                'hotels.*',
                'villes.*',
                'pays.*'
            )

            ->join('categoriechambres', 'categoriechambres.id_categoriechambre', '=',
             'chambres.categoriechambre_id')
            ->join('typehebergements', 'typehebergements.id_typehebergement', '=',
             'chambres.typehebergement_id')
            ->join('hotels', 'hotels.id_hotel', '=', 'chambres.hotel_id')
            ->join('villes', 'villes.id_ville', '=', 'chambres.ville_id')
            ->join('pays', 'pays.id_pays', '=', 'chambres.pays_id')
            ->first();
        return response()->json($chambre);
    }




    public function storeChambre(Request $request)
    {
        $messages = [

            "categoriechambre_id.required" => "La catégorie de la chambre est requise",
            "typehebergement_id.required" => "Le type d'hebergement de la chambre est requis",
            "hotel_id.required" => "L'hôtel  de la chambre est requise",
            "ville_id.required" => "La ville  de la chambre est requise",
            "pays_id.required" => "Le pays  de la chambre est requis",

            "nom_chambre.required" => "Le nom de la chambre est requis",
            "nom_chambre.max" => "Le nom de la chambre  est trop long",
            "nom_chambre.unique" => "Cette chambre existe déjà dans le système",
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

            "nom_chambre" => "bail|required|max:200|unique:chambres,nom_chambre",
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
            "title" => "ENREGISTREMENT DE LA CHAMBRE",
            "message" => $validator->errors()->first()
        ]);

     

        $chambre = new Chambre();
        $chambre->nom_chambre = $request->nom_chambre;
        $chambre->slug_chambre = Str::slug("Chambre-" . $request->nom_chambre);
        $chambre->description_chambre = $request->description_chambre;

        $chambre->categoriechambre_id = ($request->categoriechambre_id);
        $chambre->typehebergement_id = $request->typehebergement_id;
        $chambre->hotel_id = ($request->hotel_id);
        $chambre->ville_id = $request->ville_id;
        $chambre->pays_id = $request->pays_id;

        $chambre->classe_chambre = $request->classe_chambre;
        $chambre->nombre_lits_chambre = $request->nombre_lits_chambre;
        $chambre->nombre_places_chambre = $request->nombre_places_chambre;
        $chambre->prix_standard_chambre = $request->prix_standard_chambre;
        $chambre->image_chambre = $request->image_chambre;
       
        $chambre->status_chambre = true;
        $chambre->created_by = Auth::id();

        if ($request->hasFile('image_chambre')) {
            $image = $request->image_chambre;
            $chambre_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $chambre_new_name);
            $chambre->image_chambre = '/storage/uploads/' . $chambre_new_name;
        }


        $process = $chambre->save();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Enregistrement de la chambre  " . $chambre->nom_chambre . " avec succes dans le système.", Auth::id());
        else saveSysActivityLog(SYS_LOG_ERROR, "Echec d'enregistrement de la chambre " . $chambre->nom_chambre . " avec succes dans le système.", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => false,
            "title" => "ENREGISTREMENT DE LA CHAMBRE",
            "message" => "La Chambre " . $chambre->nom_chambre . " a été ajoutée avec succes"
        ]);
    }




    public function updateChambre(Request $request)

    {

        $messages = [

            "categoriechambre_id.required" => "La catégorie de la chambre est requise",
            "typehebergement_id.required" => "Le type d'hebergement de la chambre est requis",
            "hotel_id.required" => "L'hôtel  de la chambre est requise",
            "ville_id.required" => "La ville  de la chambre est requise",
            "pays_id.required" => "Le pays  de la chambre est requis",

            "nom_chambre.required" => "Le nom de la chambre est requis",
            "nom_chambre.max" => "Le nom de la chambre  est trop long",
            "nom_chambre.unique" => "Cette chambre existe déjà dans le système",
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

            "nom_chambre" => "bail|required|max:200",
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

  
        $chambre = Chambre::findOrFail($request->id_chambre);
        $chambre->nom_chambre = $request->nom_chambre;
        $chambre->slug_chambre = Str::slug("Chambre-" . $request->nom_chambre);
        $chambre->description_chambre = $request->description_chambre;

        $chambre->categoriechambre_id = ($request->categoriechambre_id);
        $chambre->typehebergement_id = $request->typehebergement_id;
        $chambre->hotel_id = ($request->hotel_id);
        $chambre->ville_id = $request->ville_id;
        $chambre->pays_id = $request->pays_id;

        $chambre->classe_chambre = $request->classe_chambre;
        $chambre->nombre_lits_chambre = $request->nombre_lits_chambre;
        $chambre->nombre_places_chambre = $request->nombre_places_chambre;
        $chambre->prix_standard_chambre = $request->prix_standard_chambre;
        $chambre->image_chambre = $request->image_chambre;
       
        $chambre->status_chambre = true;


        if ($request->hasFile('image_chambre')) {
            $image = $request->image_chambre;
            $chambre_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $chambre_new_name);
            $chambre->image_chambre = '/storage/uploads/' . $chambre_new_name;
        }


        $process = $chambre->save();
        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Mise à jour de la chambre" . $chambre->nom_chambre . " avec succes dans le système.", Auth::id());
        else saveSysActivityLog(SYS_LOG_ERROR, "Echec de mise à jour de la chambre" . $chambre->nom_chambre . " avec succes dans le système.", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => false,
            "title" => "MISE A JOUR DE LA CHAMBRE",
            "message" => "La Chambre " . $chambre->nom_chambre . "  a été ajoutée avec succes"
        ]);
    }




    public function deleteChambre(Request $request)
    {
        $chambre = Chambre::findOrFail($request->id_chambre);
        $cheminFinale = $chambre->image_chambre;

        unlink(public_path($cheminFinale));
        $process = $chambre->delete();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression de la chambre dans le système", Auth::id());
        else saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression de la chambre dans le système", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => true,
            "title" => "SUPPRESSION DE LA CHAMBRE",
            "message" => "La chambre a été bien supprimée dans le système"
        ]);
    }



    
}
