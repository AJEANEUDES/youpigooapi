<?php

namespace App\Http\Controllers;

use App\Models\Categoriechambre;
use App\Models\Chambre;
use App\Models\Hotel;
use App\Mail\ContactezNous;
use App\Models\Image;
use App\Models\Pays;
use App\Models\Service;
use App\Models\Typehebergement;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Models\Ville;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{



    public function guard()
    {
        return Auth::guard();
    }




    public function accueil()
    {
        $pays = Pays::where('status_pays', true)
            ->select('pays.*')
            ->orderByDesc('pays.created_at')
            ->limit(15)
            ->get();



        $villes = Ville::where('status_ville', true)
            ->select('villes.*', 'pays.*')
            ->join('pays', 'pays.id_pays', '=', 'villes.pays_id')
            ->orderByDesc('villes.created_at')
            ->limit(15)
            ->get();




        $typeheb = Typehebergement::where('status_typehebergement', true)
            ->select('villes.*', 'pays.*', 'typehebergements.*')
            ->join('pays', 'pays.id_pays', '=', 'typehebergements.pays_id')
            ->join('villes', 'villes.id_ville', '=', 'typehebergements.ville_id')
            ->orderByDesc('typehebergements.created_at')
            ->limit(15)
            ->get();



        $hotels = Hotel::where('status_hotel', true)
            ->select(
                'villes.*',
                'pays.*',
                'typehebergements.*',
                'hotels.*'
            )

            ->join('pays', 'pays.id_pays', '=', 'hotels.pays_id')
            ->join('villes', 'villes.id_ville', '=', 'hotels.ville_id')

            ->join(
                'typehebergements',
                'typehebergements.id_typehebergement',
                '=',
                'hotels.typehebergement_id'
            )

            ->orderByDesc('hotels.created_at')
            ->limit(15)
            ->get();



        $catchambres = Categoriechambre::where('status_categoriechambre', true)
            ->select(
                'categoriechambres.*',
                // 'hotels.*'
            )

            // ->join('hotels', 'hotels.id_hotel', '=', 'categoriechambres.hotel    _id')



            ->orderByDesc('categoriechambres.created_at')
            ->limit(15)
            ->get();



        $chambres = Chambre::where('status_chambre', true)
            ->select(
                'villes.*',
                'pays.*',
                'typehebergements.*',
                'categoriechambres.*',
                'hotels.*',
                'chambres.*',
            )

            ->join('pays', 'pays.id_pays', '=', 'chambres.pays_id')
            ->join('villes', 'villes.id_ville', '=', 'chambres.ville_id')

            ->join(
                'typehebergements',
                'typehebergements.id_typehebergement',
                '=',
                'chambres.typehebergement_id'
            )

            ->join(
                'categoriechambres',
                'categoriechambres.id_categoriechambre',
                '=',
                'chambres.categoriechambre_id'
            )

            ->join(
                'hotels',
                'hotels.id_hotel',
                '=',
                'chambres.hotel_id'
            )



            ->orderByDesc('chambres.created_at')
            ->limit(15)
            ->get();






        return view('pages.accueil', compact(
            'pays',
            'villes',
            'typeheb',
            'hotels',
            'catchambres',
            'chambres'
        ));
    }


    // public function reserverChambre()
    // {
    //     $chambres = Chambre::getAllchambres('');
    //     $pays = Pays::where('status_pays', true)->orderByDesc('created_at')->get();
    //     $villes = Ville::where('status_ville', true)->orderByDesc('created_at')->get();
    //     $typehebergements = Typehebergement::where('status_typehebergement', true)->orderByDesc('created_at')->get();
    //     $hotels = Hotel::where('status_hotel', true)->orderByDesc('created_at')->get();
    //     $categoriechambres = Categoriechambre::where('status_categoriechambre', true)->orderByDesc('created_at')->get();

    //     return view('pages.reserverChambre', compact([
    //         'chambres', 'pays', 'hotels', 'typehebergements', 'villes', 'categoriechambres'
    //     ]));
    // }


    //Recherche d'une chambre

    public function fetchDataChambre(Request $request)
    {
        $price_min = $request->price_min;
        $price_max = $request->price_max;

        $nombre_lits_min = $request->nombre_lits_min;
        $nombre_lits_max = $request->nombre_lits_max;

        $nombre_places_min = $request->nombre_places_min;
        $nombre_places_max = $request->nombre_places_max;

        $classe_chambre = $request->classe_chambre;

        $pays = $request->pays;
        $villes = $request->villes;
        $typehebergements = $request->typehebergements;
        $categoriechambres = $request->categoriechambres;
        $hotels = $request->hotels;

        $query = $request->search_chambre;

        if ($request->ajax()) {
            $chambres = Chambre::getAllChambres(
                $query,
                $price_min,
                $price_max,
                $pays,
                $villes,
                $typehebergements,
                $categoriechambres,
                $hotels,
                $nombre_lits_min,
                $nombre_lits_max,
                $nombre_places_min,
                $nombre_places_max,
                $classe_chambre

            );

            return view('pages.data.dataChambre', compact('chambres'))->render();
        }
    }


    //Recherche d'un hotel

    public function fetchDataHotel(Request $request)
    {
        $price_min = $request->price_min;
        $price_max = $request->price_max;

        $etoile_min = $request->etoile_min;
        $etoile_max = $request->etoile_max;

        $date_reservation_min = $request->date_reservation_min;
        $date_reservation_max = $request->date_reservation_max;

        $populaire_hotel = $request->populaire_hotel;

        $pays = $request->pays;
        $villes = $request->villes;
        $typehebergements = $request->typehebergements;

        $query = $request->search_hotel;

        if ($request->ajax()) {
            $hotels = Hotel::getAllHotels(
                $query,
                $price_min,
                $price_max,
                $pays,
                $villes,
                $typehebergements,
                $etoile_min,
                $etoile_max,
                $date_reservation_min,
                $date_reservation_max,
                $populaire_hotel

            );

            return view('pages.data.dataHotel', compact('hotels'))->render();
        }
    }

    //Recherche d'une catégorie de chambre

    public function fetchDataCatChambre(Request $request)
    {

        $libelle = $request->libelle;
        $description = $request->description;

        $hotel = $request->hotel;


        $query = $request->search_catchambre;

        if ($request->ajax()) {
            $catchambres = Hotel::getAllCategorieChambres(
                $query,
                $libelle,
                $description,
                $hotel,

            );

            return view('pages.data.dataCatchambre', compact('catchambres'))->render();
        }
    }

    //Recherche d'un type d'hébergement


    public function fetchDataTypeheb(Request $request)
    {

        $nom = $request->nom;

        $pays = $request->pays;
        $ville = $request->ville;


        $query = $request->search_typeheb;

        if ($request->ajax()) {
            $typehebs = Typehebergement::getAllTypeHebergements(
                $query,
                $nom,
                $pays,
                $ville,

            );

            return view('pages.data.dataTypehebs', compact('Typehebs'))->render();
        }
    }



    //détail de la chambre
    public function viewDetailsChambre(Request $request)
    {

        $chambre = Chambre::where('id_chambre', ($request->id_chambre))

            ->select(
                'chambres.*',
                'hotels.*',
                'villes.*',
                'pays.*',
                'categoriechambres.*',
                'typehebergements.*'
            )

            ->join('hotels', 'hotels.id_hotel', '=', 'chambres.hotel_id')
            ->join('villes', 'villes.id_villes', '=', 'chambres.villes_id')
            ->join('pays', 'pays.id_pays', '=', 'chambres.pays_id')
            ->join(
                'categoriechambres',
                'categoriechambres.id_categoriechambre',
                '=',
                'chambres.categoriechambre_id'
            )

            ->join(
                'typehebergements',
                'typehebergements.id_typehebergement',
                '=',
                'chambres.typehebergement_id'
            )

            ->first();

        $images = Image::where('chambre_id', ($request->id_chambre))->orderByDesc('created_at')->get();
        $image_count = Image::where('chambre_id', ($request->id_chambre))->count();
        // dump($chambre->id_chambre);
        // dump(($request->id_chambre));
        //dd($images);

        $chambres = Chambre::where('status_chambre', true)
            ->where('chambres.hotel_id', $chambre->hotel_id)
            ->where('chambres.categoriechambre_id', $chambre->categoriechambre_id)
            ->select(
                'chambres.*',
                'hotels.*',
                'villes.*',
                'pays.*',
                'categoriechambres.*',
                'typehebergements.*'
            )

            ->join('hotels', 'hotels.id_hotel', '=', 'chambres.hotel_id')
            ->join('villes', 'villes.id_villes', '=', 'chambres.villes_id')
            ->join('pays', 'pays.id_pays', '=', 'chambres.pays_id')
            ->join(
                'categoriechambres',
                'categoriechambres.id_categoriechambre',
                '=',
                'chambres.categoriechambre_id'
            )

            ->join(
                'typehebergements',
                'typehebergements.id_typehebergement',
                '=',
                'chambres.typehebergement_id'
            )

            ->orderByDesc('chambres.created_at')
            ->limit(15)
            ->get();

        Session::put('details_url_previous', $request->url());

        $services = Service::where('status_service', true)->orderByDesc('services.created_at')->get();
        return view('pages.details', compact(['chcategorieambre', 'chambres', 'services', 'images', 'image_count']));
    }

    //détail de l'hôtel (à faire)

    //détail de la catégorie de chambre (à faire)

    //détail du type d'hébergement (à faire)


    //la  fonction d'inscription d'un utilisateur


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
            "email_user.unique" => "Votre adresse mail est déjà utilisé et enrégistré dans la base",
            "prefix_user.required" => "le code téléphonique de votre pays est requis",
            "telephone_user.required" => "Votre numéro de telephone est requis",
            "telephone_user.unique" => "Votre numéro de telephone est déjà utilisé et enrégistré dans la base",
            "telephone_user.min" => "Votre numero de telephone est court",
            "telephone_user.regex" => "Votre numero de telephone est invalide",
            "adresse_user.required" => "Votre adresse de residence est requis",
            "adresse_user.max" => "Votre adresse de residence est trop long",
            "pays_user.required" => "Votre pays de residence est requis",
            "pays_user.max" => "Votre pays de residence est trop long",
            "ville_user.required" => "Votre ville de residence est requise",
            "ville_user.max" => "Votre ville de residence est trop longue",
            "roles_user.required" => "Le Type de votre compte est requis",
            "roles_user.max" => "La valeur du type est trop longue",
            "password.required" => "Le mot de passe est requis",
            "password.min" => "Le mot de passe est trop court",
            "password.same" => "Les mots de passes ne sont pas identiques",
        ];

        $validator = Validator::make($request->all(), [
            "nom_user" => "bail|required|max:50|",
            "prenoms_user" => "bail|required|max:50",
            "email_user" => "bail|required|email|max:50|unique:users,email_user",
            "prefix_user" => "bail|required|min:2|max:10",
            "telephone_user" => "bail|required|min:8|max:10|regex:/^([0-9\s\-\+\(\)]*)$/|unique:users,telephone_user",
            "adresse_user" => "bail|required|max:500",
            "pays_user" => "bail|required|max:500",
            "ville_user" => "bail|required|max:500",
            "roles_user" => "bail|required|max:30",
            "password" => "bail|required|min:8|same:confirmation_password",

        ], $messages);

        //Send failed response if request is not valid



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
        $client->prefix_user = $request->prefix_user;
        $client->telephone_user = $request->telephone_user;
        $client->adresse_user = $request->adresse_user;
        $client->pays_user = $request->pays_user;
        $client->ville_user = $request->ville_user;
        $client->roles_user = $request->roles_user;

        //condition
        if ($request->roles_user == "Client") {
            $client->roles_user = "Client";
        } elseif ($request->roles_user == "Hotel") {
            $client->roles_user = "Hotel";
        } else {
            $client->roles_user = "Compagnie";
        }

        // $client->status_user = true;

        //Générer un code

        $verification_code = Str::random(60);
        // $client->verification_code = sha1(time());
        $client->verification_code = $verification_code;
        $client->password = Hash::make($request->password);

        if ($request->hasfile('image_user')) {
            $file = $request->file('image_user');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('storage/uploads/', $filename);
            $client->image_user = $filename;
        }


        $client->save();

        if ($client != null) {

            //l'envoi de maiil pour la réinitialisation du mot de passe

            //le code suivant marche avec l'exécution de l'envoi de mail(à utiliser)
            // penser à configurer et à modifier les informations concernant l'envoi
            // de mail avec le username_email qu'il faut

            // $verification_code = $client->verification_code;
            // $email_user = $client->email_user;
            // $nom_user = $client->nom_user;
            // $prenoms_user = $client->prenoms_user;

            // Mail::send(
            //     'signup-email',
            //     [
            //         'verification_code' => $verification_code,
            //         'nom_user' => $nom_user,
            //         'prenoms_user' => $prenoms_user,
            //     ],

            //     function (Message $message) use ($email_user) {
            //         // $message->APP_NAME('YOUPIGOO');
            //         $message->subject('INSCRIPTION SUR LE SITE DE YOUPIGOO');
            //         $message->to($email_user);
            //     }
            // );


            // Voir le message de confirmation 
            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => back(),
                "redirect_to" => url("http://127.0.0.1.8000/api/auth/confirmation-email/verificationcode/" . $verification_code),
                "title" => "INSCRIPTION",
                "message" => "Mr/Mlle " . $client->nom_user . " " . $client->prenoms_user . ". Votre compte a été crée avec succes. Vérifier votre adresse mail pour le lien de confirmation."

            ]);
        }

        // Voir le message d'erreur de non envoi 


        return response()->json([
            "status" => true,
            "reload" => true,
            "redirect_to" => back(),
            "title" => "INSCRIPTION",
            "message" => "Une erreur s'est produite. Veuillez réessayer s'il vous plait!"

        ]);
    }


    public function verificationUser($verification_code, Request $request)
    {
        // $verification_code = \Illuminate\Support\Facades\Request::get('verification_code');
        $client = User::where(['verification_code' => $verification_code])->first();

        if ($client != null) {

            $messages = [

                "email_user.required" => "Votre adresse mail est requis",
                // "password.required" => "Le nouveau mot de passe est requis",
                // "password.min" => "Le nouveau mot de passe est trop court",
                // "password.max" => "Le nouveau mot de passe est trop long",
                // "confirmation_password.required" => "La confirmation du mot de passe est requis",
                // "confirmation_password.same" => "La confirmation du mot de passe ne correspond pas"
            ];

            $validator = Validator::make($request->all(), [
                "email_user" => "bail|required|email",
                // "password" => "bail|required|min:8|same:confirmation_password",
                // "confirmation_password" => "required|same:password"

            ], $messages);

            if ($validator->fails()) return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "VERIFICATION DU CODE",
                "message" => $validator->errors()->first()
            ]);

            // $client = User::find($verification_code->user_id);


            if ($client->email_user != $request->email_user) {
                return response()->json([
                    "status" => false,
                    "reload" => false,
                    "redirect_to" => null,
                    "title" => "VERIFICATION DU CODE ET DU COMPTE",
                    "message" => "Votre adresse mail est incorrecte."
                ]);
            } else {

                $client->status_user = 1;
                $client->save();

                // Voir le message de confirmation 
                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "redirect_to" => route("login"),
                    "title" => "VERIFICATION DU CODE",
                    "message" => "Votre compte a été vérifié. Vous pouvez maintenant vous y connecter!"

                ]);
            }
        }

        return response()->json([
            "status" => true,
            "reload" => true,
            "redirect_to" => back(),
            "title" => "VERIFICATION DU CODE",
            "message" => "Code de vérification invalide"

        ]);
    }

    //la fonction de génération aléatoire du token

    public function respondWithToken($token)
    {


        return response()->json(
            [
                "title" => "CONNEXION",
                "message" => "Connexion réussie.Vous vous êtes connecté(e) avec succès.",
                'token' => $token,
                'token_type' => 'bearer',
                'token_validity' => $this->guard()->factory()->getTTL() * 60
            ]
        );
    }




    //la  fonction de connexion d'un utilisateur


    public function connexion(Request $request)

    {

        $messages = [

            "email_user.required" => "Votre adresse mail est requise",
            "email_user.email" => "Votre adresse mail est invalide",
            "password.required" => "Le mot de passe est requis",
            "password.min" => "Le mot de passe est trop court",
        ];

        $validator = Validator::make($request->all(), [

            "email_user" => "bail|required|email|max:100|",
            "password" => "bail|required|min:8|max:50",

        ], $messages);

        if ($validator->fails()) {

            return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "CONNEXION",
                "message" => $validator->errors()->first()
            ]);
        }

        //validité du token(1j = 24h et 1h =60min donc token_validity = 24*60)

        $token_validity = 60;

        $this->guard()->factory()->setTTL($token_validity);
        // $client = User::where(['status_user' => $status_user])->first();
       
        //condition de fonctionnement

        if (!$token = $this->guard()->attempt($validator->validated()) )
         {
            return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "CONNEXION",
                'erreur' => "Adresse Email ou mot de passe incorrect"

            ], 401);
        }




        return $this->respondWithToken($token);


        //Vérifier si le client existe

        // $client = User::where('email_user', '=', $request->email_user)->first();

        // if ($client) {

        //     if (Hash::check($request->password, $client->password)) {

        //         //créer un jeton ou un token

        //         $token = $client->createToken('auth_token')->plainTextToken;

        //         //réponse
        //         // return response()->json([
        //         //     "status" => 1,
        //         //     "message" => "Connexion Réussie",
        //         //     "acces_token" => $token
        //         // ], 200);


        //         return response()->json([
        //             "status" => true,
        //             "reload" => false,
        //             "redirect_to" => null,
        //             'acces_token' => $token,
        //             "title" => "CONNEXION",
        //             "message" => "Mr/Mlle " . $client->nom_user . " " . $client->prenoms_user . ", vous vous êtes connectés avec succes"
        //         ], 200);
        //     } else {
        //         //réponse
        //         return response()->json([
        //             "status" => 0,
        //             "message" => "Mot de Passe incorrect"
        //         ]);
        //     }
        // } else {

        //     //réponse
        //     return response()->json([
        //         "status" => 0,
        //         "message" => "Utilisateur n'existe pas ou est introuvable"
        //     ], 404);
        // }

    }


    //la  fonction de déconnexion d'un utilisateur

    public function logout()
    {

        $this->guard()->logout();

        // $client = Auth::user()->tokens()->delete();

        return response()->json([
            "status" => true,
            "reload" => true,
            "redirect_to" => null,
            "title" => "DECONNEXION",
            "message" => 'Vous vous êtes déconnectés avec succès',
        ]);
    }

    // public function loggedUser()

    // {
    //     if (!$this->guard()->user()) {
    //         return response()->json([
    //             "status" => true,
    //             "reload" => true,
    //             "redirect_to" => route("login"),
    //             "title" => "AVERTISSEMENT",
    //             "message" => "Voous n'êtes pas connectés. Veuilez vous connecter.",
    //         ]);
    //     } 
    //     else {
    //         $loggeduser = Auth()->user();

    //         return response()->json(
    //             [
    //                 "status" => "Success",
    //                 "title" => "Données Utilisateur enrégistrées",
    //                 "Utilisateur" => $loggeduser

    //             ],
    //         200);
    //     }
    // }

    //profile de l'utilisateur

    public function profile()
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

            return response()->json(
                [
                    "status" => true,
                    "reload" => true,
                    "title" => "INFORMATIONS SUR LE PROFIL DU CLIENT",
                    "Informations" => $this->guard()->user()

                ]
            );
    }


    //rafraichir pour regénerer un token pour un utilisateur 
    //connecté

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    // fonction pour changer le mot de passe 

    public function updateMotdepasse(Request $request)
    {
        if (!$this->guard()->user()) {
            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => route("login"),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas connectés. Veuilez vous connecter.",
            ]);
        } else {

            $messages = [

                "password.required" => "Le mot de passe est requis",
                "password.min" => "Le mot de passe est trop court",
                "password.same" => "Les mots de passes ne sont pas identiques",

            ];



            $validator = Validator::make($request->all(), [

                "password" => "bail|required|min:8|max:50|same:confirmation_password",

            ], $messages);


            if ($validator->fails())
                return response()->json([
                    "status" => false,
                    "title" => "CHANGEMENT DE MOT DE PASSE",
                    "message" => $validator->errors()->first()
                ]);

            // loggeduser :données utilisateur enregistré

            $loggeduser = $this->guard()->user();
            $loggeduser->password = Hash::make($request->password);
            $loggeduser->save();


            return response()->json([
                "status" => true,
                "redirect_to" => url('/login'),
                "title" => "CHANGEMENT DE MOT DE PASSE",
                "message" => "Le mot de passe a été changé avec succès.",
            ], 200);
        }
    }

    // fonction pour mettre à jour les informations personnelles du compte 

    public function updateCompte(Request $request)
    {
        if (!$this->guard()->user()) {
            return response()->json([
                "status" => true,
                "reload" => true,
                "redirect_to" => route("login"),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas connectés. Veuilez vous connecter.",
            ]);
        } else {
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

    // public function profileClient(Request $request)
    // {


    //     $client = User::where('id', ($request->id_client))->orderByDesc('created_at')->first();

    //     return response()->json(
    //         [
    //             "status" => true,
    //             "reload" => false,
    //             "redirect_to" => null,
    //             "title" => "INFORMATIONS SUR LE PROFIL DU CLIENT",
    //             "message" => "Informations sur le profil du client " . $client->nom_user . " " . $client->prenoms_user . "",

    //         ],

    //         200

    //     );

    //     // return response()->json(
    //     //     [
    //     //         "status" => 1,
    //     //         "message" => "Informations sur le profil du client",
    //     //         "datas" => $client = Auth::user()
    //     //     ],
    //     //     200
    //     // );


    // }



}
