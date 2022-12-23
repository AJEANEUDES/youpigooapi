<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Pays;
use App\Models\Typehebergement;
use App\Models\User;
use App\Models\Ville;
use App\Models\Societe;
use App\Models\GestionnaireSociete;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;


class HotelController extends Controller
{
    public function getSpecHotelChambre(Request $request)
    {
        $societe = Societe::where('chambre_id', ($request->chambre_id))->first();
        return response()->json($societe);
    }


    public function getHotel()
    {
        $gestionnaire_hotel_id = Auth::id();
        $societe = Gestionnairesociete::where('gestionnaire_hotel_id', $gestionnaire_hotel_id)->first();

        $societes = Societe::where('id_societe', $societe->societe_id)->where('status_societe', true)->first();


        $typehebergements = Typehebergement::where('id_typehebergement', $societes->typehbergement_id)
        ->where('status_typehebergement', true)->first();

        
        $pays = Pays::where('status_pays', true)->get();
        $villes = Ville::where('status_ville', true)->get();


        $hotels = Hotel::where('societe_id', $societes->id_societe)->where('status_hotel', true)
        ->orderByDesc('created_at')->get();
        
        return view('packages.hotels.hotel.hotel', compact([
            'hotels', 'societes',  'villes', 'pays' , 'typehebergements'

        ]));


    }



    public function getSpecHotel(Request $request): JsonResponse
    {
        $hotel = Hotel::find(($request->id_hotel));
        return response()->json(['data' => $hotel]);
    }



    public function infoHotel(Request $request)
    {

        $hotel_user = User::where('id', ($request->id_hotel_user))->orderByDesc('created_at')->first();
        return response()->json($hotel_user);


        
        // $hotel = Hotel::where('id_hotel', ($request->id_hotel))
        //     ->select(
        //         'typehebergements.*',
        //         'hotels.*',
        //         'societes.*',
        //         'villes.*',
        //         'pays.*'
        //     )

        //     ->join('typehebergements', 'typehebergements.id_typehebergement', '=', 'hotels.typehebergement_id')
        //     ->join('villes', 'villes.id_ville', '=', 'hotels.ville_id')
        //     ->join('pays', 'pays.id_pays', '=', 'hotels.pays_id')
        //     ->join('societes', 'societes.id_societe', '=', 'hotels.societe_id')
        //     ->first();
        // return response()->json($hotel);
    }



    public function updateProfileHotel(Request $request)
    {
        $messages = [
            "nom_user.required" => "Votre nom est requis",
            "nom_user.max" => "Votre nom est trop long",
            "prenoms_user.required" => "Votre prenom est requis",
            "prenoms_user.max" => "Votre prenom est trop long",
            "adresse_user.required" => "L'adresse est requise",
            "adresse_user.max" => "L'adresse est trop longue",
            "telephone_user.required" => "Le numero de telephone est requis",
            "telephone_user.min" => "Le numero de telephone est invalide",
            "email_user.required" => "Votre adresse mail est requise",
            "email_user.email" => "Votre adresse email est invalide",
            "email_user.max" => "Votre adresse email est trop longue",
            "telephone_user.required" => "Votre numero de telephone est requis",
        ];

        $validator = Validator::make($request->all(), [

            "nom_user" => "bail|required|max:50|unique:users,nom_user",
            "prenoms_user" => "bail|required|max:50",
            "adresse_user" => "bail|required|max:50",
            "telephone_user" => "bail|required|min:8",
            "email_user" => "bail|required|unique:users,email_user",

        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "redirect_to" => null,
            "title" => "MISE A JOUR DU COMPTE",
            "message" => $validator->errors()->first()
        ]);

        $gestionnaire = User::findOrFail(($request->id_gestionnaire));
        $gestionnaire->nom_user = $request->nom_user;
        $gestionnaire->prenom_user = $request->prenom_user;
        $gestionnaire->email_user = $request->email_user;
        $gestionnaire->telephone_user = $request->telephone_user;
        $gestionnaire->adresse_user = $request->adresse_user;
        $gestionnaire->pays_user = $request->pays_user;
        $gestionnaire->prefix_user = $request->prefix_user;

        if ($request->hasFile('avatar_user')) {
            $image = $request->avatar_user;
            $avatar_user_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $avatar_user_name);
            $gestionnaire->avatar_user = '/storage/uploads/' . $avatar_user_name;
        }

        $process = $gestionnaire->save();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Mise à jour du gestionnaire " . $gestionnaire->nom_user . " " . $gestionnaire->prenom_user . " avec succes dans le système.", Auth::id());
        else saveSysActivityLog(SYS_LOG_ERROR, "Echec de mise à jour du gestionnaire " . $gestionnaire->nom_user . " " . $gestionnaire->prenom_user . " avec succes dans le système.", Auth::id());

        return response()->json([
            "status" => true,
            "reload" => false,
            "title" => "MISE A JOUR DU COMPTE",
            "message" => "Mr/Mlle " . $gestionnaire->nom_user . " " . $gestionnaire->prenom_user . " votre compte a été modifié avec succes"
        ]);
    }



    
    public function storeHotel(Request $request)
    {
        $messages = [
            "typehebergement_id.required" => "Le type d'hebergement de l'hôtel est requis",
            "ville_id.required" => "La ville  de l'hôtel est requise",
            "pays_id.required" => "Le pays  de l'hôtel est requis",
            "nom_hotel.required" => "Le nom de l'hôtel est requis",
            "description_hotel.required" => "La description de l'hôtel est requise",
            "description_hotel.max" => "La description de l'hôtel est trop longue",
            "prix_estimatif_chambre_hotel.required" => "Le prix de l'hôtel est requis",
            "telephone1_hotel.required" => "Le téléphone de l'hôtel est requis",
            "email_hotel.required" => "L'email de l'hôtel est requis",
            

            "image_chambre.required" => "L'image de l'hôtel est requise",
            "image_chambre.mimes" => "L'image de l'hôtel que vous avez selectionnez est invalide",
            "image_chambre.max" => "La taille de l'image de l'hôtel est trop lourde",


            "image1_chambre.required" => "L'image de l'hôtel est requise",
            "image1_chambre.mimes" => "L'image de l'hôtel que vous avez selectionnez est invalide",
            "image1_chambre.max" => "La taille de l'image de l'hôtel est trop lourde",


            "image2_chambre.required" => "L'image de l'hôtel est requise",
            "image2_chambre.mimes" => "L'image de l'hôtel que vous avez selectionnez est invalide",
            "image2_chambre.max" => "La taille de l'image de l'hôtel est trop lourde",


            "image3_chambre.required" => "L'image de l'hôtel est requise",
            "image3_chambre.mimes" => "L'image de l'hôtel que vous avez selectionnez est invalide",
            "image3_chambre.max" => "La taille de l'image de l'hôtel est trop lourde",


            "image4_chambre.required" => "L'image de l'hôtel est requise",
            "image4_chambre.mimes" => "L'image de l'hôtel que vous avez selectionnez est invalide",
            "image4_chambre.max" => "La taille de l'image de l'hôtel est trop lourde",


            "image5_chambre.required" => "L'image de l'hôtel est requise",
            "image5_chambre.mimes" => "L'image de l'hôtel que vous avez selectionnez est invalide",
            "image5_chambre.max" => "La taille de l'image de l'hôtel est trop lourde",


            "image6_chambre.required" => "L'image de l'hôtel est requise",
            "image6_chambre.mimes" => "L'image de l'hôtel que vous avez selectionnez est invalide",
            "image6_chambre.max" => "La taille de l'image de l'hôtel est trop lourde",


          


        ];


        $validator = Validator::make($request->all(), [
            "ville_id" => "bail|required",
            "pays_id" => "bail|required",
            "typehebergement_id" => "bail|required",
            "societe_id" => "bail|required",
            "nom_hotel" => "bail|required|max:500",
            "description_hotel" => "bail|required",
            // "classe_hotel" => "bail|required",
            "telephone1_hotel" => "bail|required",
            "email_hotel" => "bail|required",
            "prix_estimatif_chambre_hotel" => "bail|required",


            "image_chambre" => "bail|required",
            "image_chambre" => "bail|max:2048000",
            "image_chambre.*" => "bail|mimes:jpeg,jpg,png",

            "image1_chambre" => "bail|required",
            "image1_chambre" => "bail|max:2048000",
            "image1_chambre.*" => "bail|mimes:jpeg,jpg,png",

            "image2_chambre" => "bail|required",
            "image2_chambre" => "bail|max:2048000",
            "image2_chambre.*" => "bail|mimes:jpeg,jpg,png",

            "image3_chambre" => "bail|required",
            "image3_chambre" => "bail|max:2048000",
            "image3_chambre.*" => "bail|mimes:jpeg,jpg,png",

            "image4_chambre" => "bail|required",
            "image4_chambre" => "bail|max:2048000",
            "image4_chambre.*" => "bail|mimes:jpeg,jpg,png",

            "image5_chambre" => "bail|required",
            "image5_chambre" => "bail|max:2048000",
            "image5_chambre.*" => "bail|mimes:jpeg,jpg,png",

            "image6_chambre" => "bail|required",
            "image6_chambre" => "bail|max:2048000",
            "image6_chambre.*" => "bail|mimes:jpeg,jpg,png",

        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "ENREGISTREMENT DE L'HOTEL",
            "message" => $validator->errors()->first()
        ]);

        $typehebergement = Typehebergement::where('id_typehebergement', $request->typehebergement_id)->first();
        $ville = Ville::where('id_ville', $request->ville_id)->first();
        // $pays = Pays::where('id_pays', $request->pays_id)->first();

        $hotel = new Hotel();
        $hotel->code_hotel = "HOTEL-" . generateToken(6, DIGIT_TOKEN);
        $hotel->slug_hotel = Str::slug($typehebergement->nom_typehebergement . "-" . $ville->nom_ville);
        $hotel->typehebergement_id = $request->typehebergement_id;
        $hotel->societe_id = ($request->societe_id);
        $hotel->nom_hotel = $request->nom_hotel;
        $hotel->description_hotel = $request->description_hotel;
        $hotel->ville_id = $request->ville_id;
        $hotel->pays_id = $request->pays_id;
        $hotel->telephone1_hotel = $request->telephone1_hotel;
        $hotel->email_hotel = $request->email_hotel;
        $hotel->prix_estimatif_chambre_hotel = $request->prix_estimatif_chambre_hotel;
        $hotel->image_hotel = $request->image_hotel;
        $hotel->image1_hotel = $request->image1_hotel;
        $hotel->image2_hotel = $request->image2_hotel;
        $hotel->image3_hotel = $request->image3_hotel;
        $hotel->image4_hotel = $request->image4_hotel;
        $hotel->image5_hotel = $request->image5_hotel;
        $hotel->image6_hotel = $request->image6_hotel;
        $hotel->status_hotel = true;
        $hotel->created_by = Auth::id();

        if ($request->hasFile('image_hotel')) {
            $image = $request->image_hotel;
            $hotel_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $hotel_new_name);
            $hotel->image_hotel = '/storage/uploads/' . $hotel_new_name;
        }


        if ($request->hasFile('image1_hotel')) {
            $image = $request->image1_hotel;
            $hotel_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $hotel_new_name);
            $hotel->image1_hotel = '/storage/uploads/' . $hotel_new_name;
        }


        if ($request->hasFile('image2_hotel')) {
            $image = $request->image2_hotel;
            $hotel_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $hotel_new_name);
            $hotel->image2_hotel = '/storage/uploads/' . $hotel_new_name;
        }


        if ($request->hasFile('image3_hotel')) {
            $image = $request->image3_hotel;
            $hotel_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $hotel_new_name);
            $hotel->image3_hotel = '/storage/uploads/' . $hotel_new_name;
        }


        if ($request->hasFile('image4_hotel')) {
            $image = $request->image4_hotel;
            $hotel_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $hotel_new_name);
            $hotel->image4_hotel = '/storage/uploads/' . $hotel_new_name;
        }

        if ($request->hasFile('image5_hotel')) {
            $image = $request->image5_hotel;
            $hotel_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $hotel_new_name);
            $hotel->image5_hotel = '/storage/uploads/' . $hotel_new_name;
        }

        if ($request->hasFile('image6_hotel')) {
            $image = $request->image6_hotel;
            $hotel_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $hotel_new_name);
            $hotel->image6_hotel = '/storage/uploads/' . $hotel_new_name;
        }


        $process = $hotel->save();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Enregistrement de l'hotel avec succes dans le système.", Auth::id());
        else saveSysActivityLog(SYS_LOG_ERROR, "Echec d'enregistrement de l'hotel avec succes dans le système.", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => false,
            "title" => "ENREGISTREMENT DE L'HOTEL",
            "message" => "L'hotel a été ajoutée avec succes"
        ]);
    }




 public function updateHotel(Request $request)

    {
        $messages = [
            "typehebergement_id.required" => "Le type d'hebergement de l'hôtel est requis",
            "ville_id.required" => "La ville  de l'hôtel est requise",
            "pays_id.required" => "Le pays  de l'hôtel est requis",
            "nom_hotel.required" => "Le nom de l'hôtel est requis",
            "description_hotel.required" => "La description de l'hôtel est requise",
            "description_hotel.max" => "La description de l'hôtel est trop longue",
            "prix_estimatif_chambre_hotel.required" => "Le prix de l'hôtel est requis",
            "telephone1_hotel.required" => "Le téléphone de l'hôtel est requis",
            "email_hotel.required" => "L'email de l'hôtel est requis",
            

            "image_chambre.required" => "L'image de l'hôtel est requise",
            "image_chambre.mimes" => "L'image de l'hôtel que vous avez selectionnez est invalide",
            "image_chambre.max" => "La taille de l'image de l'hôtel est trop lourde",


            "image1_chambre.required" => "L'image de l'hôtel est requise",
            "image1_chambre.mimes" => "L'image de l'hôtel que vous avez selectionnez est invalide",
            "image1_chambre.max" => "La taille de l'image de l'hôtel est trop lourde",


            "image2_chambre.required" => "L'image de l'hôtel est requise",
            "image2_chambre.mimes" => "L'image de l'hôtel que vous avez selectionnez est invalide",
            "image2_chambre.max" => "La taille de l'image de l'hôtel est trop lourde",


            "image3_chambre.required" => "L'image de l'hôtel est requise",
            "image3_chambre.mimes" => "L'image de l'hôtel que vous avez selectionnez est invalide",
            "image3_chambre.max" => "La taille de l'image de l'hôtel est trop lourde",


            "image4_chambre.required" => "L'image de l'hôtel est requise",
            "image4_chambre.mimes" => "L'image de l'hôtel que vous avez selectionnez est invalide",
            "image4_chambre.max" => "La taille de l'image de l'hôtel est trop lourde",


            "image5_chambre.required" => "L'image de l'hôtel est requise",
            "image5_chambre.mimes" => "L'image de l'hôtel que vous avez selectionnez est invalide",
            "image5_chambre.max" => "La taille de l'image de l'hôtel est trop lourde",


            "image6_chambre.required" => "L'image de l'hôtel est requise",
            "image6_chambre.mimes" => "L'image de l'hôtel que vous avez selectionnez est invalide",
            "image6_chambre.max" => "La taille de l'image de l'hôtel est trop lourde",



        ];


        $validator = Validator::make($request->all(), [
             "ville_id" => "bail|required",
            "pays_id" => "bail|required",
            "typehebergement_id" => "bail|required",
            "societe_id" => "bail|required",
            "nom_hotel" => "bail|required|max:500",
            "description_hotel" => "bail|required",
            // "classe_hotel" => "bail|required",
            "telephone1_hotel" => "bail|required",
            "email_hotel" => "bail|required",
            "prix_estimatif_chambre_hotel" => "bail|required",


            "image_chambre" => "bail|required",
            "image_chambre" => "bail|max:2048000",
            "image_chambre.*" => "bail|mimes:jpeg,jpg,png",

            "image1_chambre" => "bail|required",
            "image1_chambre" => "bail|max:2048000",
            "image1_chambre.*" => "bail|mimes:jpeg,jpg,png",

            "image2_chambre" => "bail|required",
            "image2_chambre" => "bail|max:2048000",
            "image2_chambre.*" => "bail|mimes:jpeg,jpg,png",

            "image3_chambre" => "bail|required",
            "image3_chambre" => "bail|max:2048000",
            "image3_chambre.*" => "bail|mimes:jpeg,jpg,png",

            "image4_chambre" => "bail|required",
            "image4_chambre" => "bail|max:2048000",
            "image4_chambre.*" => "bail|mimes:jpeg,jpg,png",

            "image5_chambre" => "bail|required",
            "image5_chambre" => "bail|max:2048000",
            "image5_chambre.*" => "bail|mimes:jpeg,jpg,png",

            "image6_chambre" => "bail|required",
            "image6_chambre" => "bail|max:2048000",
            "image6_chambre.*" => "bail|mimes:jpeg,jpg,png",


        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MISE A JOUR DE LA hotel",
            "message" => $validator->errors()->first()
        ]);

        $typehebergement = Typehebergement::where('id_typehebergement', $request->typehebergement_id)->first();
        $ville = Ville::where('id_ville', $request->ville_id)->first();


        $hotel = Hotel::findOrFail($request->id_hotel);
        $hotel->slug_hotel = Str::slug($typehebergement->nom_typehebergement . "-" . $ville->nom_ville);
        $hotel->typehebergement_id = $request->typehebergement_id;
        $hotel->societe_id = ($request->societe_id);
        $hotel->nom_hotel = $request->nom_hotel;
        $hotel->description_hotel = $request->description_hotel;
        $hotel->ville_id = $request->ville_id;
        $hotel->pays_id = $request->pays_id;
        $hotel->telephone1_hotel = $request->telephone1_hotel;
        $hotel->email_hotel = $request->email_hotel;
        $hotel->prix_estimatif_chambre_hotel = $request->prix_estimatif_chambre_hotel;
        $hotel->image_hotel = $request->image_hotel;
        $hotel->image1_hotel = $request->image1_hotel;
        $hotel->image2_hotel = $request->image2_hotel;
        $hotel->image3_hotel = $request->image3_hotel;
        $hotel->image4_hotel = $request->image4_hotel;
        $hotel->image5_hotel = $request->image5_hotel;
        $hotel->image6_hotel = $request->image6_hotel;
        $hotel->status_hotel = true;
        $hotel->created_by = Auth::id();


        if ($request->hasFile('image_hotel')) {
            $image = $request->image_hotel;
            $hotel_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $hotel_new_name);
            $hotel->image_hotel = '/storage/uploads/' . $hotel_new_name;
        }


        if ($request->hasFile('image1_hotel')) {
            $image = $request->image1_hotel;
            $hotel_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $hotel_new_name);
            $hotel->image1_hotel = '/storage/uploads/' . $hotel_new_name;
        }


        if ($request->hasFile('image2_hotel')) {
            $image = $request->image2_hotel;
            $hotel_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $hotel_new_name);
            $hotel->image2_hotel = '/storage/uploads/' . $hotel_new_name;
        }


        if ($request->hasFile('image3_hotel')) {
            $image = $request->image3_hotel;
            $hotel_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $hotel_new_name);
            $hotel->image3_hotel = '/storage/uploads/' . $hotel_new_name;
        }


        if ($request->hasFile('image4_hotel')) {
            $image = $request->image4_hotel;
            $hotel_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $hotel_new_name);
            $hotel->image4_hotel = '/storage/uploads/' . $hotel_new_name;
        }

        if ($request->hasFile('image5_hotel')) {
            $image = $request->image5_hotel;
            $hotel_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $hotel_new_name);
            $hotel->image5_hotel = '/storage/uploads/' . $hotel_new_name;
        }

        if ($request->hasFile('image6_hotel')) {
            $image = $request->image6_hotel;
            $hotel_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $hotel_new_name);
            $hotel->image6_hotel = '/storage/uploads/' . $hotel_new_name;
        }


        $process = $hotel->save();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Mise à jour de l' hotel "  . $ville->nom_ville . " " . $typehebergement->nom_typehebergement . " avec succes dans le système.", Auth::id());
        else saveSysActivityLog(SYS_LOG_ERROR, "Echec de mise à jour de l' hotel "  . $ville->nom_ville . " " . $typehebergement->nom_typehebergement . " avec succes dans le système.", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => false,
            "title" => "MISE A JOUR DE l' HOTEL",
            "message" => "l' hotel " . $ville->nom_ville . " " . $typehebergement->nom_typehebergement . " a été ajoutée avec succes"
        ]);
    }




    public function deleteHotel(Request $request)
    {
        $hotel = Hotel::findOrFail($request->id_hotel);
        $cheminFinale = $hotel->image_hotel;
        $cheminFinale = $hotel->image1_hotel;
        $cheminFinale = $hotel->image2_hotel;
        $cheminFinale = $hotel->image3_hotel;
        $cheminFinale = $hotel->image4_hotel;
        $cheminFinale = $hotel->image5_hotel;
        $cheminFinale = $hotel->image6_hotel;
       
        unlink(public_path($cheminFinale));
        $process = $hotel->delete();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression de l' hotel dans le système", Auth::id());
        else saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression de l' hotel dans le système", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => true,
            "title" => "SUPPRESSION DE L' HOTEL",
            "message" => "l' hotel a été bien supprimée dans le système"
        ]);
    }





}
