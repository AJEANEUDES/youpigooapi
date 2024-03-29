<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    protected $client;

    public function guard()
    {
        return Auth::guard();
    }


    public function __construct()
    {
        $this->middleware('auth:api');
        $this->client = $this->guard()->user();
    }

    public function roleUser()
    {
        return Auth::user()->roles_user == "Admin";
    }





    public function getClients()
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




            $clients = User::where('roles_user', 'Client')->orderByDesc('created_at')->get();
            // return view('packages.clients.client', compact('clients'));

            return response()->json([
                "status" => true,
                "reload" => true,
                "message" => "LISTE DES CLIENTS",
                "data" => $clients
            ]);
        }
    }

    public function infoClient(Request $request, $id_client)
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

            $client = User::where("id", $id_client)->exists();
            $client = User::where('id', ($request->id_client))->orderByDesc('created_at')->first();

            if ($client) {
                $info = User::find($id_client);

                $client = User::where('roles_user', ($request->roles_user = 'Client'))->orderByDesc('created_at')->first();
                $client = User::where('id', ($request->id_client))->orderByDesc('created_at')->first();

                return response()->json(
                    [
                        "status" => true,
                        "message" => "Client trouvé",
                        "title" => "INFORMATIONS SUR LE PROFIL DU CLIENT",
                        "Information sur le client" => $info

                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        "status" => false,
                        "title" => "INFORMATIONS SUR LE PROFIL DU CLIENT",
                        "message" => "Aucun Client trouvé",

                    ],
                    404
                );
            }
        }
    }

    public function updateClient(Request $request,  $id_client)
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

                "nom_user.required" => "Votre nom est requis",
                "nom_user.max" => "Votre nom est trop long",
                "prenoms_user.required" => "Votre prenoms est requis",
                "prenoms_user.max" => "Votre prenoms est trop long",
                "email_user.required" => "Votre adresse mail est requise",
                "email_user.email" => "Votre adresse mail est invalide",
                "email_user.max" => "Votre adresse mail est trop longue",
                // "telephone_user.required" => "Votre numéro de telephone est requis",
                // "telephone_user.unique" => "Votre numéro de telephone est déjà utilisé et enrégistré dans la base",
                // "telephone_user.min" => "Votre numero de telephone est court",
                // "telephone_user.regex" => "Votre numero de telephone est invalide",
                "adresse_user.required" => "Votre adresse de residence est requis",
                "adresse_user.max" => "Votre adresse de residence est trop long",
                "ville_user.required" => "Votre ville de residence est requise",
                "ville_user.max" => "Votre ville de residence est trop longue",
                "pays_user.required" => "Votre pays de residence est requis",
                "pays_user.max" => "Votre pays de residence est trop long",
               

            ];

            $validator = Validator::make($request->all(), [
                "nom_user" => "bail|required|max:50|",
                "prenoms_user" => "bail|required|max:50",
                "email_user" => "bail|required|email|max:50|unique:users,email_user",
                // "telephone_user" => "bail|required|min:8|max:10|regex:/^([0-9\s\-\+\(\)]*)$/|unique:users,telephone_user",
                "adresse_user" => "bail|required|max:500",
                "ville_user" => "bail|required|max:100",
                "pays_user" => "bail|required|max:100",
            ], $messages);



            if ($validator->fails()) return response()->json([
                "status" => false,
                "reload" => false,
                "title" => "MISE A JOUR DU COMPTE",
                "message" => $validator->errors()->first()
            ]);

            $client = User::where("id", $id_client)->exists();

            if ($client) {



                $client = User::findorFail($id_client);
                $client->status_user = $request->status_user;
                $client->nom_user = $request->nom_user;
                $client->prenoms_user = $request->prenoms_user;
                $client->email_user = $request->email_user;
                $client->telephone_user = $request->telephone_user;
                $client->adresse_user = $request->adresse_user;
                $client->pays_user = $request->pays_user;
                $client->ville_user = $request->ville_user;
                $client->prefix_user = $request->prefix_user;
                $client->status_user =  $request->status_user == true ? '1' : '0';
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
                    "reload" => false,
                    "title" => "MISE A JOUR DU COMPTE",
                    "message" => "Mr/Mlle " . $client->nom_user . " " . $client->prenoms_user . " votre compte a été modifié avec succes"
                ]);
            } else {


                return response()->json([
                    "status" => false,
                    "reload" => false,
                    "title" => "MISE A JOUR DU COMPTE",
                    "message" => "Client Introuvable. Mise à jour de votre compte impossible."
                ]);
            }
        }
    }


    public function deleteClient(Request $request, $id_client)
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

            $client = User::where("id_client", $id_client)
                ->exists();

            if ($client) {


                $client = User::findOrFail($id_client);
                $destination = 'storage/uploads/' .  $client->image_user;

                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $client->delete();

                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "title" => "SUPPRESSION DU CLIENT",
                    "message" => "Le client " . $client->nom_user . " " . $client->prenoms_user . " a été bien supprimé dans le système"
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "reload" => true,
                    "title" => "SUPPRESSION DU CLIENT",
                    "message" => "Client introuvable"
                ]);
            }
        }
    }


    public function profileClient(Request $request)
    {


        $client = User::where('id', ($request->id_client))->orderByDesc('created_at')->first();

        return response()->json(
            [
                "status" => true,
                "reload" => false,
                "redirect_to" => null,
                "title" => "INFORMATIONS SUR LE PROFIL DU CLIENT",
                "message" => "Informations sur le profil du client " . $client->nom_user . " " . $client->prenoms_user . "",

            ],

            200

        );

        // return response()->json(
        //     [
        //         "status" => 1,
        //         "message" => "Informations sur le profil du client",
        //         "datas" => $client = Auth::user()
        //     ],
        //     200
        // );


    }


   
}
