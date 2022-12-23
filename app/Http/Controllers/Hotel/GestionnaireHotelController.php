<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Categoriechambre;
use App\Models\Chambre;
use App\Models\Facture;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class GestionnaireHotelController extends Controller
{


    public function tableauDeBord()
    {

        $gestionnaire_hotel_id = Auth::id();
        $categoriechambres = Categoriechambre::count();
        $chambres = Chambre::count();
        $images_chambres = Chambre::count();
        $images_hotels = Hotel::count();
        $images_categoriechambres = Categoriechambre::count();
        $services = Service::count();
        $reservations = Reservation::where('status_annulation', false)
            ->where('status_reservation', true)
            ->count();

        $factures = Facture::count();

        return view('pages.gestionnairehotel.tableaudebord', compact([

            'services',
            'reservations',
            'factures',
            'images_categoriechambres',
            'images_hotels',
            'images_chambres',
            'chambres',
            'categoriechambres',


        ]));
    }

   
    public function infoGestionnaireHotel(Request $request)
    {
        $gestionnaire_hotel = User::where('id', ($request->id_gestionnaire_hotel))->orderByDesc('created_at')->first();
        return response()->json($gestionnaire_hotel);
    }


    public function updateProfileGestionnaire(Request $request , $id_gestionnaire_hotel)
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
            "email_user.email" => "Votre adresse mail est invalide",
            "email_user.max" => "Votre adresse mail est trop longue",
            "telephone_user.required" => "Votre numero de telephone est requis",
        ];

        $validator = Validator::make($request->all(), [
            "nom_user" => "bail|required|max:50",
            "prenoms_user" => "bail|required|max:50",
            "adresse_user" => "bail|required|max:50",
            "telephone_user" => "bail|required|min:8|unique:users,telephone_user",
            "email_user" => "bail|required|unique:users,email_user",



        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "redirect_to" => null,
            "title" => "MISE A JOUR DU COMPTE",
            "message" => $validator->errors()->first()
        ]);

        $gestionnaire_hotel = User::where("id", $id_gestionnaire_hotel)->exists();

        if ($gestionnaire_hotel) {


            $gestionnaire_hotel = User::findOrFail($request->id_gestionnaire_hotel);
            $gestionnaire_hotel->nom_user = $request->nom_user;
            $gestionnaire_hotel->prenoms_user = $request->prenoms_user;
            $gestionnaire_hotel->email_user = $request->email_user;
            $gestionnaire_hotel->telephone_user = $request->telephone_user;
            $gestionnaire_hotel->adresse_user = $request->adresse_user;
            $gestionnaire_hotel->pays_user = $request->pays_user;
            $gestionnaire_hotel->ville_user = $request->ville_user;
            $gestionnaire_hotel->prefix_user = $request->prefix_user;
            $gestionnaire_hotel->roles_user = $request->roles_user;
            $gestionnaire_hotel->status_user =  $request->status_user == true ? '1' : '0';




            if ($request->hasfile('image_user')) {

                $destination = 'storage/uploads/' . $gestionnaire_hotel->image_user;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $file = $request->file('image_user');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/uploads/', $filename);
                $gestionnaire_hotel->image_user = $filename;
            }

            $gestionnaire_hotel->save();

            return redirect('Admin/gestionnairehotel/')->with('message', "Gestionnaire de  l'hôtel modifiée avec succès");

            // return response()->json([
            //     "status" => true,
            //     "reload" => false,
            //     "title" => "MISE A JOUR DU COMPTE",
            //     "message" => "Mr/Mlle " . $gestionnaire_hotel->nom_user . " " . $gestionnaire_hotel->prenomS_user . " votre compte a été modifié avec succes"
            // ]);

        } else {
            return response()->json(
                [
                    "status" => 0,
                    "message" => "Erreur de mise à jour ",

                ],
            );
        }
    }


    public function updateMotDePasse(Request $request)
    {
        $messages = [
            "password_old.required" => "L'ancien mot de passe est requis",
            "password_new.required" => "Le nouveau mot de passe est requis",
            "password_new.min" => "Le nouveau mot de passe est trop court",
            "confirmation_password.required" => "La confirmation du nouveau mot de passe est requis",
            "confirmation_password.same" => "Les nouveaux mots de passes ne sont pas identiques",
        ];

        $validator = Validator::make($request->all(), [
            'password_old' => ['bail', 'required'],
            'password_new' => ['bail', 'required', 'string', 'min:8'],
            'confirmation_password' => ['bail', 'required', 'same:password_new'],
        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MISE A JOUR DU MOT DE PASSE",
            "message" => $validator->errors()->first()
        ]);

        $gestionnaire_hotel = User::findOrFail(decodeId($request->id_gestionnaire_hotel));

        if (Hash::check($request->password_old, $gestionnaire_hotel->password)) {
            if (!Hash::check($request->password_old, Hash::make($request->password_new))) {

                $gestionnaire_hotel->password = Hash::make($request->password_new);
               $gestionnaire_hotel->save();

                
                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "redirect_to" => Auth::logout(),
                    "title" => "MISE A JOUR DU MOT DE PASSE",
                    "message" => "Mr/Mlle " . $gestionnaire_hotel->nom_user . " " . $gestionnaire_hotel->prenoms_user . " votre mot de passe a été modifié avec succes"
                ]);



            } else {
                return response()->json([
                    "status" => false,
                    "reload" => false,
                    "redirect_to" => null,
                    "title" => "MISE A JOUR DU MOT DE PASSE",
                    "message" => "Impossible d'utiliser l'ancien mot de passe comme nouveau mot de passe"
                ]);
            }
        } else {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => null,
                "title" => "MISE A JOUR DU MOT DE PASSE",
                "message" => "Votre ancien mot de passe est incorrect"
            ]);
        }
    }




}
