<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Categoriechambre;
use App\Models\Gestionnairesociete;
use App\Models\Hotel;   
use Illuminate\Support\Str;
use App\Models\Societe;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CategoriechambreController extends Controller
{

    public function getCategorieChambreHotel()
    {
        $hotels = Hotel::where('status_hotel', true)->orderBydesc('created_at')->get();
        
        $categoriechambres = Categoriechambre::select('categoriechambres.*', 'hotels.*')
        ->join('hotels','hotels.id_hotel', '=', 'categoriechambres.hotel_id' )
        ->orderByDesc('categoriechambres.created_at')
        ->get();

        return view('packages.categoriechambres.admin.categoriechambre', compact(['categoriechambres', 'hotels']));

    }


    public function infoCategoriechambre(Request $request)
    {
        $categoriechambre = Categoriechambre::where('id_categoriechambre', ($request->id_categoriechambre))
        ->select('categoriechambres.*','hotels.*', 'users.*' )
        ->join('hotels','hotels.id_hotel', '=', 'categoriechambres.hotel_id' )
        ->join('users', 'users.id', '=', 'categoriechambres.created_by')
        ->orderByDesc('categoriechambres.created_at')
        ->first();
        return response()->json($categoriechambre);

        
    }


   
    // public function getSpecCategorieChambreHotel(Request $request)
    // {
    //     $categoriechambre = Categoriechambre::where('hotel_id', ($request->hotel_id))->first();
    //     return response()->json($categoriechambre);
    // }

    
    // public function getCategoriechambreHotel()
    // {
    //     $hotel_user_id = Auth::id();
       
    //     $hotel = Hotel::where('hotel_user_id', $hotel_user_id)->first();
    //     // $societe = Gestionnairesociete::where('hotel_user_id', $hotel_user_id)->first();
    //     $hotels = Hotel::where('hotel_id', $hotel->hotel_id)->where('status_hotel', true)->first()
    //     // $societes = Societe::where('id_societe', $societe->societe_id??null)->where('status_societe', true)->first();


    //     // $hotels = Hotel::where('societe_id', $societes->id_societe??null)->where('status_hotel', true)
    //     ->orderByDesc('created_at')->get();
        
    //     return view('packages.hotels.hotel', compact([
    //         'hotels', 'categoriechambres'

    //     ]));


    // }



    
    // public function getSpecCategoriechambre(Request $request): JsonResponse
    // {
    //     $categoriechambre = Categoriechambre::find(($request->id_categoriechambre));
    //     return response()->json(['data' => $categoriechambre]);
    // }


    // public function infoCategoriechambre(Request $request)
    // {
    //     $categoriechambre = Hotel::where('id_categoriechambre', ($request->id_categoriechambre))
    //         ->select(

    //             'hotels.*',
    //         )

    //         ->join('categoriechambres', 'categoriechambres.id_categoriechambre',
    //          '=', 'hotels.categoriechambre_id')

    //         ->first();
    //     return response()->json($categoriechambre);
    // }



    public function storeCategoriechambre(Request $request)
    {
        $messages = [
            "libelle_categoriechambre.required" => "Le libelle de la catégorie de la chambre est requis",
            "libelle_categoriechambre.max" => "Le libelle de la catégorie de la chambre est trop long",
            "libelle_categoriechambre.unique" => "Le libelle existe deja dans le système",
            "hotel_id.required" => "L'hôtel de la catégorie est requis",

            "description_categoriechambre.required" => "La description de la catégorie de chambre est requise",
            "description_categoriechambre.max" => "La description de la catégorie de chambre est trop longue",
            "prix_estimatif_categoriechambre.required" => "Le prix de la catégorie de chambre est requis",

            "image_categoriechambre.required" => "L'image de la catégorie de chambre est requise",
            "image_categoriechambre.mimes" => "L'image de la catégorie de chambre que vous avez selectionnez est invalide",
            "image_categoriechambre.max" => "La taille de l'image de la catégorie de chambre est trop lourde",

        ];


        $validator = Validator::make($request->all(), [
            "libelle_categoriechambre" => "bail|required|max:100|unique:categoriechambres,libelle_categoriechambre",
            "hotel_id" => "bail|required",
            "description_categoriechambre" => "bail|required",
            "prix_estimatif_categoriechambre" => "bail|required",
            "prix_estimatif_chambre_hotel" => "bail|required",

            "image_categoriechambre" => "bail|required",
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
        $categoriechambre->hotel_id = $request->hotel_id;
        $categoriechambre->image_categoriechambre = $request->image_categoriechambre;
        $categoriechambre->status_categoriechambre = true;
        $categoriechambre->created_by = Auth::id();

        if ($request->hasFile('image_categoriechambre')) {
            $image = $request->image_categoriechambre;
            $categoriechambre_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $categoriechambre_new_name);
            $categoriechambre->image_categoriechambre = '/storage/uploads/' . $categoriechambre_new_name;
        }

        $process = $categoriechambre->save();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Enregistrement de la catégorie de chambre" . $categoriechambre->libelle_categoriechambre . " avec succes dans le système.", Auth::id());
        else saveSysActivityLog(SYS_LOG_ERROR, "Echec d'enregistrement de la catégorie de chambre" . $categoriechambre->libelle_categoriechambre . " avec succes dans le système.", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => false,
            "title" => "ENREGISTREMENT DE la catégorie de chambre",
            "message" => "la catégorie de chambre" . $categoriechambre->libelle_categoriechambre . "a été ajoutée avec succes"
        ]);
    }




    public function updateCategoriechambre(Request $request)

    {
        $messages = [
            "libelle_categoriechambre.required" => "Le libelle de la catégorie de la chambre est requis",
            "description_categoriechambre.required" => "La description de la catégorie de chambre est requise",
            "prix_estimatif_categoriechambre.required" => "Le prix de la catégorie de chambre est requis",

            "image_categoriechambre.required" => "L'image de la catégorie de chambre est requise",
            "image_categoriechambre.mimes" => "L'image de la catégorie de chambre que vous avez selectionnez est invalide",
            "image_categoriechambre.max" => "La taille de l'image de la catégorie de chambre est trop lourde",


        ];


        $validator = Validator::make($request->all(), [
            "libelle_categoriechambre" => "bail|required|max:500",
            "description_categoriechambre" => "bail|required",
            "prix_estimatif_categoriechambre" => "bail|required",


            "image_categoriechambre" => "bail|required",
            "image_categoriechambre" => "bail|max:2048000",
            "image_categoriechambre.*" => "bail|mimes:jpeg,jpg,png",


        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MISE A JOUR DE LA CATEGORIE DE CHAMBRE",
            "message" => $validator->errors()->first()
        ]);



        $categoriechambre = Categoriechambre::findOrFail($request->id_categoriechambre);
        $categoriechambre->libelle_categoriechambre = $request->libelle_categoriechambre;
        $categoriechambre->description_categoriechambre = $request->description_categoriechambre;
        $categoriechambre->slug_categoriechambre = Str::slug("categoriechambre-" . $request->libelle_categoriechambre);
        $categoriechambre->hotel_id = $request->hotel_id;
        $categoriechambre->prix_estimatif_categoriechambre = $request->prix_estimatif_categoriechambre;
        $categoriechambre->image_categoriechambre = $request->image_categoriechambre;
        $categoriechambre->status_categoriechambre = true;

        if ($request->hasFile('image_categoriechambre')) {
            $image = $request->image_categoriechambre;
            $categoriechambre_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $categoriechambre_new_name);
            $categoriechambre->image_categoriechambre = '/storage/uploads/' . $categoriechambre_new_name;
        }



        $process = $categoriechambre->save();


         //Enregistrement du systeme de log
         if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Mise à jour de la catégorie de chambre ". $categoriechambre->libelle_categoriechambre . "avec succes dans le système.", Auth::id());
         else saveSysActivityLog(SYS_LOG_ERROR, "Echec de la Mise à jour de la catégorie de chambre " . $categoriechambre->libelle_categoriechambre .  "avec succes dans le système.", Auth::id());
 
         return response()->json([
             "status" => $process,
             "reload" => false,
             "title" => "MISE A JOUR DE LA CATEGORIE DE CHAMBRE",
             "message" => "la catégorie de chambre " . $categoriechambre->libelle_categoriechambre . " a été ajoutée avec succes"
         ]);



    }


    public function deleteCategoriechambre(Request $request)
    {
        $categoriechambre = Categoriechambre::findOrFail($request->id_categoriechambre);
        $cheminFinale = $categoriechambre->image_categoriechambre;
       
        unlink(public_path($cheminFinale));
        $process = $categoriechambre->delete();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression de la catégorie de chambre ". $categoriechambre->libelle_categoriechambre . "dans le système", Auth::id());
        else saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression de la catégorie de chambre ". $categoriechambre->libelle_categoriechambre . " dans le système", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => true,
            "title" => "SUPPRESSION DE LA CATEGORIE DE CHAMBRE",
            "message" => "la catégorie de chambre ". $categoriechambre->libelle_categoriechambre . " a été bien supprimée dans le système"
        ]);
    }





}
