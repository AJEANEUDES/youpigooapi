<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Pays;
use App\Models\Societe;
use App\Models\Typehebergement;
use App\Models\Ville;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SocieteController extends Controller
{
    public function getSociete()
    {
        $societes = Societe::all();
        $hotels = Hotel::where('status_hotel', true)->orderByDesc('created_at')->get();
        $typehebergements = Typehebergement::where('status_typehebergement', true)->orderByDesc('created_at')->get();
        $villes = Ville::where('status_ville', true)->orderByDesc('created_at')->get();
        $pays = Pays::where('status_pays', true)->orderByDesc('created_at')->get();
        return view('packages.societes.societe', compact('societes', 'hotels' , 'villes' ,
         'pays' , 'typehebergements'));
    }

    public function infoSociete(Request $request)
    {
        $societe = Societe::where('id_societe', decodeId($request->id_societe))
            ->select('societes.*', 'hotels.*', 'users.*' , 'typehebergements.*'
            , 'villes.*',  'pays.*')
            ->join('hotels', 'hotels.id_hotel', '=', 'societes.hotel_id')
            ->join('typehebergements', 'typehebergements.id_typehebergements',
             '=', 'societes.typehebergements_id')

            ->join('villes', 'villes.id_ville', '=', 'societes.ville_id')
            ->join('pays', 'pays.id_pays', '=', 'societes.pays_id')
            ->join('users', 'users.id', '=', 'societes.created_by')
            ->orderByDesc('societes.created_at')
            ->first();
        return response()->json($societe);
    }

    public function storeSociete(Request $request)
    {
        $messages = [
            "nom_societe.required" => "Le nom de la societe est requis",
            "nom_societe.max" => "Le nom de la societe est trop long",
            "nom_societe.unique" => "Cette societe existe deja dans le systeme",
            "adresse_societe.required" => "L'adresse de la societe est requis",
            "adresse_societe.max" => "L'adresse de la societe est trop long",
            "telephone1_societe.required" => "Le numero de telephone1 de la societe est requis",
            "telephone1_societe.max" => "Le numero de telephone1 de la societe est trop long",
            "telephone2_societe.max" => "Le numero de telephone2 de la societe est trop long",
            "hotel_id.required" => "L'hotel' de la societe est requis",
            "typehebergement_id.required" => "Le type d'hébergement' de la societe est requis",
            "ville_id.required" => "La ville' de la societe est requise",
            "pays_id.required" => "Le pays' de la societe est requis",
        ];

        $validator = Validator::make($request->all(), [
            "nom_societe" => "bail|required|max:100|unique:societes,nom_societe",
            "adresse_societe" => "bail|required|max:100",
            "telephone1_societe" => "bail|required|max:100",
            "telephone2_societe" => "bail|max:100",
            "hotel_id" => "bail|required",
            "ville_id" => "bail|required",
            "pays_id" => "bail|required",
            "typehebergement_id" => "bail|required",
            
        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "redirect_to" => null,
            "title" => "ENREGISTREMENT DE LA SOCIETE",
            "message" => $validator->errors()->first(),
        ]);

        $societe = new Societe();
        $societe->nom_societe = $request->nom_societe;
        $societe->code_societe = "SOCIETE-" . generateToken(6, DIGIT_TOKEN);
        $societe->slug_societe = Str::slug("societe-" . $request->nom_societe);
        $societe->adresse_societe = $request->adresse_societe;
        $societe->telephone1_societe = $request->telephone1_societe;
        $societe->telephone2_societe = $request->telephone2_societe;
        $societe->status_societe = true;
        $societe->hotel_id = $request->hotel_id;
        $societe->ville_id = $request->ville_id;
        $societe->pays_id = $request->pays_id;
        $societe->typehebergement_id = $request->typehebergement_id;
        $societe->created_by = Auth::id();
        $process = $societe->save();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Enregistrement de la societé " . $societe->nom_societe . " avec succes dans le système.", Auth::id());
        else saveSysActivityLog(SYS_LOG_ERROR, "Echec d'enregistrement de la societé " . $societe->nom_societe . " avec succes dans le système.", Auth::id());

        return response()->json([
            "status" => true,
            "reload" => false,
            "redirect_to" => null,
            "title" => "ENREGISTREMENT DE LA SOCIETE",
            "message" => "La sociète " . $societe->nom_societe . " a été ajoutée avec succes"
        ]);
    }

    public function updateSociete(Request $request)
    {
        $messages = [
            "nom_societe.required" => "Le nom de la societe est requis",
            "nom_societe.max" => "Le nom de la societe est trop long",
            "nom_societe.unique" => "Cette societe existe deja dans le systeme",
            "adresse_societe.required" => "L'adresse de la societe est requis",
            "adresse_societe.max" => "L'adresse de la societe est trop long",
            "telephone1_societe.required" => "Le numero de telephone1 de la societe est requis",
            "telephone1_societe.max" => "Le numero de telephone1 de la societe est trop long",
            "telephone2_societe.max" => "Le numero de telephone2 de la societe est trop long",
            "hotel_id.required" => "L'hotel' de la societe est requis",
            "typehebergement_id.required" => "Le type d'hébergement' de la societe est requis",
            "ville_id.required" => "La ville' de la societe est requise",
            "pays_id.required" => "Le pays' de la societe est requis",
        ];


        $validator = Validator::make($request->all(), [
            "nom_societe" => "bail|required|max:100|unique:societes,nom_societe",
            "adresse_societe" => "bail|required|max:100",
            "telephone1_societe" => "bail|required|max:100",
            "telephone2_societe" => "bail|max:100",
            "hotel_id" => "bail|required",
            "ville_id" => "bail|required",
            "pays_id" => "bail|required",
            "typehebergement_id" => "bail|required",


        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "redirect_to" => null,
            "title" => "MISE A JOUR DE LA SOCIETE",
            "message" => $validator->errors()->first(),
        ]);

        $societe = Societe::findOrFail($request->id_societe);
        $societe->nom_societe = $request->nom_societe;
        $societe->slug_societe = Str::slug("societe-" . $request->nom_societe);
        $societe->adresse_societe = $request->adresse_societe;
        $societe->telephone1_societe = $request->telephone1_societe;
        $societe->telephone2_societe = $request->telephone2_societe;
        $societe->status_societe = true;
        $societe->hotel_id = $request->hotel_id;
        $societe->ville_id = $request->ville_id;
        $societe->pays_id = $request->pays_id;
        $societe->typehebergement_id = $request->typehebergement_id;
        $process = $societe->save();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Mise à jour de la sociète " . $societe->nom_societe . " avec succes dans le système.", Auth::id());
        else saveSysActivityLog(SYS_LOG_ERROR, "Echec de mise à jour de la sociète " . $societe->nom_societe . " avec succes dans le système.", Auth::id());

        return response()->json([
            "status" => true,
            "reload" => false,
            "redirect_to" => null,
            "title" => "MISE A JOUR DE LA SOCIETE",
            "message" => "La sociète " . $societe->nom_societe . " a été mise à jour avec succes"
        ]);
    }

    public function deleteSociete(Request $request)
    {
        $societe = Societe::findOrFail($request->id_societe);
        $process = $societe->delete();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression de la sociète " . $societe->nom_societe . " dans le système", Auth::id());
        else saveSysActivityLog(SYS_LOG_SUCCESS, "Echec de suppression de la sociète " . $societe->nom_societe . " dans le système", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => true,
            "title" => "SUPPRESSION DE LA SOCIETE",
            "message" => "La sociète " . $societe->nom_societe . " a été bien supprimée dans le système"
        ]);
    }
}
