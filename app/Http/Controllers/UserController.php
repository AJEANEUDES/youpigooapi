<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function inscription(Request $request)
    {
        $messages = [
            "nom_user.required" => "Votre nom est requis",
            "nom_user.max" => "Votre nom est trop long",
            "prenoms_user.required" => "Votre prenoms est  requis",
            "prenoms_user.max" => "Votre prenoms est trop long",
            "email_user.required" => "Votre adresse mail est requise",
            "email_user.email" => "Votre adresse mail est invalide",
            "email_user.max" => "Votre adresse mail est trop longue",
            "telephone_user.required" => "Votre numero de telephone est requis",
            "telephone_user.min" => "Votre numero de telephone est court",
            "adresse_user.required" => "Votre adresse de residence est requis",
            "adresse_user.max" => "Votre adresse de residence est trop long",
            "password.required" => "Le mot de passe est requis",
            "password.min" => "Le mot de passe est trop court",
            "password.same" => "Les mots de passes ne sont pas identiques",
        ];

        $validator = Validator::make($request->all(), [
            "nom_user" => "bail|required|max:50|",
            "prenoms_user" => "bail|required|max:50",
            "email_user" => "bail|required|email|max:50|unique:users,email_user",
            "telephone_user" => "bail|required|min:8|unique:users,telephone_user",
            "adresse_user" => "bail|required|max:500",
            "password" => "bail|required|min:8|same:confirmation_password",
        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "INSCRIPTION",
            "message" => $validator->errors()->first()
        ]);

        $client = new User();
        $client->nom_user = $request->nom_user;
        $client->prenoms_user = $request->prenoms_user;
        $client->email_user = $request->email_user;
        $client->telephone_user = $request->telephone_user;
        $client->adresse_user = $request->adresse_user;
        $client->pays_user = $request->pays_user;
        $client->prefix_user = $request->prefix_user;
        $client->roles_user = "Client";
        $client->status_user = true;
        $client->image_user = $request->image_user;
        $client->password = Hash::make($request->password);

        if ($request->hasFile('image_user')) {
            $image = $request->image_user;
            $avatar_user_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $avatar_user_name);
            $client->image_user = '/storage/uploads/' . $avatar_user_name;
        }

        $client->save();


        // dd($client);
        //Enregistrement du systeme de log
        // if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Enregistrement du client " . $client->nom_user . " " . $client->prenoms_user . " avec succes dans le système.", $client->id);
        // else saveSysActivityLog(SYS_LOG_ERROR, "Echec d'enregistrement du client " . $client->nom_user . " " . $client->prenoms_user . " avec succes dans le système.", $client->id);

        return response()->json([
            "status" => true,
            "reload" => false,
            "redirect_to" => url('/'),
            "title" => "INSCRIPTION",
            "message" => "Mr/Mlle " . $client->nom_user . " " . $client->prenoms_user . " votre compte a été crée avec succes"
        ]);
    }

    public function tableauDeBord()
    {
        $client = Auth::user();
        return view('pages.utilisateur.dashbord', compact('client'));
    }

    public function profile()
    {
        $client = Auth::user();
        return view('pages.utilisateur.compte', compact('client'));
    }

    public function updateUtilisateur(Request $request)
    {
        $messages = [
            "nom_user.required" => "Votre nom est requis",
            "nom_user.max" => "Votre nom est trop long",
            "nom_user.unique" => "Ce nom existe deja dans le système",

            "prenoms_user.required" => "Votre prenom est requis",
            "prenoms_user.max" => "Votre prenom est trop long",
            "adresse_user.required" => "L'adresse est requise",
            "adresse_user.max" => "L'adresse est trop longue",
            "telephone_user.required" => "Le numero de telephone est requis",
            "telephone_user.min" => "Le numero de telephone est invalide",
            "telephone_user.unique" => "Ce numéro existe deja dans le système",

            "email_user.required" => "Votre adresse mail est requise",
            "email_user.email" => "Votre adresse mail est invalide",
            "email_user.max" => "Votre adresse mail est trop longue",
            "email_user.unique" => "Cet email existe deja dans le système",

            "password.min" => "Le mot de passe est trop court",
            "password.same" => "Les mots de passes ne sont pas identiques",

            "image_user.required" => "L'image de l'utilisateur est requise",
            "image_user.mimes" => "L'image de l'utilisateur que vous avez selectionnez est invalide",
            "image_user.max" => "L'image de l'utilisateur est trop lourde",
        ];

        $validator = Validator::make($request->all(), [
            "nom_user" => "bail|required|max:50|unique:users,nom_user",
            "prenoms_user" => "bail|required|max:50",
            "adresse_user" => "bail|required|max:50",
            "telephone_user" => "bail|required|min:8|unique:users,telephone_user",
            "email_user" => "bail|required|email|max:50|unique:users,email_user",
            "image_user" => "bail|required",
            "image_user" => "bail|max:2048",
            "image_user.*" => "bail|mimes:jpeg,jpg,png",
        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "redirect_to" => null,
            "title" => "MISE A JOUR DU COMPTE",
            "message" => $validator->errors()->first()
        ]);

        $client = User::findOrFail(($request->id_client));
        $client->nom_user = $request->nom_user;
        $client->prenoms_user = $request->prenoms_user;
        $client->email_user = $request->email_user;
        $client->telephone_user = $request->telephone_user;
        $client->adresse_user = $request->adresse_user;
        $client->pays_user = $request->pays_user;
        $client->prefix_user = $request->prefix_user;
        $client->image_user = $request->image_user;


        if ($request->hasFile('image_user')) {
            $image = $request->image_user;
            $avatar_user_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $avatar_user_name);
            $client->image_user = '/storage/uploads/' . $avatar_user_name;
        }

         $client->save();

        // //Enregistrement du systeme de log
        // if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Mise à jour du client " . $client->nom_user . " " . $client->prenoms_user . " avec succes dans le système.", $client->id);
        // else saveSysActivityLog(SYS_LOG_ERROR, "Echec de mise à jour du client " . $client->nom_user . " " . $client->prenoms_user . " avec succes dans le système.", $client->id);

        return response()->json([
            "status" => true,
            "reload" => false,
            "redirect_to" => route('utilisateur.profile'),
            "title" => "MISE A JOUR DU COMPTE",
            "message" => "Mr/Mlle " . $client->nom_user . " " . $client->prenoms_user . " votre compte a été modifié avec succes"
        ]);
    }

    public function motDePasse()
    {
        $client = Auth::user();
        return view('pages.utilisateur.password', compact('client'));
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

        $client = User::findOrFail(decodeId($request->id_client));

        if (Hash::check($request->password_old, $client->password)) {
            if (!Hash::check($request->password_old, Hash::make($request->password_new))) {

                $client->password = Hash::make($request->password_new);
                $client->save();

                // //Enregistrement du systeme de log
                // if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Mise à jour du mot de passe du client " . $client->nom_user . " " . $client->prenoms_user . " avec succes dans le système.", $client->id);
                // else saveSysActivityLog(SYS_LOG_ERROR, "Echec de mise à jour du mot de passe du client " . $client->nom_user . " " . $client->prenoms_user . " avec succes dans le système.", $client->id);

                return response()->json([
                    "status" => true,
                    "reload" => false,
                    "redirect_to" => url('client/mot-de-passe'),
                    "title" => "MISE A JOUR DU MOT DE PASSE",
                    "message" => "Mr/Mlle " . $client->nom_user . " " . $client->prenoms_user . " votre mot de passe a été modifié avec succes"
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

    public function infoClient(Request $request)
    {
        $client = User::where('id', ($request->id_client))->first();
        return response()->json($client);
    }


    
}
