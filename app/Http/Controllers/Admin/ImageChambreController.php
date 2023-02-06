<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chambre;
use App\Models\Gestionnairesociete;
use App\Models\Hotel;
use App\Models\Image;
use App\Models\Societe;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ImageChambreController extends Controller
{


    // LA GESTION DES IMAGES DES CHAMBRES
    // LA GESTION DES IMAGES DES CHAMBRES
    // LA GESTION DES IMAGES DES CHAMBRES
    // LA GESTION DES IMAGES DES CHAMBRES
    // LA GESTION DES IMAGES DES CHAMBRES

    protected $image_chambre;

    public function guard()
    {
        return Auth::guard();
    }


    public function __construct()
    {
        $this->middleware('auth:api');
        $this->image_chambre = $this->guard()->user();

    }

    public function roleUser()
    {
        return Auth::user()->roles_user == "Admin";
    }



    public function getImageChambre()
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



            $chambres = Chambre::where('status_chambre', true)
                ->select(
                    'chambres.*',
                    'categoriechambres.*',
                    'hotels.*',
                    'typehebergements.*',
                    'villes.*',
                    'pays.*'
                )

                ->join(
                    'categoriechambres',
                    'categoriechambres.id_categoriechambre',
                    '=',
                    'chambres.categoriechambre_id'
                )

                ->join('hotels', 'hotels.id_hotel', '=', 'chambres.hotel_id')
                ->join('villes', 'villes.id_ville', '=', 'chambres.ville_id')
                ->join('pays', 'pays.id_pays', '=', 'chambres.pays_id')
                ->join(
                    'typehebergements',
                    'typehebergements.id_typehebergement',
                    '=',
                    'chambres.typehebergement_id'
                )

                ->orderByDesc('chambres.created_at')
                ->get();

            $image_chambre = Image::select(

                'images.*',
                'users.*',
                'chambres.*',
                'categoriechambres.*',
                'hotels.*',
                'typehebergements.*',
                'villes.*',
                'pays.*'

            )
            ->join('chambres', 'chambres.id_chambre', '=', 'images.chambre_id')
                ->join(
                    'categoriechambres',
                    'categoriechambres.id_categoriechambre',
                    '=',
                    'images.categoriechambre_id'
                )

                ->join(
                    'typehebergements',
                    'typehebergements.id_typehebergement',
                    '=',
                    'images.typehebergement_id'
                )

                ->join('hotels', 'hotels.id_hotel', '=', 'images.hotel_id')
                ->join('villes', 'villes.id_ville', '=', 'images.ville_id')
                ->join('pays', 'pays.id_pays', '=', 'images.pays_id')
              
                ->join('users', 'users.id', '=', 'images.created_by')
                ->orderByDesc('images.created_at')
                ->get();


            return response()->json(
                [
                    "status" => true,
                    "message" => "LISTE DES IMAGES DE CHAMBRE",
                    "Chambres" =>  $chambres,
                    "images de chambres" =>  $image_chambre
                ],
                200
            );
        }
    }

    public function infoImageChambre(Request $request, $id_image_chambre)
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

            $image_chambre = Image::where("id_image", $id_image_chambre)->exists();

            if ($image_chambre) {

                $info = Image::find($id_image_chambre);

                $image_chambre = Image::where('id_image', ($id_image_chambre))
                    ->select(

                        'images.*',
                        'chambres.*',
                        'users.*',
                        'categoriechambres.*',
                        'hotels.*',
                        'typehebergements.*',
                        'pays.*',
                        'villes.*'
                    )
                    ->join('chambres', 'chambres.id_chambre', '=', 'images.chambre_id')
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

                    ->join('users', 'users.id', '=', 'images.created_by')
                    ->join('hotels', 'hotels.id_hotel', '=', 'chambres.hotel_id')
                    ->orderByDesc('images.created_at')
                    ->first();


                return response()->json(
                    [
                        "status" => true,
                        "reload" => true,
                        "title" => "INFO SUR LA CATEGORIE DE CHAMBRE",
                        "message" => "Image trouvée",
                        "image de la chambre" => $info

                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        "status" => 0,
                        "message" => "Aucune image de chambre trouvé",

                    ],
                    404
                );
            }
        }
    }



    public function storeImageChambre(Request $request)
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
                "chambre.required" => "Veuillez selectionnez une chambre, s'il vous plait",
                "imageFile.required" => "L'image de la chambre est requise",
                "imageFile.mimes" => "L'image de la chambre que vous avez selectionnez est invalide",
                "imageFile.max" => " La taille de l'image de la chambre est trop lourde",
            ];

            $validator = Validator::make($request->all(), [
                "chambre" => "bail|required",
                "imageFile" => "bail|required|mimes:jpeg,jpg,png|max:2048",
                "imageFile" => "bail|max:2048000",
                "imageFile.*" => "bail|mimes:jpeg,jpg,png",

            ], $messages);

            if ($validator->fails()) return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "ENREGISTREMENT DE L'IMAGE DE LA CHAMBRE",
                "message" => $validator->errors()->first(),
            ]);


            $chambre = Chambre::where('id_chambre', $request->chambre)->first();

            if ($request->hasFile('path_image')) {
                foreach ($request->file('path_image') as $file) {
                    $name = $file->getClientOriginalName();
                    $file->move('storage/uploads', $name);

                    $finalImage = new Image();
                    $finalImage->pays_id = $request->pays;
                    $finalImage->ville_id = $request->ville;
                    $finalImage->hotel_id = $request->hotel;
                    $finalImage->typehebergement_id = $request->typehebergement;
                    $finalImage->categoriechambre_id = $request->categoriechambre;
                    $finalImage->chambre_id = $request->chambre;
                    $finalImage->hotel_id = $chambre->hotel_id;
                    $finalImage->created_by = Auth::id();
                    $finalImage->path_image = '/storage/uploads/' . $name;

                    $finalImage->save();
                }


                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "title" => "ENREGISTREMENT DE L'IMAGE DE LA CHAMBRE",
                    "message" => "L'image de chambre a été ajoutée avec succes"
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "reload" => false,
                    "title" => "ENREGISTREMENT DE L'IMAGE DE LA CHAMBRE",
                    "message" => "Une erreur s'est produite"
                ]);
            }
        }
    }



    public function updateImageChambre(Request $request, $id_image)
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
                "image_chambre.required" => "L'image de la chambre est requise",
                "image_chambre.mimes" => "L'image de la chambre que vous avez selectionnez est invalide",
                "image_chambre.max" => "La taille de l'image de la chambre est trop lourde",
            ];

            $validator = Validator::make($request->all(), [
                "image_chambre" => "bail|required|mimes:jpeg,jpg,png|max:2048000",
                "image_chambre" => "bail|max:2048000",
                "image_chambre.*" => "bail|mimes:jpeg,jpg,png",
            ], $messages);

            if ($validator->fails()) return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "MISE A JOUR DE L'IMAGE",
                "message" => $validator->errors()->first(),
            ]);


            $image = Image::where("id_image", $id_image)->exists();

            if ($image) {


                $image = Image::findOrFail($id_image);

                if ($request->hasFile('image_chambre')) {
                    $destination = 'storage/uploads/' . $image->image_chambre;
                    if (File::exists($destination)) {
                        File::delete($destination);
                    }

                    $image_ch = $request->image_chambre;
                    $chambre_new_name = time() . '.' . $image_ch->getClientOriginalExtension();
                    $image_ch->move('storage/uploads/', $chambre_new_name);
                    $image->path_image = '/storage/uploads/' . $chambre_new_name;
                }

                $image->update();


                return response()->json([
                    "status" => true,
                    "reload" => false,
                    "title" => "MISE A JOUR DE L'IMAGE",
                    "message" => "L'image de la chambre a été modifiée avec succes"
                ]);
            } else {
                return response()->json(
                    [
                        "status" => false,
                        "title" => "MISE A JOUR DE L'IMAGE",
                        "message" => "Erreur de mise à jour ",

                    ],
                    404
                );
            }
        }
    }

    public function deleteImageChambre(Request $request, $id_image)
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


        $image = Image::where("id_image", $id_image)->exists();

        if ($image) {

            $image = Image::findOrFail($id_image);


            if ($request->hasFile('image_chambre')) {
                $destination = 'storage/uploads/' . $image->image_chambre;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $image_ch = $request->image_chambre;
                $chambre_new_name = time() . '.' . $image_ch->getClientOriginalExtension();
                $image_ch->move('storage/uploads/', $chambre_new_name);
                $image->path_image = '/storage/uploads/' . $chambre_new_name;
            }

            // $cheminFinale = $image->path_image;
            // unlink(public_path($cheminFinale));
            $image->delete();


            return response()->json([
                "status" => true,
                "reload" => true,
                "title" => "SUPPRESSION DE L'IMAGE",
                "message" => "L'image de la chambre a été bien supprimée dans le système"
            ]);
        } else {
            return response()->json(
                [
                    "status" => false,
                    "message" => "Image introuvable",

                ],
                404
            );
        }
    }
    
    }






    // LA GESTION DES IMAGES DES HOTELS
    // LA GESTION DES IMAGES DES HOTELS
    // LA GESTION DES IMAGES DES HOTELS
    // LA GESTION DES IMAGES DES HOTELS
    // LA GESTION DES IMAGES DES HOTELS









    public function getImageHotel()
    {


        $hotels = Hotel::where('status_hotel', true)

            ->select(
                'hotels.*',
                'villes.*',
                'pays.*',
                'typehebergements.*',
            )
            ->join('villes', 'villes.id_ville', '=', 'hotels.ville_id')
            ->join('pays', 'pays.id_pays', '=', 'hotels.pays_id')
            ->join(
                'typehebergements',
                'typehebergements.id_typehebergement',
                '=',
                'hotels.typehebergement_id'
            )

            ->orderByDesc('hotels.created_at')
            ->get();



        $images = Image::select(
            'images.*',
            'users.*',
            'hotels.*',
            'typehebergements.*',
            'villes.*',
            'pays.*',
        )

            ->join('hotels', 'hotels.id_hotel', '=', 'images.hotel_id')

            ->join(
                'typehebergements',
                'typehebergements.id_typehebergement',
                '=',
                'hotels.typehebergement_id'
            )

            ->join(
                'villes',
                'villes.id_ville',
                '=',
                'hotels.ville_id'
            )

            ->join(
                'pays',
                'pays.id_pays',
                '=',
                'hotels.pays_id'
            )

            ->join('users', 'users.id', '=', 'images.created_by')
            ->orderByDesc('images.created_at')
            ->get();

        return view('packages.images.hotel.image', compact(['hotels', 'images']));

        return response()->json(
            [
                "status" => 1,
                "message" => "Liste des Images d'hôtels",
                "data" => $hotels, $images
            ],
            200
        );
    }



    public function infoImageHotel(Request $request)
    {
        $image = Image::where('id_image', ($request->id_image))
            ->select(
                'images.*',
                'users.*',
                'hotels.*',
                'typehebergements.*',
                'pays.*',
                'villes.*'
            )
            ->join('hotels', 'hotels.id_hotel', '=', 'images.hotel_id')
            ->join('pays', 'pays.id_pays', '=', 'hotels.pays_id')
            ->join('villes', 'villes.id_ville', '=', 'hotels.ville_id')

            ->join(
                'typehebergements',
                'typehebergements.id_typehebergement',
                '=',
                'hotels.typehebergement_id'
            )


            ->join('users', 'users.id', '=', 'images.created_by')
            ->orderByDesc('images.created_at')
            ->first();
        return response()->json($image);
    }



    public function storeImageHotel(Request $request)
    {
        $messages = [
            "hotel.required" => "Veuillez selectionnez un hotel, s'il vous plait",
            "imageFile.required" => "L'image de l' hotel est requise",
            "imageFile.mimes" => "L'image de l' hotel que vous avez selectionnez est invalide",
            "imageFile.max" => " La taille de l'image de l' hotel est trop lourde",
        ];

        $validator = Validator::make($request->all(), [
            "hotel" => "bail|required",
            "imageFile" => "bail|required|mimes:jpeg,jpg,png|max:2048",
            "imageFile" => "bail|max:2048000",
            "imageFile.*" => "bail|mimes:jpeg,jpg,png",

        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "ENREGISTREMENT DE L'IMAGE",
            "message" => $validator->errors()->first(),
        ]);


        $hotel = Hotel::where('id_hotel', $request->hotel)->first();


        if ($request->hasFile('path_image')) {
            foreach ($request->file('path_image') as $file) {
                $name = $file->getClientOriginalName();
                $file->move('storage/uploads', $name);

                $finalImage = new Image();
                $finalImage->pays_id = $request->pays;
                $finalImage->ville_id = $request->ville;
                $finalImage->hotel_id = $request->hotel;
                $finalImage->typehebergement_id = $request->typehebergement;
                $finalImage->created_by = Auth::id();
                $finalImage->path_image = '/storage/uploads/' . $name;

                $finalImage->save();
            }

            //Enregistrement du systeme de log

            return response()->json([
                "status" => true,
                "reload" => true,
                "title" => "ENREGISTREMENT DE L'IMAGE",
                "message" => "L'image de l' hotel a été ajoutée avec succes"
            ]);
        } else {
            return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "ENREGISTREMENT DE l'HOTEL",
                "message" => "Une erreur s'est produite"
            ]);
        }
    }




    public function updateImageHotel(Request $request)
    {
        $messages = [
            "image_hotel.required" => "L'image de l' hotel est requise",
            "image_hotel.mimes" => "L'image de l' hotel que vous avez selectionnez est invalide",
            "image_hotel.max" => "La taille de l'image de l' hotel est trop lourde",
        ];

        $validator = Validator::make($request->all(), [
            "image_hotel" => "bail|required|mimes:jpeg,jpg,png|max:2048000",
            "image_hotel" => "bail|max:2048000",
            "image_hotel.*" => "bail|mimes:jpeg,jpg,png",
        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MISE A JOUR DE L'IMAGE",
            "message" => $validator->errors()->first(),
        ]);

        $image = Image::findOrFail($request->id_image);

        if ($request->hasFile('image_hotel')) {
            $image_hotel = $request->image_hotel;
            $hotel_new_name = time() . '.' . $image_hotel->getClientOriginalExtension();
            $image_hotel->move('storage/uploads/', $hotel_new_name);
            $image->path_image = '/storage/uploads/' . $hotel_new_name;
        } else {
            $image_hotel = $request->image_hotel;
            $hotel_new_name = time() . '.' . $image_hotel->getClientOriginalExtension();
            $image_hotel->move('storage/uploads/', $hotel_new_name);
            $image->path_image = '/storage/uploads/' . $hotel_new_name;
        }

        $image->save();

        return response()->json([
            "status" => true,
            "reload" => false,
            "title" => "MISE A JOUR DE L'IMAGE",
            "message" => "L'image de l' hotel a été modifiée avec succes"
        ]);

        // else{
        //     return response()->json([
        //         "status" => true,
        //         "reload" => false,
        //         "title" => "MISE A JOUR DE L'IMAGE",
        //         "message" => "une erreur s'est produite"
        //     ]);
        // }
    }




    public function deleteImageHotel(Request $request)
    {
        $image = Image::findOrFail($request->id_image);
        $cheminFinale = $image->path_image;
        unlink(public_path($cheminFinale));
        $image->delete();

        //Enregistrement du systeme de log
        // if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression de l'image de l'hotel dans le système", Auth::id());
        // else saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression de l'image de l'hotel dans le système", Auth::id());

        return response()->json([
            "status" => true,
            "reload" => true,
            "title" => "SUPPRESSION DE L'IMAGE",
            "message" => "L'image de l'hotel a été bien supprimée dans le système"
        ]);
    }




    // LA GESTION DES IMAGES DES CATEGORIES DE CHAMBRE
    // LA GESTION DES IMAGES DES CATEGORIES DE CHAMBRE
    // LA GESTION DES IMAGES DES CATEGORIES DE CHAMBRE
    // LA GESTION DES IMAGES DES CATEGORIES DE CHAMBRE
    // LA GESTION DES IMAGES DES CATEGORIES DE CHAMBRE





    public function getImageCategoriechambre()
    {
        $gestionnaire_hotel_id = Auth::id();
        $societe = Gestionnairesociete::where('gestionnaire_hotel_id', $gestionnaire_hotel_id)->first();



        $images = Image::select(
            'images.*',
            'users.*',
            'categoriechambres.*',
        )

            ->where('images.societe_id', $societe->societe_id)



            ->join('users', 'users.id', '=', 'images.created_by')
            ->orderByDesc('images.created_at')
            ->get();

        return view('packages.images.categoriechambre.image', compact(['categoriechambres', 'images']));
    }



    public function infoImageCategoriechambre(Request $request)
    {
        $image = Image::where('id_image', ($request->id_image))
            ->select(
                'images.*',
                'users.*',
                'categoriechambres.*',
            )
            ->join(
                'categoriechambres',
                'categoriechambres.id_categoriechambre',
                '=',
                'images.categoriechambre_id'
            )


            ->join('users', 'users.id', '=', 'images.created_by')
            ->orderByDesc('images.created_at')
            ->first();
        return response()->json($image);
    }




    public function storeImageCategoriechambre(Request $request)
    {
        $messages = [
            "categoriechambre.required" => "Veuillez selectionnez une catégorie de chambre, s'il vous plait",
            "imageFile.required" => "L'image de la catégorie de chambre est requise",
            "imageFile.mimes" => "L'image de la catégorie de chambre que vous avez selectionnez est invalide",
            "imageFile.max" => " La taille de l'image de la catégorie de chambre est trop lourde",
        ];

        $validator = Validator::make($request->all(), [
            "categoriechambre" => "bail|required",
            "imageFile" => "bail|required|mimes:jpeg,jpg,png|max:2048",
            "imageFile" => "bail|max:2048000",
            "imageFile.*" => "bail|mimes:jpeg,jpg,png",

        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "ENREGISTREMENT DE L'IMAGE",
            "message" => $validator->errors()->first(),
        ]);


        $gestionnaire_hotel_id = Auth::id();
        $societe = GestionnaireSociete::where('gestionnaire_hotel_id', $gestionnaire_hotel_id)->first();

        $societes = Societe::where('id_societe', $societe->societe_id)->where('status_societe', true)->get();


        foreach ($societes as $item) {
            $id_societe = $item->id_societe;
        }

        if ($request->hasFile('imageFile')) {
            foreach ($request->file('imageFile') as $file) {
                $name = $file->getClientOriginalName();
                $file->move('storage/images', $name);

                $finalImage = new Image();
                $finalImage->hotel_id = $request->hotel;
                $finalImage->created_by = Auth::id();
                $finalImage->path_image = '/storage/images/' . $name;

                $process = $finalImage->save();
            }

            //Enregistrement du systeme de log
            if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Enregistrement de l'image de la catégorie de chambre avec succes dans le système.", Auth::id());
            else saveSysActivityLog(SYS_LOG_ERROR, "Echec d'enregistrement de l'image de la catégorie de chambre avec succes dans le système.", Auth::id());

            return response()->json([
                "status" => $process,
                "reload" => true,
                "title" => "ENREGISTREMENT DE L'IMAGE",
                "message" => "L'image de la catégorie de chambre a été ajoutée avec succes"
            ]);
        } else {
            return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "ENREGISTREMENT DE lA CATEGORIE DE CHAMBRE",
                "message" => "Une erreur s'est produite"
            ]);
        }
    }




    public function updateImageCategoriechambre(Request $request)
    {
        $messages = [
            "image_categoriechambre.required" => "L'image de la catégorie de chambre est requise",
            "image_categoriechambre.mimes" => "L'image de la catégorie de chambre que vous avez selectionnez est invalide",
            "image_categoriechambre.max" => "La taille de l'image de la catégorie de chambre est trop lourde",
        ];

        $validator = Validator::make($request->all(), [
            "image_categoriechambre" => "bail|required|mimes:jpeg,jpg,png|max:2048000",
            "image_categoriechambre" => "bail|max:2048000",
            "image_categoriechambre.*" => "bail|mimes:jpeg,jpg,png",
        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MISE A JOUR DE L'IMAGE",
            "message" => $validator->errors()->first(),
        ]);

        $image = Image::findOrFail($request->id_image);

        if ($request->hasFile('image_categoriechambre')) {
            $image_categoriechambre = $request->image_categoriechambre;
            $categoriechambre_new_name = time() . '.' . $image_categoriechambre->getClientOriginalExtension();
            $image_categoriechambre->move('storage/uploads/', $categoriechambre_new_name);
            $image->path_image = '/storage/uploads/' . $categoriechambre_new_name;
        }

        $process = $image->save();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Mise à jour de la catégorie de chambre avec succes dans le système.", Auth::id());
        else saveSysActivityLog(SYS_LOG_ERROR, "Echec de mise à jour de la catégorie de chambre avec succes dans le système.", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => false,
            "title" => "MISE A JOUR DE L'IMAGE",
            "message" => "L'image de la catégorie de chambre a été modifiée avec succes"
        ]);
    }




    public function deleteImageCategoriechambre(Request $request)
    {
        $image = Image::findOrFail($request->id_image);
        $cheminFinale = $image->path_image;
        unlink(public_path($cheminFinale));
        $process = $image->delete();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression de l'image de la catégorie de chambre dans le système", Auth::id());
        else saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression de l'image de la catégorie de chambre dans le système", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => true,
            "title" => "SUPPRESSION DE L'IMAGE",
            "message" => "L'image de la catégorie de chambre a été bien supprimée dans le système"
        ]);
    }



    // LA GESTION DES IMAGES DES TYPES HEBERGEMENT
    // LA GESTION DES IMAGES DES TYPES HEBERGEMENT
    // LA GESTION DES IMAGES DES TYPES HEBERGEMENT
    // LA GESTION DES IMAGES DES TYPES HEBERGEMENT
    // LA GESTION DES IMAGES DES TYPES HEBERGEMENT



    public function getImagetypehebergement()
    {
        $gestionnaire_hotel_id = Auth::id();
        $societe = Gestionnairesociete::where('gestionnaire_hotel_id', $gestionnaire_hotel_id)->first();



        $images = Image::select(
            'images.*',
            'users.*',
            'villes.*',
            'pays.*',
            'typehebergements.*',

        )

            ->where('images.societe_id', $societe->societe_id)
            ->join('villes', 'villes.id_ville', '=', 'typehebergements.ville_id')
            ->join('pays', 'pays.id_pays', '=', 'typehebergements.pays_id')
            ->join('users', 'users.id', '=', 'images.created_by')
            ->orderByDesc('images.created_at')
            ->get();

        return view('packages.images.typehebergement.image', compact(['typehebergements', 'images']));
    }





    public function infotypehebergement(Request $request)
    {
        $image = Image::where('id_image', ($request->id_image))
            ->select(
                'images.*',
                'users.*',
                'typehebergements.*',
                'pays.*',
                'villes.*'
            )
            ->join(
                'typehebergements',
                'typehebergements.id_typehebergement',
                '=',
                'images.typehebergement_id'
            )

            ->join('pays', 'pays.id_pays', '=', 'typehebergements.pays_id')
            ->join('villes', 'villes.id_ville', '=', 'typehebergements.ville_id')


            ->join('users', 'users.id', '=', 'images.created_by')
            ->orderByDesc('images.created_at')
            ->first();

        return response()->json($image);
    }



    public function storeImagetypehebergement(Request $request)
    {
        $messages = [
            "typehebergement.required" => "Veuillez selectionnez le type d'hébergement, s'il vous plait",
            "imageFile.required" => "L'image du type d'hébergement est requise",
            "imageFile.mimes" => "L'image du type d'hébergement que vous avez selectionnez est invalide",
            "imageFile.max" => " La taille de l'image du type d'hébergement est trop lourde",
        ];

        $validator = Validator::make($request->all(), [
            "typehebergement" => "bail|required",
            "imageFile" => "bail|required|mimes:jpeg,jpg,png|max:2048",
            "imageFile" => "bail|max:2048000",
            "imageFile.*" => "bail|mimes:jpeg,jpg,png",

        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "ENREGISTREMENT DE L'IMAGE",
            "message" => $validator->errors()->first(),
        ]);


        // $gestionnaire_hotel_id = Auth::id();
        // $societe = GestionnaireSociete::where('gestionnaire_hotel_id', $gestionnaire_hotel_id)->first();

        // $societes = Societe::where('id_societe', $societe->societe_id)->where('status_societe', true)->get();


        // foreach ($societes as $item) {
        //     $id_societe = $item->id_societe;
        // }

        if ($request->hasFile('imageFile')) {
            foreach ($request->file('imageFile') as $file) {
                $name = $file->getClientOriginalName();
                $file->move('storage/images', $name);

                $finalImage = new Image();
                $finalImage->pays_id = $request->pays;
                $finalImage->ville_id = $request->ville;
                $finalImage->typehebergement_id = $request->typehebergement;
                $finalImage->created_by = Auth::id();
                $finalImage->path_image = '/storage/images/' . $name;

                $process = $finalImage->save();
            }

            //Enregistrement du systeme de log
            if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Enregistrement de l'image du type d'hébergement avec succes dans le système.", Auth::id());
            else saveSysActivityLog(SYS_LOG_ERROR, "Echec d'enregistrement de l'image du type d'hébergement avec succes dans le système.", Auth::id());

            return response()->json([
                "status" => $process,
                "reload" => true,
                "title" => "ENREGISTREMENT DE L'IMAGE",
                "message" => "L'image du type d'hébergement a été ajoutée avec succes"
            ]);
        } else {
            return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "ENREGISTREMENT DU TYPE HEBERGEMENT",
                "message" => "Une erreur s'est produite"
            ]);
        }
    }




    public function updateImagetypehebergement(Request $request)
    {
        $messages = [
            "image_typehebergement.required" => "L'image de l' hotel est requise",
            "image_typehebergement.mimes" => "L'image de l' hotel que vous avez selectionnez est invalide",
            "image_typehebergement.max" => "La taille de l'image de l' hotel est trop lourde",
        ];

        $validator = Validator::make($request->all(), [
            "image_typehebergement" => "bail|required|mimes:jpeg,jpg,png|max:2048000",
            "image_typehebergement" => "bail|max:2048000",
            "image_typehebergement.*" => "bail|mimes:jpeg,jpg,png",
        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MISE A JOUR DE L'IMAGE",
            "message" => $validator->errors()->first(),
        ]);

        $image = Image::findOrFail($request->id_image);

        if ($request->hasFile('image_typehebergement')) {
            $image_typehebergement = $request->image_typehebergement;
            $typehebergement_new_name = time() . '.' . $image_typehebergement->getClientOriginalExtension();
            $image_typehebergement->move('storage/uploads/', $typehebergement_new_name);
            $image->path_image = '/storage/uploads/' . $typehebergement_new_name;
        }

        $process = $image->save();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Mise à jour du type d'hébergement avec succes dans le système.", Auth::id());
        else saveSysActivityLog(SYS_LOG_ERROR, "Echec de mise à jour du type d'hébergement avec succes dans le système.", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => false,
            "title" => "MISE A JOUR DE L'IMAGE",
            "message" => "L'image du type d'hébergement a été modifiée avec succes"
        ]);
    }




    public function deleteImagetypehebergement(Request $request)
    {
        $image = Image::findOrFail($request->id_image);
        $cheminFinale = $image->path_image;
        unlink(public_path($cheminFinale));
        $process = $image->delete();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression de l'image du type d'hébergement dans le système", Auth::id());
        else saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression de l'image du type d'hébergement dans le système", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => true,
            "title" => "SUPPRESSION DE L'IMAGE",
            "message" => "L'image du type d'hébergement a été bien supprimée dans le système"
        ]);
    }




    // LA GESTION DES IMAGES DES VILLES
    // LA GESTION DES IMAGES DES VILLES
    // LA GESTION DES IMAGES DES VILLES
    // LA GESTION DES IMAGES DES VILLES
    // LA GESTION DES IMAGES DES VILLES



    public function getImageville()
    {
        $gestionnaire_hotel_id = Auth::id();
        $societe = Gestionnairesociete::where('gestionnaire_hotel_id', $gestionnaire_hotel_id)->first();



        $images = Image::select(
            'images.*',
            'users.*',
            'villes.*',
            'pays.*',

        )

            ->where('images.societe_id', $societe->societe_id)
            ->join('pays', 'pays.id_pays', '=', 'villes.pays_id')
            ->join('users', 'users.id', '=', 'images.created_by')
            ->orderByDesc('images.created_at')
            ->get();

        return view('packages.images.ville.image', compact(['villes', 'images']));
    }





    public function infoville(Request $request)
    {
        $image = Image::where('id_image', ($request->id_image))
            ->select(
                'images.*',
                'users.*',
                'pays.*',
                'villes.*'
            )
            ->join(
                'villes',
                'villes.id_ville',
                '=',
                'images.ville_id'
            )

            ->join('pays', 'pays.id_pays', '=', 'villes.pays_id')

            ->join('users', 'users.id', '=', 'images.created_by')
            ->orderByDesc('images.created_at')
            ->first();

        return response()->json($image);
    }



    public function storeImageville(Request $request)
    {
        $messages = [
            "ville.required" => "Veuillez selectionnez la ville s'il vous plait",
            "imageFile.required" => "L'image de la ville est requise",
            "imageFile.mimes" => "L'image de la ville que vous avez selectionnez est invalide",
            "imageFile.max" => " La taille de l'image de la ville est trop lourde",
        ];

        $validator = Validator::make($request->all(), [
            "ville" => "bail|required",
            "imageFile" => "bail|required|mimes:jpeg,jpg,png|max:2048",
            "imageFile" => "bail|max:2048000",
            "imageFile.*" => "bail|mimes:jpeg,jpg,png",

        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "ENREGISTREMENT DE L'IMAGE",
            "message" => $validator->errors()->first(),
        ]);


        // $gestionnaire_hotel_id = Auth::id();
        // $societe = GestionnaireSociete::where('gestionnaire_hotel_id', $gestionnaire_hotel_id)->first();

        // $societes = Societe::where('id_societe', $societe->societe_id)->where('status_societe', true)->get();


        // foreach ($societes as $item) {
        //     $id_societe = $item->id_societe;
        // }

        if ($request->hasFile('imageFile')) {
            foreach ($request->file('imageFile') as $file) {
                $name = $file->getClientOriginalName();
                $file->move('storage/images', $name);

                $finalImage = new Image();
                $finalImage->pays_id = $request->pays;
                $finalImage->ville_id = $request->ville;
                $finalImage->created_by = Auth::id();
                $finalImage->path_image = '/storage/images/' . $name;

                $process = $finalImage->save();
            }

            //Enregistrement du systeme de log
            if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Enregistrement de l'image de la ville avec succes dans le système.", Auth::id());
            else saveSysActivityLog(SYS_LOG_ERROR, "Echec d'enregistrement de l'image de la ville avec succes dans le système.", Auth::id());

            return response()->json([
                "status" => $process,
                "reload" => true,
                "title" => "ENREGISTREMENT DE L'IMAGE",
                "message" => "L'image du type d'hébergement a été ajoutée avec succes"
            ]);
        } else {
            return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "ENREGISTREMENT DU TYPE HEBERGEMENT",
                "message" => "Une erreur s'est produite"
            ]);
        }
    }




    public function updateImageville(Request $request)
    {
        $messages = [
            "image_ville.required" => "L'image de l' hotel est requise",
            "image_ville.mimes" => "L'image de l' hotel que vous avez selectionnez est invalide",
            "image_ville.max" => "La taille de l'image de l' hotel est trop lourde",
        ];

        $validator = Validator::make($request->all(), [
            "image_ville" => "bail|required|mimes:jpeg,jpg,png|max:2048000",
            "image_ville" => "bail|max:2048000",
            "image_ville.*" => "bail|mimes:jpeg,jpg,png",
        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MISE A JOUR DE L'IMAGE",
            "message" => $validator->errors()->first(),
        ]);

        $image = Image::findOrFail($request->id_image);

        if ($request->hasFile('image_ville')) {
            $image_ville = $request->image_ville;
            $ville_new_name = time() . '.' . $image_ville->getClientOriginalExtension();
            $image_ville->move('storage/uploads/', $ville_new_name);
            $image->path_image = '/storage/uploads/' . $ville_new_name;
        }

        $process = $image->save();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Mise à jour de la ville avec succes dans le système.", Auth::id());
        else saveSysActivityLog(SYS_LOG_ERROR, "Echec de mise à jour de la ville avec succes dans le système.", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => false,
            "title" => "MISE A JOUR DE L'IMAGE",
            "message" => "L'image de la ville a été modifiée avec succes"
        ]);
    }




    public function deleteImageville(Request $request)
    {
        $image = Image::findOrFail($request->id_image);
        $cheminFinale = $image->path_image;
        unlink(public_path($cheminFinale));
        $process = $image->delete();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression de l'image de la ville dans le système", Auth::id());
        else saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression de l'image de la ville dans le système", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => true,
            "title" => "SUPPRESSION DE L'IMAGE",
            "message" => "L'image de la ville a été bien supprimée dans le système"
        ]);
    }



    // LA GESTION DES IMAGES DES PAYS
    // LA GESTION DES IMAGES DES PAYS
    // LA GESTION DES IMAGES DES PAYS
    // LA GESTION DES IMAGES DES PAYS
    // LA GESTION DES IMAGES DES PAYS






    public function getImagePays()
    {
        $gestionnaire_hotel_id = Auth::id();
        $societe = Gestionnairesociete::where('gestionnaire_hotel_id', $gestionnaire_hotel_id)->first();



        $images = Image::select(
            'images.*',
            'users.*',
            'pays.*',

        )

            ->where('images.societe_id', $societe->societe_id)
            ->join('users', 'users.id', '=', 'images.created_by')
            ->orderByDesc('images.created_at')
            ->get();

        return view('packages.images.pays.image', compact(['pays', 'images']));
    }





    public function infoPays(Request $request)
    {
        $image = Image::where('id_image', ($request->id_image))
            ->select(
                'images.*',
                'users.*',
                'pays.*',
            )
            ->join(
                'pays',
                'pays.id_pays',
                '=',
                'images.pays_id'
            )

            ->join('users', 'users.id', '=', 'images.created_by')
            ->orderByDesc('images.created_at')
            ->first();

        return response()->json($image);
    }



    public function storeImagePays(Request $request)
    {
        $messages = [
            "pays.required" => "Veuillez selectionnez le pays s'il vous plait",
            "imageFile.required" => "L'image du pays est requise",
            "imageFile.mimes" => "L'image du pays que vous avez selectionnez est invalide",
            "imageFile.max" => " La taille de l'image du pays est trop lourde",
        ];

        $validator = Validator::make($request->all(), [
            "pays" => "bail|required",
            "imageFile" => "bail|required|mimes:jpeg,jpg,png|max:2048",
            "imageFile" => "bail|max:2048000",
            "imageFile.*" => "bail|mimes:jpeg,jpg,png",

        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "ENREGISTREMENT DE L'IMAGE",
            "message" => $validator->errors()->first(),
        ]);


        // $gestionnaire_hotel_id = Auth::id();
        // $societe = GestionnaireSociete::where('gestionnaire_hotel_id', $gestionnaire_hotel_id)->first();

        // $societes = Societe::where('id_societe', $societe->societe_id)->where('status_societe', true)->get();


        // foreach ($societes as $item) {
        //     $id_societe = $item->id_societe;
        // }

        if ($request->hasFile('imageFile')) {
            foreach ($request->file('imageFile') as $file) {
                $name = $file->getClientOriginalName();
                $file->move('storage/images', $name);

                $finalImage = new Image();
                $finalImage->pays_id = $request->pays;
                $finalImage->created_by = Auth::id();
                $finalImage->path_image = '/storage/images/' . $name;

                $process = $finalImage->save();
            }

            //Enregistrement du systeme de log
            if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Enregistrement de l'image de la ville avec succes dans le système.", Auth::id());
            else saveSysActivityLog(SYS_LOG_ERROR, "Echec d'enregistrement de l'image de la ville avec succes dans le système.", Auth::id());

            return response()->json([
                "status" => $process,
                "reload" => true,
                "title" => "ENREGISTREMENT DE L'IMAGE",
                "message" => "L'image du type d'hébergement a été ajoutée avec succes"
            ]);
        } else {
            return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "ENREGISTREMENT DU TYPE HEBERGEMENT",
                "message" => "Une erreur s'est produite"
            ]);
        }
    }




    public function updateImagePays(Request $request)
    {
        $messages = [
            "image_ville.required" => "L'image de l' hotel est requise",
            "image_ville.mimes" => "L'image de l' hotel que vous avez selectionnez est invalide",
            "image_ville.max" => "La taille de l'image de l' hotel est trop lourde",
        ];

        $validator = Validator::make($request->all(), [
            "image_ville" => "bail|required|mimes:jpeg,jpg,png|max:2048000",
            "image_ville" => "bail|max:2048000",
            "image_ville.*" => "bail|mimes:jpeg,jpg,png",
        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MISE A JOUR DE L'IMAGE",
            "message" => $validator->errors()->first(),
        ]);

        $image = Image::findOrFail($request->id_image);

        if ($request->hasFile('image_ville')) {
            $image_ville = $request->image_ville;
            $ville_new_name = time() . '.' . $image_ville->getClientOriginalExtension();
            $image_ville->move('storage/uploads/', $ville_new_name);
            $image->path_image = '/storage/uploads/' . $ville_new_name;
        }

        $process = $image->save();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Mise à jour du pays avec succes dans le système.", Auth::id());
        else saveSysActivityLog(SYS_LOG_ERROR, "Echec de mise à jour du pays avec succes dans le système.", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => false,
            "title" => "MISE A JOUR DE L'IMAGE",
            "message" => "L'image du pays a été modifiée avec succes"
        ]);
    }




    public function deleteImagePays(Request $request)
    {
        $image = Image::findOrFail($request->id_image);
        $cheminFinale = $image->path_image;
        unlink(public_path($cheminFinale));
        $process = $image->delete();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression de l'image du pays dans le système", Auth::id());
        else saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression de l'image du pays dans le système", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => true,
            "title" => "SUPPRESSION DE L'IMAGE",
            "message" => "L'image du pays a été bien supprimée dans le système"
        ]);
    }
}
