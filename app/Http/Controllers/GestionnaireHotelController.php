<?php

namespace App\Http\Controllers;

use App\Models\Categoriechambre;
use App\Models\Chambre;
use App\Models\Facture;
use App\Models\Gestionnairehotel;
use App\Models\Hotel;
use App\Models\Pays;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\Typehebergement;
use App\Models\User;
use App\Models\Ville;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GestionnaireHotelController extends Controller
{



    public function getGestionnaireHotel()
    {
        $gestionnaires = User::where('roles_user', 'Hotel')->orderByDesc('created_at')->get();
        $hotels = Hotel::where('status_hotel', true)->orderByDesc('created_at')->get();
        // $gestionnaire = Gestionnairehotel::where('', true)->orderByDesc('created_at')->get();
        return view('packages.gestionnairehotel.hotel', compact('gestionnaires', 'hotels',));
    }


    public function infoGestionnaireHotel(Request $request, $id_gestionnaire)
    {

        $gestionnaire_hotel = GestionnaireHotel::where("gestionnaire_id", $id_gestionnaire)->exists();
        $hotel = Hotel::where('id_hotel', $gestionnaire_hotel->hotel_id)->first();

        // if ($gestionnaire_hotel) {

        //     $info = User::find($id_gestionnaire_hotel);


        //     $gestionnaire_hotel = User::where('id', ($request->id_gestionnaire_hotel))->orderByDesc('created_at')->first();

        //     // return response()->json($hotel_user);

        //     return response()->json(
        //         [
        //             "status" => 1,
        //             "message" => "Gestionnaire hôtel trouvé",
        //             "data" => $info

        //         ],
        //         200
        //     );
        // } else {
        //     return response()->json(
        //         [
        //             "status" => 0,
        //             "message" => "Aucun Gestionnaire d'hôtel trouvé",

        //         ],
        //         404
        //     );
        // }

            $gestionnaire = User::where('id', $request->id_gestionnaire)
            ->orderByDesc('created_at')->first();

            return response()->json([
                'gestionnaire' => $gestionnaire,
                'hotel' => $hotel
            ]);

    }


    public function createGestionnaireHotel()
    {
        $gestionnaires = User::where('roles_user', 'Hotel')->orderByDesc('created_at')->get();
        $hotels = Hotel::where('status_hotel', true)->orderByDesc('created_at')->get();

        return view(
            'packages.gestionnairehotel.create',

            compact(

                'gestionnaires',
                'hotels',

            )
        );
    }



    public function storeGestionnaireHotel(Request $request)
    {
        $messages = [

            "hotel_id.required" => "L'hotel d'appartenance du gestionnaire est requis",
            "nom_user.required" => "Le nom est requis",
            "prenoms_user.required" => "Le prenoms est n'est pas requis",
            "email_user.required" => "L'email est requis",
            "email_user.unique" => "Cet email existe deja",
            "password.required" => "Le mot de passe est requis",
            "password.min" => "Le mot de passe est trop court",
            "password.same" => "Les mots de passes ne sont pas identiques",
            "telephone_user.required" => "Le numero de telephone est requis",
            "telephone_user.unique" => "Ce numero de telephone existe deja",
            "adresse_user.required" => "L'adresse est requise",
            "vile_user.required" => "La ville est requise",
            "pays_user.required" => "Le pays est requis",
        ];

        $validator = Validator::make($request->all(), [
            "nom_user" => "bail|required",
            "prenoms_user" => "bail|nullable",
            "email_user" => "bail|required|unique:users,email_user",
            "password" => "bail|required|min:8|same:confirmation_password",
            "telephone_user" => "bail|required|unique:users,telephone_user",
            "adresse_user" => "bail|required",
            "ville_user" => "bail|required",
            "pays_user" => "bail|required",
            "hotel_id" => "bail|required",



        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "redirect_to" => null,
            "title" => "ENREGISTREMENT DU GESTIONNAIRE D'HOTEL",
            "message" => $validator->errors()->first(),
        ]);

        // Enregistrement de gestionnaire_hotel en tant que user

        $gestionnaire = new User();
        $gestionnaire->nom_user = $request->nom_user;
        $gestionnaire->prenoms_user = $request->prenoms_user;
        $gestionnaire->email_user = $request->email_user;
        $gestionnaire->adresse_user = $request->adresse_user;
        $gestionnaire->telephone_user = $request->telephone_user;
        $gestionnaire->roles_user = "Hotel";
        $gestionnaire->password = Hash::make($request->password);
        $gestionnaire->status_user = true;

        // $gestionnaire_hotel->created_by = Auth::id();

        if ($request->hasfile('image_user')) {
            $file = $request->file('image_user');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('storage/uploads/', $filename);
            $gestionnaire->image_user = $filename;
        }

        $gestionnaire->save();

        // Enregistrement de la table gestionnaires_hotels
        $gestionnaireHotel = new GestionnaireHotel();
        $gestionnaireHotel->gestionnaire_id = $gestionnaire->id;
        $gestionnaireHotel->hotel_id = $request->hotel_id;
        $gestionnaireHotel->save();


        return redirect('Admin/gestionnairehotel/')->with('message', 'Le Gestionnaire de l\'hôtel Ajoutée avec succès');

        // return response()->json([
        //     "status" => true,
        //     "reload" => false,
        //     "redirect_to" => null,
        // "title" => "ENREGISTREMENT DU GESTIONNAIRE D'HOTEL",
        //     "message" => "Le gestionnaire de l'hôtel " . $gestionnaire_hotel->nom_user . " " . $gestionnaire_hotel->prenoms_user . " a été ajouté avec succes"
        // ]);

    }


    public function editGestionnaireHotel($id_gestionnaire)
    {
        $hotels = Hotel::where('status_hotel', true)->orderByDesc('created_at')->get();
        $gestionnaire = User::find($id_gestionnaire);
        return view('packages.gestionnairehotel.edit', compact('gestionnaire', 'hotels'));
    }


    public function updateGestionnaireHotel(Request $request, $id_gestionnaire)
    {
        $messages = [

            "hotel_id.required" => "L'hotel de géré par le gestionnaire est requis",
            "nom_user.required" => "Votre nom est requis",
            "nom_user.max" => "Votre nom est trop long",
            "prenoms_user.required" => "Votre prenom est requis",
            "prenoms_user.max" => "Votre prenom est trop long",
            "adresse_user.required" => "L'adresse est requise",
            "adresse_user.max" => "L'adresse est trop longue",
            "telephone_user.required" => "Le numero de telephone est requis",
            "telephone_user.min" => "Le numero de telephone est invalide",
            "email_user.required" => "Votre adresse mail est requise",
            "email_user.email" => "Votre adresse mail est invalide",
            "email_user.max" => "Votre adresse mail est trop longue",
            "vile_user.required" => "La ville est requise",
            "pays_user.required" => "Le pays est requis",
        ];

        $validator = Validator::make($request->all(), [
            "nom_user" => "bail|required|max:50",
            "prenoms_user" => "bail|required|max:50",
            "adresse_user" => "bail|required|max:500",
            "telephone_user" => "bail|required|min:8|",
            "email_user" => "bail|required|email|max:50|",
            "ville_user" => "bail|required",
            "pays_user" => "bail|required",

        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "redirect_to" => null,
            "title" => "MISE A JOUR DU COMPTE",
            "message" => $validator->errors()->first()
        ]);

        $gestionnaire = User::where("id", $id_gestionnaire)->exists();

        if ($gestionnaire) {

            $gestionnaire = User::findOrFail($id_gestionnaire);
            $gestionnaire->nom_user = $request->nom_user;
            $gestionnaire->prenoms_user = $request->prenoms_user;
            $gestionnaire->email_user = $request->email_user;
            $gestionnaire->telephone_user = $request->telephone_user;
            $gestionnaire->adresse_user = $request->adresse_user;
            $gestionnaire->ville_user = $request->ville_user;
            $gestionnaire->pays_user = $request->pays_user;
            $gestionnaire->prefix_user = $request->prefix_user;
            $gestionnaire->roles_user = $request->roles_user;
            $gestionnaire->status_user =  $request->status_user == true ? '1' : '0';
            // $gestionnaire->hotel_id = $request->hotel_id;




            if ($request->hasfile('image_user')) {

                $destination = 'storage/uploads/' . $gestionnaire->image_user;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $file = $request->file('image_user');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/uploads/', $filename);
                $gestionnaire->image_user = $filename;
            }

            $gestionnaire->update();

            $gestionnaire = new Gestionnairehotel();
            // $gestionnaire->gestionnaire_hotel_id = $gestionnaire_hotel->id;
            $gestionnaire->hotel_id = $request->hotel_id;

            $gestionnaire->update();


            // return response()->json([
            //     "status" => true,
            //     "reload" => false,
            //     "redirect_to" => false,
            //     "title" => "MISE A JOUR DU COMPTE",
            //     "message" => "Le gestionnaire de l'hôtel " . $gestionnaire_hotel->nom_user . " " . $gestionnaire_hotel->prenoms_user . " votre compte a été modifié avec succes"
            // ]);

            return redirect('Admin/gestionnairehotel/')->with('message', 'Le gestionnaire de l\'hôtel modifié avec succès');
        } else {
            return response()->json(
                [
                    "status" => 0,
                    "message" => "Erreur de mise à jour ",

                ],
            );
        }
    }




    public function deleteGestionnaireHotel($id_gestionnaire_hotel)
    {
        $gestionnaire_hotel = User::findOrFail($id_gestionnaire_hotel);
        if ($gestionnaire_hotel) {
            $destination = 'storage/uploads/' . $gestionnaire_hotel->image_user;
            if (File::exists($destination)) {
                File::delete($destination);
            }

            $gestionnaire_hotel->delete();
            // return response()->json([
            //     "status" => true,
            //     "reload" => true,
            //     "title" => "SUPPRESSION DU GESTIONNAIRE D'HOTEL",
            //     "message" => "Le gestionnaire de l'hotel " . $gestionnaire_hotel->nom_user . " " . $gestionnaire_hotel->prenoms_user . " a été bien supprimé dans le système"
            // ],200);
            return redirect('Admin/gestionnairehotel/')->with('message', 'Le Gestionnaire de l\'hotel a été Supprimé avec succès');
        } else {
            // return response()->json([
            //     "status" => false,
            //     "reload" => true,
            //     "title" => "SUPPRESSION DU GESTIONNAIRE D'HOTEL",
            //     "message" => "gestionnairehotel introuvable"
            // ], 404);

            return redirect('Admin/gestionnairehotel/')->with('message', 'Erreur de suppression');
        }
    }
}
