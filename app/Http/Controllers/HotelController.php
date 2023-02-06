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
use Illuminate\Support\Facades\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class HotelController extends Controller
{
    //HotelController dans le dossier Hotel renseigne les informations sur un hôtel visible par
    //l'hôtel (hotel_user) lui même en question

    protected $hotel;

    public function guard()
    {
        return Auth::guard();
    }

    public function roleUser()
    {
        return Auth::user()->roles_user == "Hotel";
    }



    public function __construct()
    {
        $this->middleware('auth:api');
        $this->hotel = $this->guard()->user();
    }



    public function tableauDeBord()
    {

        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Hotel") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas l'accès à un compte hôtel",
            ]);
        } else {


            $hotel_user = Auth::user();
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
                "title" => "TABLEAU DE BORD HOTEL",

                "Informations sur le compte " => $hotel_user->nom_user . " " . $hotel_user->prenoms_user,
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

        if (!$this->guard()->user()) {
            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => route("login"),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas connectés. Veuilez vous connecter.",
            ]);
        } else

        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Hotel") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas l'accès à un compte hôtel",
            ]);
        } else {
            $hotel_user = User::where("id", $id_hotel_user)->exists();

            if ($hotel_user) {

                $info = User::find($id_hotel_user);


                $hotel_user = User::where('id', ($request->id_hotel_user))->orderByDesc('created_at')->first();

                // return response()->json($hotel_user);

                return response()->json(
                    [
                        "status" => 1,
                        "message" => "Informations personnelles de l'Hôtel",
                        "data" => $info

                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        "status" => 0,
                        "message" => "Aucun Hôtel trouvé",

                    ],
                    404
                );
            }
        }
    }


    // fonction pour changer le mot de passe 

    // public function updateMotdepasseCompteHotel(Request $request)
    // {
    //     if (!$this->guard()->user()) {
    //         return response()->json([
    //             "status" => true,
    //             "reload" => true,
    //             "redirect_to" => route("login"),
    //             "title" => "AVERTISSEMENT",
    //             "message" => "Vous n'êtes pas connectés. Veuilez vous connecter.",
    //         ]);
    //     } else

    //     if (Auth::guard()->check() &&  Auth::user()->roles_user != "Hotel") {
    //         return response()->json([
    //             "status" => false,
    //             "reload" => false,
    //             "redirect_to" => route('login'),
    //             "title" => "AVERTISSEMENT",
    //             "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas l'accès à un compte hôtel",
    //         ]);
    //     } else {

    //         $messages = [

    //             "password.required" => "Le mot de passe est requis",
    //             "password.min" => "Le mot de passe est trop court",
    //             "password.same" => "Les mots de passes ne sont pas identiques",

    //         ];



    //         $validator = Validator::make($request->all(), [

    //             "password" => "bail|required|min:8|max:50|same:confirmation_password",

    //         ], $messages);


    //         if ($validator->fails())
    //             return response()->json([
    //                 "status" => false,
    //                 "title" => "CHANGEMENT DE MOT DE PASSE",
    //                 "message" => $validator->errors()->first()
    //             ]);

    //         // loggeduser :données utilisateur enregistré

    //         $loggeduser = $this->guard()->user();
    //         $loggeduser->password = Hash::make($request->password);
    //         $loggeduser->save();


    //         return response()->json([
    //             "status" => true,
    //             "redirect_to" => url('/login'),
    //             "title" => "CHANGEMENT DE MOT DE PASSE",
    //             "message" => "Le mot de passe a été changé avec succès.",
    //         ], 200);
    //     }
    // }

    // fonction pour mettre à jour les informations personnelles du compte 

    public function updateCompteHotel(Request $request)
    {
        if (!$this->guard()->user()) {
            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => route("login"),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas connectés. Veuilez vous connecter.",
            ]);
        } 
        else
        
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
                "nom_user.required" => "Votre nom est requis",
                "nom_user.max" => "Votre nom est trop long",
                "prenoms_user.required" => "Votre prenoms est requis",
                "prenoms_user.max" => "Votre prenom est trop long",
                "adresse_user.required" => "L'adresse est requise",
                "adresse_user.max" => "L'adresse est trop longue",
                "prefix_user.required" => "le code téléphonique de votre est requis",
                "telephone_user.required" => "Votre numéro de telephone est requis",
                "telephone_user.unique" => "Votre numéro de telephone est déjà utilisé et enrégistré dans la base",
                "telephone_user.min" => "Votre numero de telephone est court",
                "telephone_user.regex" => "Votre numero de telephone est invalide",
                // "email_user.required" => "Votre adresse mail est requise",
                // "email_user.email" => "Votre adresse mail est invalide",
                // "email_user.max" => "Votre adresse mail est trop longue",
                // "email_user.unique" => "Votre adresse mail est déjà utilisé et enrégistré dans la base",
                "pays_user.required" => "Votre pays de residence est requis",
                "pays_user.max" => "Votre pays de residence est trop long",
                "ville_user.required" => "Votre ville de residence est requise",
                "ville_user.max" => "Votre ville de residence est trop longue",

            ];

            $validator = Validator::make($request->all(), [
                "nom_user" => "bail|required|max:50",
                "prenoms_user" => "bail|required|max:50",
                "adresse_user" => "bail|required|max:50",
                "prefix_user" => "bail|required|max:5",
                "telephone_user" => "bail|required|min:8|max:10|regex:/^([0-9\s\-\+\(\)]*)$/|unique:users,telephone_user",
                // "email_user" => "bail|required|email|max:50",
                // "email_user" => "bail|required|email|max:50|unique:users,email_user",
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

            $client = $this->guard()->user();

            // $client = User::findOrFail(decodeId($request->id_client));
            // $client = User::findOrFail($id_client);
            $client->nom_user = $request->nom_user;
            $client->prenoms_user = $request->prenoms_user;
            // $client->email_user = $request->email_user;
            $client->prefix_user = $request->prefix_user;
            $client->telephone_user = $request->telephone_user;
            $client->adresse_user = $request->adresse_user;
            $client->pays_user = $request->pays_user;
            $client->ville_user = $request->ville_user;
            $client->roles_user = "Client";

            if ($request->hasfile('image_user')) {

                $destination = 'storage/uploads/' . $client->image_user;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $file = $request->file('image_user');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/uploads/', $filename);
                $client->image_user = $filename;
            }


            $client->update();


            return response()->json([
                "status" => true,
                "reload" => true,
                "title" => "MISE A JOUR DU COMPTE",
                "message" => "Mr/Mlle " . $client->nom_user . " " . $client->prenoms_user . " vos informations personnelles ont été mises à jour avec succès"
            ]);
        }
        //  else {
        //     return response()->json(
        //         [
        //             "status" => false,
        //             "message" => "Erreur de mise à jour des informations personnelles de votre compte ",

        //         ],
        //     );
        // }
    }



    // public function updateProfileHotel(Request $request)
    // {
    //     $messages = [
    //         "nom_user.required" => "Votre nom est requis",
    //         "nom_user.max" => "Votre nom est trop long",
    //         "prenoms_user.required" => "Votre prenom est requis",
    //         "prenoms_user.max" => "Votre prenom est trop long",
    //         "adresse_user.required" => "L'adresse est requise",
    //         "adresse_user.max" => "L'adresse est trop longue",
    //         "telephone_user.required" => "Le numero de telephone est requis",
    //         "telephone_user.min" => "Le numero de telephone est invalide",
    //         "email_user.required" => "Votre adresse mail est requise",
    //         "email_user.email" => "Votre adresse email est invalide",
    //         "email_user.max" => "Votre adresse email est trop longue",
    //         "telephone_user.required" => "Votre numero de telephone est requis",
    //     ];

    //     $validator = Validator::make($request->all(), [

    //         "nom_user" => "bail|required|max:50|unique:users,nom_user",
    //         "prenoms_user" => "bail|required|max:50",
    //         "adresse_user" => "bail|required|max:50",
    //         "telephone_user" => "bail|required|min:8",
    //         "email_user" => "bail|required|unique:users,email_user",

    //     ], $messages);

    //     if ($validator->fails()) return response()->json([
    //         "status" => false,
    //         "reload" => false,
    //         "redirect_to" => null,
    //         "title" => "MISE A JOUR DU COMPTE",
    //         "message" => $validator->errors()->first()
    //     ]);

    //     $hotel_user = User::findOrFail(($request->id_hotel_user));
    //     $hotel_user->nom_user = $request->nom_user;
    //     $hotel_user->prenoms_user = $request->prenoms_user;
    //     $hotel_user->email_user = $request->email_user;
    //     $hotel_user->telephone_user = $request->telephone_user;
    //     $hotel_user->adresse_user = $request->adresse_user;
    //     $hotel_user->pays_user = $request->pays_user;
    //     $hotel_user->prefix_user = $request->prefix_user;

    //     if ($request->hasFile('avatar_user')) {
    //         $image = $request->avatar_user;
    //         $avatar_user_name = time() . '.' . $image->getClientOriginalExtension();
    //         $image->move('storage/uploads/', $avatar_user_name);
    //         $hotel_user->avatar_user = '/storage/uploads/' . $avatar_user_name;
    //     }

    //     $process = $hotel_user->save();

    //     if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Mise à jour du hotel_user " . $hotel_user->nom_user . " " . $hotel_user->prenoms_user . " avec succes dans le système.", Auth::id());
    //     else saveSysActivityLog(SYS_LOG_ERROR, "Echec de mise à jour du hotel_user " . $hotel_user->nom_user . " " . $hotel_user->prenoms_user . " avec succes dans le système.", Auth::id());

    //     return response()->json([
    //         "status" => true,
    //         "reload" => false,
    //         "title" => "MISE A JOUR DU COMPTE",
    //         "message" => "Mr/Mlle " . $hotel_user->nom_user . " " . $hotel_user->prenoms_user . " votre compte a été modifié avec succes"
    //     ]);
    // }



    public function updateMotdepasseCompteHotel(Request $request)
    {

        if (!$this->guard()->user()) {
            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => route("login"),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas connectés. Veuilez vous connecter.",
            ]);
        } else

        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Hotel") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas l'accès à un compte hôtel",
            ]);
        } else

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
            "title" => "MISE A JOUR DU MOT DE PASSE DU COMPTE HOTEL",
            "message" => $validator->errors()->first()
        ]);

        $hotel_user = $this->guard()->user();
        // $hotel_user = User::findOrFail(($request->id_hotel_user));

        if (Hash::check($request->password_old, $hotel_user->password)) {
            if (!Hash::check($request->password_old, Hash::make($request->password_new))) {

                $hotel_user->password = Hash::make($request->password_new);
                $hotel_user->save();

             
                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "redirect_to" => Auth::logout(),
                    "title" => "MISE A JOUR DU MOT DE PASSE DU COMPTE HOTEL",
                    "message" => "Mr/Mlle " . $hotel_user->nom_user . " " . $hotel_user->prenoms_user . " votre mot de passe a été modifié avec succes"
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "reload" => false,
                    "redirect_to" => null,
                    "title" => "MISE A JOUR DU MOT DE PASSE DU COMPTE HOTEL",
                    "message" => "Impossible d'utiliser l'ancien mot de passe comme nouveau mot de passe"
                ]);
            }
        } else {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => null,
                "title" => "MISE A JOUR DU MOT DE PASSE DU COMPTE HOTEL",
                "message" => "Votre ancien mot de passe est incorrect"
            ]);
        }
    }
}



}
