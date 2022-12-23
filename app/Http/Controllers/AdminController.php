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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Ville;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{

    protected $admin;

    public function guard()
    {
        return Auth::guard();
    }



    public function tableauDeBord()
    {
        /*pour un hôtel, on décompte à la fois, l'utilisateur et l'hôtel. Pour 
         dire qu'un utilisateur est relié à un hôtel afin d'obtenir les renseigenments 
         non seulement sur l'hôtel crée mais aussi l'utilisateur qui l'a crée permettant
         ainsi d'avoir des informations sûres au cas où l'entité" hôtel n'est pas 
         réglémentaire..*/
        // $admins = User::where('roles_user', 'Admin')->orderByDesc('created_at')->get();

        $categoriechambres = Categoriechambre::count();
        $chambres = Chambre::count();
        $images_chambres = Chambre::count();
        $images_hotels = Hotel::count();
        $images_categoriechambres = Categoriechambre::count();
        $images_typehebergements = Typehebergement::count();
        $typehebergements = Typehebergement::count();
        $images_villes = Ville::count();
        $images_pays = Pays::count();
        $hotels = Hotel::count();
        $admins = User::where('roles_user', 'Admin')->count();
        $villes = Ville::count();
        $pays = Pays::count();
        $services = Service::count();
        $clients = User::where('roles_user', 'Client')->count();
        $gestionnaires_hotels = User::where('roles_user', 'Hotel')->count();

        // A mettre au niveau du superadmin loo
        // $admins = User::where('roles_user', 'Admin')->count();

        $reservations = Reservation::where('status_annulation', false)
            ->where('status_reservation', true)
            ->count();

        $factures = Facture::count();

        return view('pages.admin.tableaudebord', compact([
            'images_pays',
            'hotels',
            'gestionnaires_hotels',
            'villes',
            'pays',
            'services',
            'clients',
            'admins',
            'reservations',
            'factures',
            'images_villes',
            'images_typehebergements',
            'typehebergements',
            'images_categoriechambres',
            'images_hotels',
            'images_chambres',
            'chambres',
            'categoriechambres',
            'admins',




        ]));
    }


    public function __construct()
    {
        $this->middleware('auth:api');
        $this->admin = $this->guard()->user();
    }

    public function profileAdmin()
    {
        return view('packages.profiles.admin.profile');
    }



    public function getAdmin()
    {
        $admins = User::where('roles_user', 'Admin')->orderByDesc('created_at')->get();
        // return view('packages.admins.admin', compact('admins'));
        return response()->json($admins);
    }


    public function infoAdmin(Request $request, $id_admin)
    {
        $admin = User::where("id", $id_admin)->exists();

        if ($admin) {

            $info = User::find($id_admin);

            $admin = User::where('roles_user', ($request->roles_user = 'Admin'))->orderByDesc('created_at')->first();
            $admin = User::where('id', ($request->id_admin))->orderByDesc('created_at')->first();
            // return response()->json($admin);

            return response()->json(
                [
                    "status" => 1,
                    "message" => "Administrateur trouvé",
                    "data" => $info

                ],
                200
            );
        } else {
            return response()->json(
                [
                    "status" => 0,
                    "message" => "Aucun administrateur trouvé",

                ],
                404
            );
        }
    }



    public function createAdmin()
    {
        return view('packages.admins.create');
    }



    public function storeAdmin(Request $request)
    {
        $messages = [
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


        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "errors" => $validator->errors(),
            "reload" => false,
            "redirect_to" => null,
            "title" => "ENREGISTREMENT DE L'ADMIN",
            "message" => $validator->errors()->first(),
        ], 400);

        $admin = new User();
        $admin->nom_user = $request->nom_user;
        $admin->prenoms_user = $request->prenoms_user;
        $admin->email_user = $request->email_user;
        $admin->adresse_user = $request->adresse_user;
        $admin->telephone_user = $request->telephone_user;
        $admin->roles_user = "Admin";
        $admin->password = Hash::make($request->password);
        $admin->status_user =  $request->status_user == true;

        if ($request->hasfile('image_user')) {
            $file = $request->file('image_user');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('storage/uploads/', $filename);
            $admin->image_user = $filename;
        }


        $admin->save();


        // return redirect('Admin/administrateur/')->with('message', 'Administrateur Ajoutée avec succès');

        return response()->json([
            "status" => true,
            "reload" => false,
            "redirect_to" => null,
            "title" => "ENREGISTREMENT DE L'ADMIN",
            "message" => "L'admin " . $admin->nom_user . " " . $admin->prenoms_user . " a été ajouté avec succes"
        ]);
    }


    public function showAdmin(User $admin)
    {   
        return $admin;
    }



    public function editAdmin($id_admin)
    {
        $admin = User::find($id_admin);
        return view('packages.admins.edit', compact('admin'));
    }


    public function updateAdmin(Request $request, $id_admin)
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

        $admin = User::where("id", $id_admin)->exists();

        if ($admin) {

            $admin = User::findOrFail($id_admin);
            $admin->nom_user = $request->nom_user;
            $admin->prenoms_user = $request->prenoms_user;
            $admin->email_user = $request->email_user;
            $admin->telephone_user = $request->telephone_user;
            $admin->adresse_user = $request->adresse_user;
            $admin->ville_user = $request->ville_user;
            $admin->pays_user = $request->pays_user;
            $admin->prefix_user = $request->prefix_user;
            $admin->roles_user = $request->roles_user;
            $admin->status_user =  $request->status_user == true ? '1' : '0';




            if ($request->hasfile('image_user')) {

                $destination = 'storage/uploads/' . $admin->image_user;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $file = $request->file('image_user');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/uploads/', $filename);
                $admin->image_user = $filename;
            }

            $admin->update();


            return response()->json([
                "status" => true,
                "reload" => false,
                "redirect_to" => false,
                "title" => "MISE A JOUR DU COMPTE",
                "message" => "Mr/Mlle " . $admin->nom_user . " " . $admin->prenoms_user . " votre compte a été modifié avec succes"
            ]);

            // return redirect('Admin/administrateur/')->with('message', 'Administrateur modifiée avec succès');
        } else {
            return response()->json(
                [
                    "status" => 0,
                    "message" => "Erreur de mise à jour ",

                ],
            );
        }
    }


    public function updateProfileAdmin(Request $request)
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
        ];

        $validator = Validator::make($request->all(), [
            "nom_user" => "bail|required|max:50|unique:users,nom_user",
            "prenoms_user" => "bail|required|max:50",
            "adresse_user" => "bail|required|max:50",
            "telephone_user" => "bail|required|min:8|unique:users,telephone_user",
            "email_user" => "bail|required|email|max:50|unique:users,email_user",
        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "redirect_to" => null,
            "title" => "MISE A JOUR DU COMPTE",
            "message" => $validator->errors()->first()
        ]);

        $admin = User::findOrFail(($request->id_admin));
        $admin->nom_user = $request->nom_user;
        $admin->prenoms_user = $request->prenoms_user;
        $admin->email_user = $request->email_user;
        $admin->telephone_user = $request->telephone_user;
        $admin->adresse_user = $request->adresse_user;
        $admin->pays_user = $request->pays_user;
        $admin->prefix_user = $request->prefix_user;
        $admin->image_user = $request->image_user;

        if ($request->hasFile('avatar_user')) {
            $image = $request->image_user;
            $avatar_user_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $avatar_user_name);
            $admin->image_user = '/storage/uploads/' . $avatar_user_name;
        }

        $admin->save();


        return response()->json([
            "status" => true,
            "reload" => false,
            "title" => "MISE A JOUR DU COMPTE",
            "message" => "Mr/Mlle " . $admin->nom_user . " " . $admin->prenoms_user . " votre compte a été modifié avec succes"
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

        $admin = User::findOrFail(($request->id_admin));

        if (Hash::check($request->password_old, $admin->password)) {
            if (!Hash::check($request->password_old, Hash::make($request->password_new))) {

                $admin->password = Hash::make($request->password_new);
                $admin->save();


                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "redirect_to" => Auth::logout(),
                    "title" => "MISE A JOUR DU MOT DE PASSE",
                    "message" => "Mr/Mlle " . $admin->nom_user . " " . $admin->prenoms_user . " votre mot de passe a été modifié avec succes"
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


    public function deleteAdmin($id_admin)
    {
        $admin = User::findOrFail($id_admin);
        if ($admin) {
            $destination = 'storage/uploads/' . $admin->image_user;
            if (File::exists($destination)) {
                File::delete($destination);
            }

            $admin->delete();
            return response()->json([
                "status" => true,
                "reload" => true,
                "title" => "SUPPRESSION DE L'ADMIN",
                "message" => "L'admin " . $admin->nom_user . " " . $admin->prenoms_user . " a été bien supprimé dans le système"
            ],200);
            return redirect('Admin/administrateur/')->with('message', 'Administrateur Supprimé avec succès');
        } else {
            return response()->json([
                "status" => false,
                "reload" => true,
                "title" => "SUPPRESSION DE L'ADMIN",
                "message" => "Administrateur introuvable; erreur de suppression"
            ], 404);

            return redirect('Admin/administrateur/')->with('message', 'Erreur de suppression');
        }
    }
}
