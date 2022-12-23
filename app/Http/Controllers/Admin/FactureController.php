<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;


class FactureController extends Controller
{


    public function downloadFacture(Request $request)
    {
        $facture = Facture::where('id_facture', ($request->id_facture))->first();
        //return response()->json($facture->path_facture);
        $file = public_path() . $facture->path_facture;
        // $headers = array(
        //     'Content-Type: application/pdf',
        //     'Content-Type: application/image',
        // );
        return Response::download($file);
    }



    public function getFacture()
    {
        $factures = Facture::select(
            'factures.*',
            'reservations.*',
            'users.*',
            'chambres.*',
            'categoriechambres.*',
            'hotels.*'
        )

            ->join('reservations', 'reservations.id_reservation', '=', 'factures.reservation_id')
            ->join('users', 'users.id', '=', 'factures.client_id')
            ->join('chambres', 'chambres.id_chambre', '=', 'reservations.chambre_id')
            ->join(
                'categoriechambres',
                'categoriechambres.id_categoriechambres',
                '=',
                'chambres.categoriechambres_id'
            )

            ->join('hotels', 'hotels.id_hotel', '=', 'chambres.hotel_id')
            //->join('users', 'users.id', '=', 'factures.created_by')
            ->orderByDesc('factures.created_at')
            ->get();
        // return view('packages.factures.admin.facture', compact('factures'));

        return response()->json(
            [
                "status" => true,
                "reload" => false,
                "title" => "LISTE DES FACTURES",
                "data" =>  $factures,


            ],
            200

        );
    }



    public function getSpecFacture()
    {
    }


    public function listeFacture()
    {
        $client = Auth::user();
        $mes_factures = Facture::where('factures.client_id', $client->id)

            ->select(
                'factures.*',
                'reservations.*',
                'users.*',
                'chambres.*',
                'categoriechambres.*',
                'hotels.*'
            )

            ->join('reservations', 'reservations.id_reservation', '=', 'factures.reservation_id')
            ->join('users', 'users.id', '=', 'factures.client_id')
            ->join('chambres', 'chambres.id_chambre', '=', 'reservations.chambre_id')
            ->join(
                'categoriechambres',
                'categoriechambres.id_categoriechambres',
                '=',
                'chambres.categoriechambres_id'
            )

            ->join('hotels', 'hotels.id_hotel', '=', 'chambres.hotel_id')
            //->join('users', 'users.id', '=', 'factures.created_by')
            ->orderByDesc('factures.created_at')
            ->get();

        // return view('pages.utilisateur.facture', compact([
        //     'mes_factures', 'client'
        // ]));


        return response()->json(
            [
                "status" => true,
                "reload" => false,
                "title" => "LISTE DES FACTURES DU CLIENT",
                "data" =>  $mes_factures,
                "client" => $client,


            ],
            200

        );
    }


    public function infoFacture(Request $request, $id_facture)
    {

        $facture = Facture::where("id_facture", $id_facture)->exists();

        if ($facture) {


            $facture = Facture::where('id_facture', ($request->id_facture))

                ->select(
                    'factures.*',
                    'reservations.*',
                    'users.*',
                    'chambres.*',
                    'categoriechambres.*',
                    'hotels.*'

                )

                ->join('reservations', 'reservations.id_reservation', '=', 'factures.reservation_id')
                ->join('users', 'users.id', '=', 'factures.client_id')
                ->join(
                    'categoriechambres',
                    'categoriechambres.id_categoriechambres',
                    '=',
                    'chambres.categoriechambres_id'
                )
                ->join('chambres', 'chambres.id_chambre', '=', 'reservations.chambre_id')
                ->join(
                    'categoriechambres',
                    'categoriechambres.id_categoriechambres',
                    '=',
                    'chambres.categoriechambres_id'
                )
                ->join('hotels', 'hotels.id_hotel', '=', 'chambres.hotel_id')
                ->orderByDesc('factures.created_at')
                ->first();


            // return response()->json($facture);
            return response()->json(
                [
                    "status" => true,
                    "reload" => false,
                    "title" => "INFO SUR LA FACTURE",
                    "data" =>  $facture,


                ],
                200

            );
        } else {
            return response()->json(
                [
                    "status" => true,
                    "reload" => false,
                    "title" => "INFO SUR LA FACTURE",
                    "message" => "Aucune facture trouvée",


                ],
                404

            );
        }
    }


    public function storeFacture(Request $request)
    {
        $messages = [
            "path_facture.required" => "Veuillez selectionnez un fichier.",
            "path_facture.mimes" => "Le fichier que vous avez selectionnez est invalide",
            "path_facture.max" => "Le fichier que vous avez selectionnez est trop lourd",
        ];

        $validator = Validator::make($request->all(), [
            "path_facture" => "bail|max:204800",
            "path_facture.*" => "bail|required|file|mimes:jpg,jpeg,png,pdf",
        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "ENVOIE DE LA FACTURE",
            "message" => $validator->errors()->first(),
        ]);

        $facture = new Facture();
        $facture->path_facture = $request->path_facture;
        $facture->reservation_id = $request->reservation;
        $facture->client_id = $request->client;
        $facture->created_by = Auth::id();

        if ($request->hasFile('path_facture')) {
            $facture_client = $request->path_facture;
            $facture_new_name = time() . '.' . $facture_client->getClientOriginalExtension();
            $facture_client->move('storage/factures/', $facture_new_name);
            $facture->path_facture = '/storage/factures/' . $facture_new_name;
        }

        $facture->save();


        return response()->json([
            "status" => true,
            "reload" => false,
            "redirect_to" => null,
            "title" => "ENVOIE DE LA FACTURE",
            "message" => "La facture a été envoyée avec succes"
        ]);
    }




    public function updateFacture(Request $request, $id_facture)
    {
        $messages = [
            "path_facture.required" => "Veuillez selectionnez un fichier.",
            "path_facture.mimes" => "Le fichier que vous avez selectionnez est invalide",
            "path_facture.max" => "Le fichier que vous avez selectionnez est trop lourd",
        ];

        $validator = Validator::make($request->all(), [
            "path_facture" => "bail|max:2048",
            "path_facture.*" => "bail|required|file|mimes:jpg,jpeg,png,pdf",
        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MISE A JOUR DE LA FACTURE",
            "message" => $validator->errors()->first(),
        ]);


        $facture = Facture::where("id_facture", $id_facture)->exists();

        if ($facture) {



            $facture = Facture::findOrFail($id_facture);
            $facture->path_facture = $request->path_facture;

            if ($request->hasFile('path_facture')) {

                $destination = 'storage/factures/' . $facture->path_facture;
                if (File::exists($destination)) {
                    File::delete($destination);
                }
                $facture_client = $request->path_facture;
                $facture_new_name = time() . '.' . $facture_client->getClientOriginalExtension();
                $facture_client->move('storage/factures/', $facture_new_name);
                $facture->path_facture = '/storage/factures/' . $facture_new_name;
            }



            $facture->updaate();


            return response()->json([
                "status" => true,
                "reload" => false,
                "redirect_to" => null,
                "title" => "MISE A JOUR DE LA FACTURE",
                "message" => "La facture a été mise à jour avec succes"
            ]);
        } else {
            return response()->json(
                [
                    "status" => 0,
                    "message" => "Erreur de mise à jour ",

                ],
                404
            );
        }
    }

    public function deleteFacture()
    {
    }
}
