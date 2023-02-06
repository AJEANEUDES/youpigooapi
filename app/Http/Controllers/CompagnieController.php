<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categoriechambre;
use App\Models\Chambre;
use App\Models\Facture;
use App\Models\Hotel;
use App\Models\Pays;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\Typehebergement;
use App\Models\User;
use App\Models\Ville;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class CompagnieController extends Controller
{
    //compagnie user

    protected $compagnie;

    public function guard()
    {
        return Auth::guard();
    }

    public function roleUser()
    {
        return Auth::user()->roles_user == "Compagnie";
    }



    public function __construct()
    {
        $this->middleware('auth:api');
        $this->compagnie = $this->guard()->user();
    }



    public function tableauDeBord()
    {

        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Compagnie") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas l'accès à un compte compagnie",
            ]);
        }
         else {


        $compagnie_user = Auth::user();
        // $hotel_user = User::find(Auth::user()->roles_user="Hotel");
        // $categoriechambres = Categoriechambre::where('categoriechambres.created_by', $user->categoriechambre_id)->count();
        // $categoriechambres = Categoriechambre::where('categoriechambres_created_by', $hotel_user->id)->count();
        // $categoriechambres = Auth::user()->createdby()->count();
        // $categoriechambres = Categoriechambre::where('categoriechambres', $hotel_user->id)->count();
        $categoriechambres = Categoriechambre::count();
        $chambres = Chambre::count();
        $images_chambres = Chambre::count();
        $images_categoriechambres = Categoriechambre::count();
        $services = Service::count();
        $reservations = Reservation::where('status_annulation', false)
            ->where('status_reservation', true)
            ->count();

        $factures = Facture::count();


        return response()->json([
            "status" => true,
            "reload" => true,
            "title" => "TABLEAU DE BORD COMPAGNIE",

            "Informations sur le compte "=>$compagnie_user->nom_user." ".$compagnie_user->prenoms_user,
            'services' => $services,
            'reservations' => $reservations,
            'factures' => $factures,
            'images_categoriechambres' => $images_categoriechambres,
            'images_chambres' => $images_chambres,
            'chambres' => $chambres,
            'categoriechambres' => $categoriechambres,

        ]);

    }

}




    public function infoHotel(Request $request, $id_hotel_user)
    {

        $hotel_user = User::where("id", $id_hotel_user)->exists();

        if ($hotel_user) {

            $info = User::find($id_hotel_user);


            $hotel_user = User::where('id', ($request->id_hotel_user))->orderByDesc('created_at')->first();

            // return response()->json($hotel_user);

            return response()->json(
                [
                    "status" => 1,
                    "message" => "Vos informations personnelles",
                    "data" => $info

                ],
                200
            );
        } else {
            return response()->json(
                [
                    "status" => 0,
                    "message" => "Aucune compagnie trouvée",

                ],
                404
            );
        }
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

        $hotel_user = User::findOrFail(($request->id_hotel_user));
        $hotel_user->nom_user = $request->nom_user;
        $hotel_user->prenoms_user = $request->prenoms_user;
        $hotel_user->email_user = $request->email_user;
        $hotel_user->telephone_user = $request->telephone_user;
        $hotel_user->adresse_user = $request->adresse_user;
        $hotel_user->pays_user = $request->pays_user;
        $hotel_user->prefix_user = $request->prefix_user;

        if ($request->hasFile('avatar_user')) {
            $image = $request->avatar_user;
            $avatar_user_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $avatar_user_name);
            $hotel_user->avatar_user = '/storage/uploads/' . $avatar_user_name;
        }

        $process = $hotel_user->save();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Mise à jour du hotel_user " . $hotel_user->nom_user . " " . $hotel_user->prenoms_user . " avec succes dans le système.", Auth::id());
        else saveSysActivityLog(SYS_LOG_ERROR, "Echec de mise à jour du hotel_user " . $hotel_user->nom_user . " " . $hotel_user->prenoms_user . " avec succes dans le système.", Auth::id());

        return response()->json([
            "status" => true,
            "reload" => false,
            "title" => "MISE A JOUR DU COMPTE",
            "message" => "Mr/Mlle " . $hotel_user->nom_user . " " . $hotel_user->prenoms_user . " votre compte a été modifié avec succes"
        ]);
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

        $hotel_user = User::findOrFail(decodeId($request->id_hotel_user));

        if (Hash::check($request->password_old, $hotel_user->password)) {
            if (!Hash::check($request->password_old, Hash::make($request->password_new))) {

                $hotel_user->password = Hash::make($request->password_new);
                $process = $hotel_user->save();

                //Enregistrement du systeme de log
                if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Mise à jour du mot de passe du client " . $hotel_user->nom_user . " " . $hotel_user->prenoms_user . " avec succes dans le système.");
                else saveSysActivityLog(SYS_LOG_ERROR, "Echec de mise à jour du mot de passe du client " . $hotel_user->nom_user . " " . $hotel_user->prenoms_user . " avec succes dans le système.");

                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "redirect_to" => Auth::logout(),
                    "title" => "MISE A JOUR DU MOT DE PASSE",
                    "message" => "Mr/Mlle " . $hotel_user->nom_user . " " . $hotel_user->prenoms_user . " votre mot de passe a été modifié avec succes"
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
