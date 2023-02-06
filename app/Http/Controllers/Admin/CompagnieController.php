<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Compagnie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CompagnieController extends Controller
{

    protected $compagnie;

    public function guard()
    {
        return Auth::guard();
    }


    public function __construct()
    {
        $this->middleware('auth:api');
        $this->compagnie = $this->guard()->user();
    }

    public function roleUser()
    {
        return Auth::user()->roles_user == "Admin";
    }



    public function getCompagnie()
    {

        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Admin") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas un administrateur",
            ]);
        } else {

            $compagnies = DB::table('compagnies')
                ->select('compagnies.*', 'villes.*', 'pays.*',  'users.*')
                ->join('villes', 'villes.id_ville', '=', 'compagnies.ville_id')

                ->join('pays', 'pays.id_pays', '=', 'compagnies.pays_id')
                ->join('users', 'users.id', '=', 'compagnies.user_id')
                ->orderByDesc('compagnies.created_at')
                ->get();




            $compagnies = Compagnie::with("pays")->get();
            $compagnies = Compagnie::with("villes")->get();
            $compagnies = Compagnie::with("users")->get();

            return response()->json(
                [
                    "status" => true,
                    "message" => "LISTE DES COMPAGNIES",
                    "compagnies" => $compagnies
                ],


                200
            );
        }
    }


    public function infoCompagnie(Request $request, $id_compagnie)
    {

        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Admin") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas un administrateur",
            ]);
        } else {


            $compagnies = Compagnie::with("pays")->get();
            $compagnies = Compagnie::with("villes")->get();
            $compagnies = Compagnie::with("users")->get();

            $compagnie = Compagnie::where("id_compagnie", $id_compagnie)->exists();


            if ($compagnie) {

                $info = Compagnie::find($id_compagnie);

                $compagnie = Compagnie::where('id_compagnie', ($id_compagnie))
                    ->select(

                        'compagnies.*',
                        'villes.*',
                        'pays.*',
                        'users.*',
                    )

                    ->join('pays', 'pays.id_pays', '=', 'compagnies.pays_id')
                    ->join('villes', 'villes.id_ville', '=', 'compagnies.ville_id')
                    ->join('users', 'users.id', '=', 'compagnies.user_id')

                    ->first();

                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "title" => "INFO SUR LA COMPAGNIE",
                    "Info sur la compagnie" => $info
                ], 200);
            } else {
                return response()->json(
                    [
                        "status" => false,
                        "reload" => false,
                        "title" => "INFO SUR LA COMPAGNIE",

                        "message" => "Aucune Compagnie trouvé",

                    ],
                    404
                );
            }
        }
    }


    public function storeCompagnie(Request $request)
    {

        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Admin") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas un administrateur",
            ]);
        } 
        
        
        else {


        $messages = [


            //informations sur la compagnie proprement dit

            "ville_id.required" => "La ville de l'hôtel est requise",
            "pays_id.required" => "Le pays de l'hôtel est requis",
            "user_id.required" => "Le gestionnaire de la compagnie est requis",

            "nom_compagnie.required" => "Le nom de la compagnie est requis",
            "description_compagnie.required" => "La description de la compagnie est requise",
            "description_compagnie.max" => "La description de la compagnie est trop longue",

            "image_compagnie.mimes" => "L'image de la compagnie que vous avez selectionnez est invalide",
            "image_compagnie.max" => "La taille de l'image de la compagnie est trop lourde",

            // "prix_estimatif_chambre_hotel.required" => "Le prix estimatif d'une
            // chambre à la compagnie est requis",
            "telephone1_compagnie.required" => "Le numero1 de telephone de la compagnie est 
            requis",
            "telephone1_compagnie.unique" => "Ce numero de telephone existe deja",

            "adresse_compagnie.required" => "L'adresse de la compagnie est requise",
            "email_compagnie.required" => "L'email de la compagnie est requis",
            "email_compagnie.unique" => "Cet email existe deja dans la base",


        ];

        $validator = Validator::make($request->all(), [

            //informations sur l'hôtel proprement dit

            "ville_id" => "bail|required",
            "pays_id" => "bail|required",
            "user_id" => "bail|required",
            "nom_compagnie" => "bail|required|max:500",
            "description_compagnie" => "bail|required",
            "telephone1_compagnie" => "bail|unique:compagnies,telephone1_hotel",
            "telephone2_compagnie" => "bail",
            "adresse_compagnie" => "bail|required",
            "email_compagnie" => "bail|unique:compagnies,email_compagnie",
            // "prix_estimatif_chambre_hotel" => "bail|required",

            "image_compagnie" => "bail|max:2048000",
            "image_compagnie.*" => "bail|mimes:jpeg,jpg,png",



        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "redirect_to" => null,
            "title" => "ENREGISTREMENT DE LA COMPAGNIE",
            "message" => $validator->errors()->first(),
        ]);


        $compagnie = new Compagnie();
        $compagnie->nom_compagnie = $request->nom_compagnie;
        $compagnie->slug_compagnie = Str::slug("Compagnie-" . $request->nom_compagnie);
        $compagnie->description_compagnie = $request->description_compagnie;

        $compagnie->pays_id = $request->pays_id;
        $compagnie->user_id = $request->user_id;
        $compagnie->ville_id = $request->ville_id;

        $compagnie->telephone1_compagnie = $request->telephone1_compagnie;
        $compagnie->telephone2_compagnie = $request->telephone2_compagnie;
        $compagnie->etoile = $request->etoile;
        $compagnie->adresse_compagnie = $request->adresse_compagnie;
        $compagnie->email_compagnie = $request->email_compagnie;

        $compagnie->numero_rccm_compagnie = $request->numero_rccm_compagnie;
        if ($request->hasfile('numero_rccm_compagnie')) {
            $file = $request->file('numero_rccm_compagnie');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('storage/uploads/', $filename);
            $compagnie->numero_rccm_compagnie = $filename;
        }

        $compagnie->numero_cnss_compagnie = $request->numero_cnss_compagnie;
        if ($request->hasfile('numero_cnss_compagnie')) {
            $file = $request->file('numero_cnss_compagnie');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('storage/uploads/', $filename);
            $compagnie->numero_cnss_compagnie = $filename;
        }

        $compagnie->numero_if_compagnie = $request->numero_if_compagnie;
        if ($request->hasfile('numero_if_compagnie')) {
            $file = $request->file('numero_if_compagnie');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('storage/uploads/', $filename);
            $compagnie->numero_if_compagnie = $filename;
        }

        $compagnie->longitude_compagnie = $request->longitude_compagnie;
        $compagnie->latitude_compagnie = $request->latitude_compagnie;
        // $compagnie->prix_estimatif_chambre_compagnie = $request->prix_estimatif_chambre_compagnie;
        $compagnie->status_compagnie =  true ;
        // $compagnie->created_by = Auth::id();

        if ($request->hasfile('image_compagnie')) {
            $file = $request->file('image_compagnie');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('storage/uploads/', $filename);
            $compagnie->image_compagnie = $filename;
        }

        $compagnie->save();




        return response()->json([
            "status" => true,
            "reload" => true,
            "redirect_to" => null,
            "title" => "ENREGISTREMENT DE LA COMPAGNIE",
            "message" => "La Compagnie " . $compagnie->nom_compagnie . " a été ajouté avec succes"
        ]);

    }

    }



    
    public function updateCompagnie(Request $request, $id_compagnie)
    {

        
        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Admin") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas un administrateur",
            ]);
        } 
        
        
        else {




        $messages = [


            //informations sur l'hôtel proprement dit

            "ville_id.required" => "La ville de l'hôtel est requise",
            "pays_id.required" => "Le pays de l'hôtel est requis",
            "user_id.required" => "Le gestionnaire de la compagnie est requis",

            "nom_compagnie.required" => "Le nom de la compagnie est requis",
            "description_compagnie.required" => "La description de la compagnie est requise",
            "description_compagnie.max" => "La description de la compagnie est trop longue",

            "image_compagnie.mimes" => "L'image de la compagnie que vous avez selectionnez est invalide",
            "image_compagnie.max" => "La taille de l'image de la compagnie est trop lourde",

            // "prix_estimatif_chambre_hotel.required" => "Le prix estimatif d'une
            // chambre à la compagnie est requis",
            "telephone1_compagnie.required" => "Le numero1 de telephone de la compagnie est 
            requis",
            "telephone1_compagnie.unique" => "Ce numero de telephone existe deja",

            "adresse_compagnie.required" => "L'adresse de la compagnie est requise",
            "email_compagnie.required" => "L'email de la compagnie est requis",
            "email_compagnie.unique" => "Cet email existe deja dans la base",


        ];



        $validator = Validator::make($request->all(), [


            //informations sur la compagnie proprement dit

           
            "ville_id" => "bail|required",
            "pays_id" => "bail|required",
            "user_id" => "bail|required",
            "nom_compagnie" => "bail|required|max:500",
            "description_compagnie" => "bail|required",
            "telephone1_compagnie" => "bail|unique:compagnies,telephone1_hotel",
            "telephone2_compagnie" => "bail",
            "adresse_compagnie" => "bail|required",
            "email_compagnie" => "bail|unique:compagnies,email_compagnie",
            // "prix_estimatif_chambre_hotel" => "bail|required",

            "image_compagnie" => "bail|max:2048000",
            "image_compagnie.*" => "bail|mimes:jpeg,jpg,png",




        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "redirect_to" => null,
            "title" => "MISE A JOUR DU COMPTE DE LA COMPAGNIE",
            "message" => $validator->errors()->first(),
        ]);

        $compagnie = Compagnie::where("id_compagnie", $id_compagnie)->exists();


        if ($compagnie) {


            $compagnie = Compagnie::findOrFail($id_compagnie);
            $compagnie->nom_compagnie = $request->nom_compagnie;
            $compagnie->slug_compagnie = Str::slug("compagnie-" . $request->nom_compagnie);
            $compagnie->description_compagnie = $request->description_compagnie;
            $compagnie->typehebergement_id = $request->typehebergement_id;
            $compagnie->ville_id = $request->ville_id;
            $compagnie->pays_id = $request->pays_id;
            $compagnie->user_id = $request->user_id;
            $compagnie->telephone1_compagnie = $request->telephone1_compagnie;
            $compagnie->telephone2_compagnie = $request->telephone2_compagnie;
            $compagnie->etoile = $request->etoile;
            $compagnie->email_compagnie = $request->email_compagnie;
            // $compagnie->prix_estimatif_chambre_compagnie = $request->prix_estimatif_chambre_compagnie;
           
            $compagnie->longitude_compagnie = $request->longitude_compagnie;
            $compagnie->latitude_compagnie = $request->latitude_compagnie;
    
            $compagnie->status_compagnie =  $request->status_compagnie == true ? '1' : '0';


            if ($request->hasfile('numero_cnss_compagnie')) {

                $destination = 'storage/uploads/' . $compagnie->numero_cnss_compagnie;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $file = $request->file('numero_cnss_compagnie');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/uploads/', $filename);
                $compagnie->numero_cnss_compagnie = $filename;
            }


            if ($request->hasfile('numero_rccm_compagnie')) {

                $destination = 'storage/uploads/' . $compagnie->numero_rccm_compagnie;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $file = $request->file('numero_rccm_compagnie');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/uploads/', $filename);
                $compagnie->numero_rccm_compagnie = $filename;
            }



            if ($request->hasfile('numero_if_compagnie')) {

                $destination = 'storage/uploads/' . $compagnie->numero_if_compagnie;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $file = $request->file('numero_if_compagnie');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/uploads/', $filename);
                $compagnie->numero_if_compagnie = $filename;
            }




            if ($request->hasfile('image_compagnie')) {

                $destination = 'storage/uploads/' . $compagnie->image_compagnie;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $file = $request->file('image_compagnie');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/uploads/', $filename);
                $compagnie->image_compagnie = $filename;
            }



            $compagnie->update();



            return response()->json([
                "status" => true,
                "reload" => false,
                "redirect_to" => null,
                "title" => "MISE A JOUR DU COMPTE DE LA COMPAGNIE",
                "message" => "La Compagnie " . $compagnie->nom_compagnie . " a été modifiée avec succes"
                 ]);


        } else {
            return response()->json(
                [
                    "status" => false,
                    "message" => "Erreur de mise à jour ",

                ],
            );
        }
    }

    }



    public function deleteCompagnie($id_compagnie)

    {

         
        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Admin") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas un administrateur",
            ]);
        } 
        
        
        else {



        $compagnie = Compagnie::where("id_compagnie", $id_compagnie)
        ->exists();



        if ($compagnie) {   

            $compagnie = Compagnie::findOrFail($id_compagnie);


            $destination = 'storage/uploads/' . $compagnie->image_compagnie;
            if (File::exists($destination)) {
                File::delete($destination);
            }


            $destination = 'storage/uploads/' . $compagnie->numero_cnss_compagnie;
            if (File::exists($destination)) {
                File::delete($destination);
            }


            $destination = 'storage/uploads/' . $compagnie->numero_rccm_compagnie;
            if (File::exists($destination)) {
                File::delete($destination);
            }


            $destination = 'storage/uploads/' . $compagnie->numero_if_compagnie;
            if (File::exists($destination)) {
                File::delete($destination);
            }



            $compagnie->delete();

            return response()->json([
                "status" => true,
                "reload" => true,
                "title" => "SUPPRESSION DE LA COMPAGNIE",
                "message" => "La compagnie " . $compagnie->nom_compagnie . " a été bien supprimé dans le système"

            ]);
        } else {
            return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "SUPPRESSION DE LA COMPAGNIE",
                "message" => "Compte Compagnie introuvable"
            ], 404);

        }
    }
    }

}
