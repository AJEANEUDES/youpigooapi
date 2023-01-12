<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pays;
use App\Models\Societe;
use App\Models\Typehebergement;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Ville;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VilleController extends Controller
{

    protected $ville;


    public function guard()
    {
        return Auth::guard();
    }

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->ville = $this->guard()->user();
    }

    public function roleUser()
    {
        return Auth::user()->roles_user == "Admin";
    }




    public function getVille()
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

            $pays = Pays::where('status_pays', true)->orderByDesc('created_at')->get();
            $villes = Ville::with("pays")->get();

            $villes = Ville::all();


            $villes = DB::table('villes')
                ->select('villes.*', 'users.*', 'pays.*')

                ->join('pays', 'pays.id_pays', '=', 'villes.pays_id')
                ->join('users', 'users.id', '=', 'villes.created_by')
                ->orderByDesc('villes.created_at')
                ->get();


            return response()->json(
                [
                    "status" => true,
                    "reload" => true,
                    "message" => "LISTE DES VILLES",
                    "Villes" =>  $villes
                ],
                200
            );
        }
    }



    public function getSpecVilleType(Request $request)
    {
        $type = Typehebergement::where('typehebergement_id', ($request->typehebergement_id))->get();
        return response()->json($type);
    }

    // public function getSpecVille(Request $request): JsonResponse
    // {
    //     $ville = Ville::find(($request->id_ville));
    //     return response()->json(['data' => $ville]);
    // }


    public function infoVille(Request $request, $id_ville)
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
        
        else
        
        {
            $ville = Ville::with("pays")->get();
            $ville = Ville::where(
                "id_ville",
                $id_ville
            )->exists();

            if ($ville) {

                $info = Ville::find($id_ville);

                $ville = Ville::where('id_ville', ($request->id_ville))
                    ->select(

                        'villes.*',
                        'pays.*',
                        'users.*',
                    )

                    ->join('pays', 'pays.id_pays', '=', 'villes.pays_id')
                    ->join('users', 'users.id', '=', 'villes.created_by')

                    ->first();

                return response()->json([
                    "status" => true,
                    "reload" => false,
                    "title" => "INFO SUR LA VILLE",
                    "Informations sur la ville" => $info
                ], 200);
            } else {
                return response()->json(
                    [
                        "status" => false,
                        "reload" => false,
                        "title" => "INFO SUR LA VILLE",
                        "message" => "Aucune ville n'a été trouvée.",

                    ],
                    404
                );
            }
        }
    }



    public function storeVille(Request $request)
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


            $messages = [

                "pays_id.required" => "Le pays de la ville est requis",
                "nom_ville.required" => "Le nom de la ville est requis",
                "nom_ville.max" => "Le nom de la ville est trop long",
                "nom_ville.unique" => "Cette ville existe déjà dans le système",
                "description_ville.required" => "La description de la ville est requise",


                "image_ville.mimes" => "L'image de la ville que vous avez selectionnez est invalide",
                "image_ville.max" => "La taille de l'image de la ville est trop lourde",

            ];


            $validator = Validator::make($request->all(), [

                "nom_ville" => "bail|required|max:200|unique:villes,nom_ville",
                "description_ville" => "bail|required",
                "pays_id" => "bail|required",

                "image_ville" => "bail|max:2048000",
                "image_ville.*" => "bail|mimes:jpeg,jpg,png",


            ], $messages);

            if ($validator->fails()) return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "ENREGISTREMENT DE LA VILLE",
                "message" => $validator->errors()->first()
            ]);


            $ville = new Ville();
            $ville->nom_ville = $request->nom_ville;
            $ville->slug_ville = Str::slug("ville- " . $request->nom_ville);
            $ville->description_ville = $request->description_ville;
            $ville->status_ville =   true;
            $ville->pays_id = $request->pays_id;
            $ville->created_by = Auth::id();

            if ($request->hasfile('image_ville')) {
                $file = $request->file('image_ville');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/uploads/', $filename);
                $ville->image_ville = $filename;
            }



            $ville->save();


            return response()->json([
                "status" => true,
                "reload" => false,
                "title" => "ENREGISTREMENT DE LA VILLE",
                "message" => "La ville " . $ville->nom_ville . " a été ajoutée avec succes"
            ]);
        }
    }






    public function updateVille(Request $request, $id_ville)

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



            $messages = [

                "nom_ville.required" => "Le nom de la ville est requis",
                "nom_ville.max" => "Le nom de la ville est trop long",
                "description_ville.required" => "La description de la ville est requise",
                "pays_id.required" => "Le pays de la ville est requis",

                "image_ville.mimes" => "L'image de la ville que vous avez selectionnez est invalide",
                "image_ville.max" => "La taille de l'image de la ville est trop lourde",

            ];


            $validator = Validator::make($request->all(), [

                "pays_id" => "bail|required",
                "nom_ville" => "bail|required|max:200",
                "description_ville" => "bail|required",

                "image_ville" => "bail|max:2048000",
                "image_ville.*" => "bail|mimes:jpeg,jpg,png",


            ], $messages);

            if ($validator->fails()) return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "MISE A JOUR DE LA VILLE",
                "message" => $validator->errors()->first()
            ]);


            $ville = Ville::where("id_ville", $id_ville)
                ->exists();

            if ($ville) {


                $ville = Ville::findOrFail($id_ville);
                $ville->nom_ville = $request->nom_ville;
                $ville->slug_ville = Str::slug("ville-" . $request->nom_ville);
                $ville->description_ville = $request->description_ville;
                $ville->status_ville =  $request->status_ville == true ? '1' : '0';
                $ville->pays_id = $request->pays_id;
                $ville->image_ville = $request->image_ville;

                if ($request->hasfile('image_ville')) {

                    $destination = 'storage/uploads/' . $ville->image_ville;
                    if (File::exists($destination)) {
                        File::delete($destination);
                    }

                    $file = $request->file('image_ville');
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move('storage/uploads/', $filename);
                    $ville->image_ville = $filename;
                }


                $ville->update();

                return response()->json([
                    "status" => true,
                    "reload" => false,
                    "title" => "MISE A JOUR DE LA VILLE",
                    "message" => "La ville " . $ville->nom_ville . " a été modifié avec succès"
                ]);
            }
            
            else {

                return response()->json([
                    "status" => false,
                    "reload" => false,
                    "title" => "MISE A JOUR DE LA VILLE",
                    "message" => "Erreur de mise à jour"
                ]);

                
            }
        }
    }


    public function deleteVille(Request $request, $id_ville)
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



        $ville = ville::where("id_ville", $id_ville)
            ->exists();

        if ($ville) {

            $ville = Ville::findOrFail($id_ville);

            $destination = 'storage/uploads/' . $ville->image_ville;

            if (File::exists($destination)) {
                File::delete($destination);
            }

            $ville->delete();


            return response()->json([
                "status" => true,
                "reload" => true,
                "title" => "SUPPRESSION DE LA VILLE",
                "message" => "La ville  " . $ville->nom_ville . "  a été bien supprimée dans le système"
            ]);
        } else {
            return response()->json([
                "status" => false,
                "reload" => true,
                "title" => "SUPPRESSION DE LA VILLE",
                "message" => "Ville introuvable"
            ]);

       
        }
    }

    }
}
