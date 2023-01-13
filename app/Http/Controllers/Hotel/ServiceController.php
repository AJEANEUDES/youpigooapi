<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Chambre;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{

    protected $service;

    public function guard()
    {
        return Auth::guard();
    }

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->service = $this->guard()->user();
    }


    public function getServiceChambre()
    {

        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Hotel") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas l'accès à un compte hôtel",
            ]);
        } else {


            $services = Service::with("chambres")->get();
            $services = Chambre::where('status_chambre', true)->orderByDesc('created_at')->get();

            $services = Service::select('services.*', 'chambres.*')
                ->join('chambres', 'chambres.id_chambre', '=', 'services.chambre_id')
                ->orderByDesc('services.created_at')
                ->get();


            return response()->json(
                [
                    "status" => true,
                    "message" => "LISTE DES SERVICES LIES A LA CHAMBRE",
                    "Services" => $services
                ],
                200
            );
        }
    }


    public function getSpecChambreService(Request $request)
    {
        $service = Service::where('chambre_id', ($request->chambre_id))->get();
        return response()->json($service);
    }



    public function infoServiceChambre(Request $request,  $id_service)
    {


        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Hotel") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas l'accès à un compte hôtel",
            ]);
        } else {


            $service = Service::with("chambres")->get();
            $service = Service::where("id_service", $id_service)->exists();

            if ($service) {

                $info = Service::find($id_service);


                $service = Service::where('id_service', ($id_service))
                    ->select('services.*', 'chambres.*', 'users.*')
                    ->join('chambres', 'chambres.id_chambre', '=', 'services.chambre_id')
                    ->join('users', 'users.id', '=', 'services.created_by')
                    ->orderByDesc('services.created_at')
                    ->first();

                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "title" => "INFO SUR  LE SERVICE DE LA CHAMBRE",
                    "informations sur le service de chambre" => $info
                ], 200);
            } else {
                return response()->json(
                    [
                        "status" => false,
                        "title" => "INFO SUR  LE SERVICE DE LA CHAMBRE",
                        "message" =>  "Aucun service de chambre trouvé",

                    ],
                    404
                );
            }
        }
    }



    public function storeServiceChambre(Request $request)
    {

        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Hotel") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas l'accès à un compte hôtel",
            ]);
        } else {


            $messages = [
                "nom_service.required" => "Le nom du service est requis",
                "nom_service.max" => "Le nom du service est trop long",
                "nom_service.unique" => "Ce service existe deja dans le système",
                "description_service.required" => "La description du service est requise",
                "chambre_id.required" => "La chambre du service est requise",
            ];

            $validator = Validator::make($request->all(), [
                "nom_service" => "bail|required|max:200|unique:services,nom_service",
                "chambre_id" => "bail|required",
                "description_service" => "bail|required",

            ], $messages);

            if ($validator->fails()) return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => null,
                "title" => "ENREGISTREMENT DU SERVICE",
                "message" => $validator->errors()->first(),
            ]);

            $service = new Service();
            $service->nom_service = $request->nom_service;
            $service->slug_service = Str::slug("service-" . $request->nom_service);
            $service->description_service = $request->description_service;
            $service->status_service = true;
            $service->chambre_id = $request->chambre_id;
            $service->created_by = Auth::id();
            $service->save();


            return response()->json([
                "status" => true,
                "reload" => true,
                "title" => "ENREGISTREMENT DU SERVICE",
                "message" => "Le service " . $service->nom_service . " a été ajouté avec succes"
            ]);
        }
    }




    public function updateServiceChambre(Request $request, $id_service)
    {

        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Hotel") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas l'accès à un compte hôtel",
            ]);
        } else {

            $messages = [
                "nom_service.required" => "Le nom du service est requis",
                "nom_service.max" => "Le nom du service est trop long",
                "nom_service.unique" => "Ce service existe deja dans le système",
                "description_service.required" => "La description du service est requise",

            ];

            $validator = Validator::make($request->all(), [
                "nom_service" => "bail|required|max:200",
                "description_service" => "bail|required|",
            ], $messages);

            if ($validator->fails()) return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => null,
                "title" => "MISE A JOUR DU SERVICE",
                "message" => $validator->errors()->first(),
            ]);

            // 

            $service = Service::where("id_service", $id_service)->exists();

            if ($service) {

                $service = service::findOrFail($request->id_service);
                $service->nom_service = $request->nom_service;
                $service->slug_service = Str::slug("service-" . $request->nom_service);
                $service->description_service = $request->description_service;
                $service->status_service = true;
                $service->chambre_id = $request->chambre_id;
                $service->update();

                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "title" => "MISE A JOUR DU SERVICE",
                    "message" => "Le service " . $service->nom_service . " a été mise à jour avec succes"
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


    public function deleteServiceChambre(Request $request, $id_service)
    {

        if (Auth::guard()->check() &&  Auth::user()->roles_user != "Hotel") {
            return response()->json([
                "status" => false,
                "reload" => false,
                "redirect_to" => route('login'),
                "title" => "AVERTISSEMENT",
                "message" => "Vous n'êtes pas autorisé. Vous n'êtes pas l'accès à un compte hôtel",
            ]);
        } else {

            $service = Service::where("id_service", $id_service)->exists();

            if ($service) {


                $service = Service::findOrFail($id_service);
                $service->delete();


                return response()->json([
                    "status" => true,
                    "reload" => true,
                    "title" => "SUPPRESSION DU SERVICE",
                    "message" => "Le service " . $service->nom_service . " a été bien supprimé dans le système"
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "reload" => false,
                    "title" => "SUPPRESSION DU SERVICE",
                    "message" => "Service introuvable"
                ], 404);
            }

    }
}

}
