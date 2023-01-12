<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Categoriechambre;
use App\Models\Chambre;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\StripeClient;

class ReservationController extends Controller
{

    public function getReservations()
    {

        $reservations = Reservation::with("chambres")->get();
        $reservations = Reservation::with("hotels")->get();

        $reservations = Reservation::where('status_annulation', false)
            ->where('status_reservation', true)
            ->select(
                'reservations.*',
                'chambres.*',
                // 'societes.*',
                // 'services.*',
                'users.*',
                'categoriechambres.*',
                'hotels.*'
            )

            ->join('chambres', 'chambres.id_chambre', '=', 'reservations.chambre_id')
            ->join(
                'categoriechambres',
                'categoriechambres.id_categoriechambre',
                '=',
                'chambres.categoriechambre_id'
            )

            ->join('hotels', 'hotels.id_hotel', '=', 'chambres.hotel_id')
            // ->join('societes', 'societes.id_societe', '=', 'reservations.societe_id')
            ->join('users', 'users.id', '=', 'reservations.user_id')
            //->join('services', 'services.id_service', '=', 'reservations.service_reservation')
            ->orderByDesc('reservations.created_at')
            ->get();

        $listes_services = [];

        foreach ($reservations as $dataReservations) {
            $data = json_decode($dataReservations['service_reservation']);
            $nom_service = "";
            if (!is_null($data)) {
                foreach ($data as $item) {
                    $services = Service::where('id_service', $item)->first();
                    $nom_service .= $services->nom_service . ',';
                }
                $listes_services[$dataReservations->id_reservation] = $nom_service;
            } else {
                $listes_services[$dataReservations->id_reservation] = "";
            }
        }



        //Reservation annulee
        // (si la reservation est annulée)

        $reservations_annul = Reservation::where('status_annulation', true)
            ->where('status_reservation', true)
            ->select(
                'reservations.*',
                'chambres.*',
                // 'societes.*',
                'users.*',
                'categoriechambres.*',
                'hotels.*'
            )

            ->join('chambres', 'chambres.id_chambre', '=', 'reservations.chambre_id')
            ->join(
                'categoriechambres',
                'categoriechambres.id_categoriechambre',
                '=',
                'chambres.categoriechambre_id'
            )

            ->join('hotels', 'hotels.id_hotel', '=', 'chambres.hotel_id')
            // ->join('societes', 'societes.id_societe', '=', 'reservations.societe_id')
            ->join('users', 'users.id', '=', 'reservations.user_id')
            //->join('services', 'services.id_service', '=', 'reservations.service_reservation')
            ->orderByDesc('reservations.created_at')
            ->get();

        $listes_services_annul = [];
        foreach ($reservations_annul as $dataReservationsAnnul) {
            $data = json_decode($dataReservationsAnnul['service_reservation']);
            $nom_service = "";
            if (!is_null($data)) {
                foreach ($data as $item) {
                    $services = Service::where('id_service', $item)->first();
                    $nom_service .= $services->nom_service . ',';
                }
                $listes_services_annul[$dataReservationsAnnul->id_reservation] = $nom_service;
            } else {
                $listes_services_annul[$dataReservationsAnnul->id_reservation] = "";
            }
        }

        // return view('packages.reservations.reservation', compact([
        //     'reservations', 'listes_services', 'reservations_annul',
        //     'listes_services_annul'
        // ]));


        return response()->json([
            "status" => true,
            "reload" => false,
            "message" => "LISTE DES RESERVATIONS",
            "data" => $reservations,
            "message2" => "LISTE DES RESERVATIONS ANNULEES",
            "datareserveesannulees" => $reservations_annul
        ]);
    }


    public function listeReservation()
    {
        $client = Auth::user();
        $mes_reservations = Reservation::where('reservations.created_by', $client->id)
            ->where('status_reservation', true)
            ->select(
                'reservations.*',
                'chambres.*',
                'categoriechambres.*',
                'hotels.*'
            )

            // ->select('reservations.*', 'chambres.*', 'marques.*', 'modeles.*')
            ->join('chambres', 'chambres.id_chambre', '=', 'reservations.chambre_id')
            ->join(
                'categoriechambres',
                'categoriechambres.id_categoriechambre',
                '=',
                'chambres.categoriechambre_id'
            )

            ->join('hotels', 'hotels.id_hotel', '=', 'chambres.hotel_id')
            ->orderByDesc('reservations.created_at')
            ->get();

        $listes_services = [];
        foreach ($mes_reservations as $dataReservations) {
            $data = json_decode($dataReservations['service_reservation']);
            $nom_service = "";
            if (!is_null($data)) {
                foreach ($data as $item) {
                    $services = Service::where('id_service', $item)->first();
                    $nom_service .= $services->nom_service . ',';
                }
                $listes_services[$dataReservations->id_reservation] = $nom_service;
            } else {
                $listes_services[$dataReservations->id_reservation] = "";
            }
        }

        return response()->json([
            "status" => true,
            "reload" => false,
            "redirect_to" => null,
            "title" => "LISTE DES RESERVATIONS D'HOTEL"
        ]);




        // return view('pages.utilisateur.reservation', compact([
        //     'mes_reservations', 'client', 'listes_services'
        // ]));


    }


    public function infoReservation(Request $request)
    {
        $reservation = Reservation::where('id_reservation', ($request->id_reservation))

            ->select(
                'reservations.*',
                'chambres.*',
                // 'societes.*',
                'users.*',
                'categoriechambres.*',
                'hotels.*'
            )

            ->join('chambres', 'chambres.id_chambre', '=', 'reservations.chambre_id')
            ->join(
                'categoriechambres',
                'categoriechambres.id_categoriechambre',
                '=',
                'chambres.categoriechambre_id'
            )

            ->join('hotels', 'hotels.id_hotel', '=', 'chambres.hotel_id')

            // ->join('societes', 'societes.id_societe', '=', 'reservations.societe_id')
            ->join('hotels', 'hotels.id_hotel', '=', 'societes.hotel_id')
            ->join('users', 'users.id', '=', 'reservations.created_by')
            ->orderByDesc('reservations.created_at')
            ->first();
        return response()->json($reservation);
    }



    public function storeReservation(Request $request)
    {
        $messages = [

            "hotel_id.required" => "L'hôtel de la reservation est requis",
            "chambre_id.required" => "La chambre rerservée dans l'hôtel est requise",


            "prix_reservation.required" => "Le prix de la reservation de la chambre dans 
            l'hôtel est requis",
            "datearrivee.required" => "La date d'arrivee dans la chambre d'hôtel est requise",
            "datedepart.required" => "La date de départ dans la chambre d'hôtel est requis",
            "totaladultes.required" => "Le nombre total d'adultes  réservant la chambre
            dans l'hôtel est requis",
            // "totalenfants.required" => "Le nombre total d'enfants à prendre en compte dans la 
            // reservation de la chambre dans l'hôtel est requis",
            "nombredechambres.required" => "Le nombre de chambres reservé dans la chambre dans l'hôtel est requis",


        ];

        $validator = Validator::make($request->all(), [

            "hotel_id" => "bail|required",
            "chambre_id" => "bail|required",

            "datearrivee" => "bail|required|",
            "datedepart" => "bail|required|",
            "totaladultes" => "bail|required|",
            "nombredechambres" => "bail|required|",


        ], $messages);


        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "ENREGISTREMENT DE LA RESERVATION",
            "message" => $validator->errors()->first(),
        ]);


        $reservation = new Reservation();
        $reservation->datearrivee = $request->datearrivee;
        $reservation->datedepart = $request->datedepart;
        $reservation->totaladultes = $request->totaladultes;
        $reservation->totalenfants = $request->totalenfants;
        $reservation->nombredechambres = $request->nombredechambres;
        // $reservation->nombredejours = $request->nombredejours;
        // $reservation->total = $request->nombredejours * $request->prix_reservation;

        $reservation->hotel_id = $request->hotel_id;
        $reservation->chambre_id = $request->chambre_id;


        $reservation->save();


        return response()->json([
            "status" => true,
            "redirect_to" => route('reservations.checkout'),
            "title" => "ENREGISTREMENT DE LA RESERVATION",
            // "message" => "La reservation  de la chambre " . $reservation->nom_chambre . 
            // "de l'hôtel " . $reservation->nom_hotel . " a été enregistré avec succès avec succes"
            "data"  => $reservation
        ]);



        // \Stripe\Stripe::setApiKey('sk_test_51J56p2ACbrUE7avuCZ2AbQIpizmyVyN3YP7Rfj3TKH17yXPpRqBTUk02T
        // tYq4RkGCXdYupUUKNwOiRJXuOAKQJ1l00g36dE3CN');

        // header('Content-Type: application/json');
        // $session = \Stripe\Checkout\Session::create([
        //     'payement_method_types'=>['card'],
        //     'line_items'=>[[
        //         'price_data'=>[
        //             'currency'=>'EUR',
        //             'product_data' =>[
        //                 'name' => 'Chambre Suite',

        //             ],
        //             'unit_amount' => 2000,
        //         ],
        //         'quantity' => 1,
        //     ]],

        //     'mode' => 'payement',
        //     'success_url' => 'http://127.0.0.1:8000/api/auth/Client/reservations/
        //     payementsuccess?session_id={CHECKOUT_SESSION_ID}',
        //     'cancel_url' => 'http://127.0.0.1:8000/api/auth/Client/reservations/
        //     echecpayement'
        //     ]);

        //     return redirect($session->url);




        // $chambre = Chambre::where('id_chambre', decodeId($request->chambre))
        //     ->select('chambres.*', 'categoriechambres.*', 'hotels.*')
        //     ->join('categoriechambres', 'categoriechambres.id_categoriechambre', '=',
        //      'chambres.categoriechambre_id')
        //     ->join('hotels', 'hotels.id_hotel', '=', 'chambres.hotel_id')
        //     ->first();

        // $nom_produit = $chambre->nom_chambre . " " . $chambre->libelle_categoriechambre;

        // $response = payWithPayDunya($nom_produit);
        // $response = paywithCinetPay($nom_produit); 

        // if ($response->status == "success") {

        //     $reservation = new Reservation();
        //     $reservation->code_reservation = "RESERV-" . generateToken(8, DIGIT_TOKEN);
        //     $reservation->prix_reservation = 200;
        //     $reservation->service_reservation = json_encode($request->service);
        //     $reservation->status_reservation = false;
        //     $reservation->voiture_id = decodeId($request->voiture);
        //     $reservation->societe_id = decodeId($request->societe);
        //     $reservation->created_by = decodeId($request->client);
        //     $reservation->token_payement = $response->token;

        //     $process = $reservation->save();

        //     //Enregistrement du systeme de log
        //     if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Enregistrement de la reservation avec succes dans le système.", $reservation->created_by);
        //     else saveSysActivityLog(SYS_LOG_ERROR, "Echec d'enregistrement de la reservation avec succes dans le système.", $reservation->created_by);

        //     return response()->json([
        //         "status" => true,
        //         "reload" => false,
        //         "redirect_to" => $response->getInvoiceUrl(),
        //         "title" => "PAYEMENT DE LA RESERVATION",
        //         "message" => "Veuillez patientez pour proceder au paiement."
        //     ]);
        // }

        // else{
        //         //Enregistrement du systeme de log
        //         if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Enregistrement de la reservation avec succes dans le système.", $reservation->created_by);
        //         else saveSysActivityLog(SYS_LOG_ERROR, "Echec d'enregistrement de la reservation avec succes dans le système.", $reservation->created_by);

        //         return response()->json([
        //             "status" => true,
        //             "reload" => false,
        //             "redirect_to" => $response->getInvoiceUrl(),
        //             "title" => "PAYEMENT DE LA RESERVATION",
        //             "message" => "Veuillez patientez pour proceder au paiement."
        //         ]);
        // }



    }

    public function checkout(Request $request, $datearrivee)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        header('Content-Type: application/json');
        $disponibiliteChambres = DB::select("select * from chambres where id_chambre not in
        (select chambre_id from reservations where '$datearrivee' between datearrivee and datedepart)");

        $data = [];
        foreach ($disponibiliteChambres as $chambre) {
            $categoriechambres = Categoriechambre::find($chambre->categoriechambre_id);
            $nomchambre = Chambre::find($chambre->nom_chambre);
            $data[] = [

                'categoriechambre' => $categoriechambres,
                'chambre' => $chambre,
                'nomchambre' => $nomchambre,

            ];
        }

        $prix_total = 0;
        $reservations = Reservation::all();
        $lineItems = [];
        foreach ($reservations as $reservation)
        {
            $categoriechambres = Categoriechambre::find($chambre->categoriechambre_id);
            $nomchambre = Chambre::find($chambre->nom_chambre);
            $prixchambre = Chambre::find($chambre->prix_standard_chambre);
            $prix_total += $reservation->$prixchambre;
            $images = Chambre::find($chambre->image_chambre);

            
            $lineItems[] = [
                [[
                    'price_data' => [
                        'currency' => 'usd',
                        'reservation_data' => [
                            // 'categoriechambre' =>$categoriechambres,
                            'name' =>$nomchambre,
                            'images' =>[$images],
    
                        ],
                        'unit_amount' => $prixchambre * 100,
                    ],
                    'quantity' => 1,
                ]]
            ]; 
        }


        $session = \Stripe\Checkout\Session::create([
            'payement_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payement',
            'success_url' => route('reservations.success', [], true),
            'cancel_url' => route('reservations.cancel', [], true),
          
        ]);

        $order = new Order();
        $order->status = 'unpaid';
        $order->prix_total = $prix_total;
        $order->session_id = $session->id_order;
        $order->save();

        return redirect($session->url);
    }


    public function success()
    {

    }


    
    public function cancel()
    {
        
    }



    public function annulationReservation(Request $request)
    {
        $messages = [
            "motif_reservation.required" => "Le motif de la reservation est requis",
            "motif_reservation.max" => "Le motif de la reservation est trop long",
        ];

        $validator = Validator::make($request->all(), [
            "motif_reservation" => "bail|required|max:255",
        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "redirect_to" => null,
            "title" => "ANNULATION D'UNE RESERVATION",
            "message" => $validator->errors()->first(),
        ]);

        $reservation = Reservation::findOrFail($request->reservation);
        $reservation->motif_reservation = $request->motif_reservation;
        $reservation->status_annulation = true;
        $reservation->save();


        return response()->json([
            "status" => true,
            "reload" => false,
            "redirect_to" => null,
            "title" => "ANNULATION D'UNE RESERVATION",
            "message" => "La reservation a été annulée avec succes"
        ]);
    }



    // public function payementCallback()
    // {
    //     $token = $_GET['token'];

    //     $reservation = Reservation::where('created_by', Auth::id())
    //         ->where('status_reservation', false)
    //         ->where('token_payement', $token)
    //         ->orderByDesc('id_reservation')->first();

    //     $chambre = Chambre::where('id_chambre', $reservation->chambre_id)->first();

    //     if ($reservation) {
    //         $response = checkEtatPayementPaydunya($token);
    //         $data = json_decode($response);

    //         if ($data->status == "cancelled") {
    //             $client = Auth::user();
    //             Session::flash('message_return', "Cette transaction a été annulée avec succes.");
    //             return view('pages.payement_return', compact('client'));
    //         } elseif ($data->status == "pending") {
    //             $client = Auth::user();
    //             Session::flash('message_return', "Cette transaction est en attente donc veuillez patientez un instant svp.");
    //             return view('pages.payement_return', compact('client'));
    //         } elseif ($data->status == "completed") {

    //             $reservation->status_reservation = true;
    //             $reservation->facture_reservation = $data->receipt_url;
    //             $process = $reservation->save();

    //             $chambre->status_reserver_chambre = true;
    //             $chambre->save();

    //             //Enregistrement du systeme de log
    //             if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Enregistrement de la reservation avec succes dans le système.", $reservation->created_by);
    //             else saveSysActivityLog(SYS_LOG_ERROR, "Echec d'enregistrement de la reservation avec succes dans le système.", $reservation->created_by);

    //             $client = Auth::user();
    //             Session::flash('message_success', 'Le paiement de la reservation a été effectué avec succes');
    //             return view('pages.payement_callback', compact('client'));
    //         }
    //     } else {
    //         $client = Auth::user();
    //         Session::flash('message_return', "Cette transaction n'existe pas sur notre serveur.");
    //         return view('pages.payement_return', compact('client'));
    //     }
    // }

    // public function payementCancel()
    // {
    //     $client = Auth::user();
    //     Session::flash('message_cancel', 'La reservation a ete annulee avec succes');
    //     return view('pages.payement_cancel', compact('client'));
    // }








    // public function payementReturn()
    // {
    //     $token = $_GET['token'];

    //     $reservation = Reservation::where('created_by', Auth::id())
    //         ->where('status_reservation', false)
    //         ->where('token_payement', $token)
    //         ->orderByDesc('id_reservation')->first();

    //     $chambre = Chambre::where('id_chambre', $reservation->chambre_id)->first();

    //     if ($reservation) {
    //         $response = checkEtatPayementPaydunya($token);
    //         $data = json_decode($response);

    //         if ($data->status == "cancelled") {
    //             $client = Auth::user();
    //             Session::flash('message_return', "Cette transaction a été annulée avec succes.");
    //             return view('pages.payement_return', compact('client'));
    //         } elseif ($data->status == "pending") {
    //             $client = Auth::user();
    //             Session::flash('message_return', "Cette transaction est en attente donc veuillez patientez un instant svp.");
    //             return view('pages.payement_return', compact('client'));
    //         } elseif ($data->status == "completed") {

    //             $reservation->status_reservation = true;
    //             $reservation->facture_reservation = $data->receipt_url;
    //             $process = $reservation->save();

    //             $chambre->status_reserver_chambre = true;
    //             $chambre->save();

    //             //Enregistrement du systeme de log
    //             if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Enregistrement de la reservation avec succes dans le système.", $reservation->created_by);
    //             else saveSysActivityLog(SYS_LOG_ERROR, "Echec d'enregistrement de la reservation avec succes dans le système.", $reservation->created_by);

    //             $client = Auth::user();
    //             Session::flash('message_success', 'Le paiement de la reservation a été effectué avec succes');
    //             return view('pages.payement_return', compact('client'));
    //         }
    //     } else {
    //         $client = Auth::user();
    //         Session::flash('message_return', "Cette transaction n'existe pas sur notre serveur.");
    //         return view('pages.payement_return', compact('client'));
    //     }
    // }

    // public function notificationPayement()
    // {
    //     echo "Notification payement";
    // }


    //Vérifier la disponibilité des chambres

    public function disponibiliteChambre(Request $request, $datearrivee)
    {
        $disponibiliteChambres = DB::select("select * from chambres where id_chambre not in
        (select chambre_id from reservations where '$datearrivee' between datearrivee and datedepart)");

        $data = [];
        foreach ($disponibiliteChambres as $chambre) {
            $categoriechambres = Categoriechambre::find($chambre->categoriechambre_id);
            $nomchambre = Chambre::find($chambre->nom_chambre);
            $data[] = [

                'categoriechambre' => $categoriechambres,
                'chambre' => $chambre,
                'nomchambre' => $nomchambre,

            ];
        }


        return response()->json([
            "status" => true,
            "title" => "DISPONIBILITE DES CHAMBRES",
            "data" => $disponibiliteChambres,
        ]);
    }


    public function reservation_payement_sucess(Request $request)
    {
        \Stripe\Stripe::setApiKey('sk_test_51J56p2ACbrUE7avuCZ2AbQIpizmyVyN3YP7Rfj3TKH17yXPpRqBTUk02T
        tYq4RkGCXdYupUUKNwOiRJXuOAKQJ1l00g36dE3CN');
        $session = \Stripe\Checkout\Session::retrieve($request->get('session_id'));
        $client = \Stripe\Customer::retrieve($session->client);

        dd($session);
        dd($client);
        echo "Payement de la reservation de la chambre d'hôtel 
        effectué avec succès";
    }



    public function reservation_payement_annuler(Request $request)

    {
        dd($request);
        echo "echec du payement de la reservation de la chambre d'hôtel ";
    }

    // Le code d'integration de l'api de stripe

    public function stripePost(Request $request)

    {

        try {
            $stripe = new \Stripe\StripeClient();
            $res = $stripe->tokens->create([
                'card' => [
                    'number' => $request->number,
                    'exp_month' => $request->exp_month,
                    'exp_year' => $request->exp_year,
                    'cvc' => $request->cvc,

                ],
            ]);

            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $response = $stripe->charges->create([
                'amount' => $request->amount,
                'currency' => 'usd',
                'source' => $res->id,
                'description' => $request->description,

            ]);
            return response()->json([$response->status], 201);
        } catch (Exception $ex) {
            return response()->json([['response' => 'Erreur']], 500);
        }
    }
}
