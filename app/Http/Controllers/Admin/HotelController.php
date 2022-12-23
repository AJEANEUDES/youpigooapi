<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoriechambre;
use App\Models\Chambre;
use App\Models\Facture;
use App\Models\Gestionnairesociete;
use App\Models\Hotel;
use App\Models\Pays;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\Societe;
use App\Models\Typehebergement;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Ville;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HotelController extends Controller
{


    /* HotelController dans le dossier Admin est un controller qui  prend en charge 
    l'ensemble des opérations qu'on peut effectuer sur un hotel en tant qu'admin
     ou superadmin*/

    public function getHotel()
    {

        // $hotels = DB::table('hotels')
        //     ->select('hotels.*', 'villes.*', 'pays.*', 'typehebergements.*', 'users.*')
        //     ->join('villes', 'villes.id_ville', '=', 'hotels.ville_id')
        //     ->join(
        //         'typehebergements',
        //         'typehebergements.id_typehebergement',
        //         '=',
        //         'hotels.typehebergement_id'
        //     )

        //     ->join('pays', 'pays.id_pays', '=', 'hotels.pays_id')
        //     ->join('users', 'users.id', '=', 'hotels.user_id')
        //     ->orderByDesc('hotels.created_at')
        //     ->get();


        // $hotels = Hotel::all();
        //   $hotels_inactives = Hotel::where('roles_user', 'Hotel')->orderByDesc('created_at')->get();
        // $email_hotel_user = User::where('email_user', 'roles_user')
        // ->orderByDesc('created_at')->get();

        // $typehebergements = Typehebergement::where('status_typehebergement', true)->get();
        // $pays = Pays::where('status_pays', true)->get();
        // // $hotels_inactifs = Hotel::where('status_hotel', false)->get();
        // $villes = Ville::where('status_ville', true)->get();

        $hotels = Hotel::with("typehebergements")->get();
        $hotels = Hotel::with("pays")->get();
        $hotels = Hotel::with("villes")->get();
        

        // return view('packages.gestionnaires.gestionnaire', compact(['hotels',
        // 'villes', 'pays', 'typehebergements'
        //  ]));

        // return view('packages.hotels.hotel', compact([
        //     'hotels',
        //     'villes', 'pays', 'typehebergements'
        // ]));
        // return response()->json($hotels, $typehebergements,  $pays, $villes );

        return response()->json(
            [
                "status" => 1,
                "message" => "Liste des Hôtels",
                "data" => $hotels
            ],
            200
        );
    }


    public function profileHotel()
    {
        return view('packages.profiles.hotel.profile');
    }




    public function infoHotel(Request $request, $id_hotel)
    {
        $hotel = Hotel::with("typehebergements")->get();
        $hotel = Hotel::with("pays")->get();
        $hotel = Hotel::with("villes")->get();
        $hotel = Hotel::where("id_hotel", $id_hotel)->exists();


        if ($hotel) {

            $info = Hotel::find($id_hotel);

            // $hotel = Hotel::where('id_hotel', ($id_hotel))
            //     ->select(

            //         'hotels.*',
            //         'typehebergements.*',
            //         'villes.*',
            //         'pays.*',
            //         'users.*',
            //     )

            //     ->join('pays', 'pays.id_pays', '=', 'hotels.pays_id')
            //     ->join('villes', 'villes.id_ville', '=', 'hotels.ville_id')
            //     ->join(
            //         'typehebergements',
            //         'typehebergements.id_typehebergement',
            //         '=',
            //         'hotels.typehebergements_id'
            //     )
            //     ->join('users', 'users.id', '=', 'hotels.created_by')

            //     ->first();
            // return response()->json($ville);

            return response()->json([
                "status" => true,
                "reload" => false,
                "title" => "INFO SUR  L'HOTEL",
                "data" => $info
            ], 200);
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


    public function createHotel()
    {
        $pays = Pays::where('status_pays', true)->orderByDesc('created_at')->get();
        $users = User::where('status_user', true)->orderByDesc('created_at')->get();
        $villes = Ville::where('status_ville', true)->orderByDesc('created_at')->get();
        $typehebergements = Typehebergement::where('status_typehebergement', true)->orderByDesc('created_at')->get();
        return view('packages.hotels.create', compact('pays', 'users', 'villes', 'typehebergements'));
    }




    public function storeHotel(Request $request)
    {
        $messages = [


            //informations sur l'hôtel proprement dit

            "typehebergement_id.required" => "Le type d'hebergement de l'hôtel est requis",
            "ville_id.required" => "La ville de l'hôtel est requise",
            "pays_id.required" => "Le pays de l'hôtel est requis",
            "user_id.required" => "Le gestionnaire de l'hôtel est requis",

            "nom_hotel.required" => "Le nom de l'hôtel est requis",
            "description_hotel.required" => "La description de l'hôtel est requise",
            "description_hotel.max" => "La description de l'hôtel est trop longue",

            "image_hotel.mimes" => "L'image de l'hôtel que vous avez selectionnez est invalide",
            "image_hotel.max" => "La taille de l'image de l'hôtel est trop lourde",

            "prix_estimatif_chambre_hotel.required" => "Le prix estimatif d'une
            chambre à l'hôtel est requis",
            "telephone1_hotel.required" => "Le numero1 de telephone de l'hôtel est 
            requis",
            "telephone1_hotel.unique" => "Ce numero de telephone existe deja",

            "adresse_hotel.required" => "L'adresse de l'hôtel est requise",
            "email_hotel.required" => "L'email de l'hôtel est requis",
            "email_hotel.unique" => "Cet email existe deja",
            "adresse_hotel.required" => "L'adresse de l'hôtel est requise",


        ];

        $validator = Validator::make($request->all(), [

            //informations sur l'hôtel proprement dit

            "ville_id" => "bail|required",
            "pays_id" => "bail|required",
            "user_id" => "bail|required",
            "typehebergement_id" => "bail|required",

            "nom_hotel" => "bail|required|max:500",
            "description_hotel" => "bail|required",
            "telephone1_hotel" => "bail|unique:hotels,telephone1_hotel",
            "telephone2_hotel" => "bail",
            "etoile" => "bail|required",
            "adresse_hotel" => "bail|required",
            "email_hotel" => "bail|unique:hotels,email_hotel",
            "prix_estimatif_chambre_hotel" => "bail|required",

            "image_hotel" => "bail|max:2048000",
            "image_hotel.*" => "bail|mimes:jpeg,jpg,png",



        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "redirect_to" => null,
            "title" => "ENREGISTREMENT DE L'HOTEL",
            "message" => $validator->errors()->first(),
        ]);


        $hotel = new Hotel();
        $hotel->nom_hotel = $request->nom_hotel;
        $hotel->slug_hotel = Str::slug("Hotel-" . $request->nom_hotel);
        $hotel->description_hotel = $request->description_hotel;

        $hotel->pays_id = $request->pays_id;
        $hotel->user_id = $request->user_id;
        $hotel->typehebergement_id = $request->typehebergement_id;
        $hotel->ville_id = $request->ville_id;

        $hotel->telephone1_hotel = $request->telephone1_hotel;
        $hotel->telephone2_hotel = $request->telephone2_hotel;
        $hotel->etoile = $request->etoile;
        $hotel->adresse_hotel = $request->adresse_hotel;
        $hotel->email_hotel = $request->email_hotel;

        $hotel->numero_rccm_hotel = $request->numero_rccm_hotel;
        if ($request->hasfile('numero_rccm_hotel')) {
            $file = $request->file('numero_rccm_hotel');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('storage/uploads/', $filename);
            $hotel->numero_rccm_hotel = $filename;
        }

        $hotel->numero_cnss_hotel = $request->numero_cnss_hotel;
        if ($request->hasfile('numero_cnss_hotel')) {
            $file = $request->file('numero_cnss_hotel');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('storage/uploads/', $filename);
            $hotel->numero_cnss_hotel = $filename;
        }

        $hotel->numero_if_hotel = $request->numero_if_hotel;
        if ($request->hasfile('numero_if_hotel')) {
            $file = $request->file('numero_if_hotel');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('storage/uploads/', $filename);
            $hotel->numero_if_hotel = $filename;
        }

        // $hotel->longitude_hotel = $request->longitude_hotel;
        // $hotel->latitude_hotel = $request->latitude_hotel;
        $hotel->prix_estimatif_chambre_hotel = $request->prix_estimatif_chambre_hotel;
        $hotel->status_hotel =  $request->status_hotel == true ? '1' : '0';
        // $hotel->created_by = Auth::id();

        if ($request->hasfile('image_hotel')) {
            $file = $request->file('image_hotel');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('storage/uploads/', $filename);
            $hotel->image_hotel = $filename;
        }

        $hotel->save();



        return redirect('Admin/hotels/')->with('message', 'Hotel Ajoutée avec succès');

        return response()->json([
            "status" => true,
            "reload" => false,
            "redirect_to" => null,
            "title" => "ENREGISTREMENT DE L'HOTEL",
            "message" => "L'Hôtel " . $hotel->nom_hotel . "a été ajouté avec succes"
        ]);
    }


    public function editHotel(Request $request, $id_hotel)
    {

        $pays = Pays::where('status_pays', true)->orderByDesc('created_at')->get();
        $users = User::where('status_user', true)->orderByDesc('created_at')->get();
        $villes = Ville::where('status_ville', true)->orderByDesc('created_at')->get();
        $typehebergements = Typehebergement::where('status_typehebergement', true)->orderByDesc('created_at')->get();
        $hotel = Hotel::find($id_hotel);

        return view('packages.hotels.edit', compact('pays', 'users', 'villes', 'typehebergements', 'hotel'));
    }



    public function updateHotel(Request $request, $id_hotel)
    {

        $messages = [


            //informations sur l'hôtel proprement dit

            "typehebergement_id.required" => "Le type d'hebergement de l'hôtel est requis",
            "ville_id.required" => "La ville de l'hôtel est requise",
            "pays_id.required" => "Le pays de l'hôtel est requis",
            "user_id.required" => "Le pays de l'hôtel est requis",

            "nom_hotel.required" => "Le nom de l'hôtel est requis",
            "description_hotel.required" => "La description de l'hôtel est requise",
            "description_hotel.max" => "La description de l'hôtel est trop longue",

            "image_hotel.mimes" => "L'image de l'hôtel que vous avez selectionnez est invalide",
            "image_hotel.max" => "La taille de l'image de l'hôtel est trop lourde",

            "prix_estimatif_chambre_hotel.required" => "Le prix estimatif d'une
            chambre à l'hôtel est requis",
            "telephone1_hotel.required" => "Le numero1 de telephone de l'hôtel est 
            requis",
            "telephone1_hotel.required" => "Le numero de telephone est requis",
            "telephone2_hotel.required" => "Le numero de telephone est requis",
            "email_hotel.required" => "L'email de l'hôtel est requis",
            "adresse_hotel.required" => "L'adresse de l'hôtel est requise",


        ];



        $validator = Validator::make($request->all(), [


            //informations sur l'hôtel proprement dit

            "ville_id" => "bail|required",
            "pays_id" => "bail|required",
            "user_id" => "bail|required",
            "typehebergement_id" => "bail|required",

            "nom_hotel" => "bail|required|max:500",
            "description_hotel" => "bail|required",
            "telephone1_hotel" => "bail|required",
            "telephone2_hotel" => "bail",
            "email_hotel" => "bail|required",
            "prix_estimatif_chambre_hotel" => "bail|required",

            "image_hotel" => "bail|max:2048000",
            "image_hotel.*" => "bail|mimes:jpeg,jpg,png",



        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "redirect_to" => null,
            "title" => "MISE A JOUR DU COMPTE L'HOTEL",
            "message" => $validator->errors()->first(),
        ]);

        $hotel = Hotel::where("id_hotel", $id_hotel)->exists();


        if ($hotel) {


            $hotel = Hotel::findOrFail($id_hotel);
            $hotel->nom_hotel = $request->nom_hotel;
            $hotel->slug_hotel = Str::slug("Hotel-" . $request->nom_hotel);
            $hotel->description_hotel = $request->description_hotel;
            $hotel->typehebergement_id = $request->typehebergement_id;
            $hotel->ville_id = $request->ville_id;
            $hotel->pays_id = $request->pays_id;
            $hotel->user_id = $request->user_id;
            $hotel->telephone1_hotel = $request->telephone1_hotel;
            $hotel->telephone2_hotel = $request->telephone2_hotel;
            $hotel->etoile = $request->etoile;
            $hotel->email_hotel = $request->email_hotel;
            $hotel->prix_estimatif_chambre_hotel = $request->prix_estimatif_chambre_hotel;
            $hotel->status_hotel =  $request->status_hotel == true ? '1' : '0';


            if ($request->hasfile('numero_cnss_hotel')) {

                $destination = 'storage/uploads/' . $hotel->numero_cnss_hotel;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $file = $request->file('numero_cnss_hotel');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/uploads/', $filename);
                $hotel->numero_cnss_hotel = $filename;
            }


            if ($request->hasfile('numero_rccm_hotel')) {

                $destination = 'storage/uploads/' . $hotel->numero_rccm_hotel;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $file = $request->file('numero_rccm_hotel');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/uploads/', $filename);
                $hotel->numero_rccm_hotel = $filename;
            }



            if ($request->hasfile('numero_if_hotel')) {

                $destination = 'storage/uploads/' . $hotel->numero_if_hotel;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $file = $request->file('numero_if_hotel');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/uploads/', $filename);
                $hotel->numero_if_hotel = $filename;
            }




            if ($request->hasfile('image_hotel')) {

                $destination = 'storage/uploads/' . $hotel->image_hotel;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $file = $request->file('image_hotel');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/uploads/', $filename);
                $hotel->image_hotel = $filename;
            }



            $hotel->update();



            return response()->json([
                "status" => true,
                "reload" => false,
                "redirect_to" => null,
                "title" => "MISE A JOUR DU COMPTE DE L'HOTEL",
                "message" => "L'Hôtel " . $hotel->nom_hotel . " a été modifié avec succes"
            ]);


            return redirect('Admin/hotels/')->with('message', 'Hotel modifiée avec succès');
        } else {
            return response()->json(
                [
                    "status" => 0,
                    "message" => "Erreur de mise à jour ",

                ],
            );
        }
    }



    public function deleteHotel($id_hotel)

    {

        $hotel = Hotel::findOrFail($id_hotel);



        if ($hotel) 
        
        {

            $destination = 'storage/uploads/' . $hotel->image_hotel;
            if (File::exists($destination)) {
                File::delete($destination);
            }


            $destination = 'storage/uploads/' . $hotel->numero_cnss_hotel;
            if (File::exists($destination)) {
                File::delete($destination);
            }


            $destination = 'storage/uploads/' . $hotel->numero_rccm_hotel;
            if (File::exists($destination)) {
                File::delete($destination);
            }


            $destination = 'storage/uploads/' . $hotel->numero_if_hotel;
            if (File::exists($destination)) {
                File::delete($destination);
            }



            $hotel->delete();
            return redirect('Admin/hotels/')->with('message', 'Hotel Supprimé avec succès');

            return response()->json([
                "status" => true,
                "reload" => true,
                "title" => "SUPPRESSION DE L'HOTEL",
                "message" => "L'Hôtel " . $hotel->nom_hotel . " a été bien supprimé dans le système"

            ]);
        } 
        
        
        else {
            return response()->json([
                "status" => false,
                "reload" => true,
                "title" => "SUPPRESSION DE L'HOTEL",
                "message" => "Compte Hotel introuvable"
            ], 404);

            return redirect('Admin/hotels/')->with('message', 'Erreur de suppression');
        }
    }
}
