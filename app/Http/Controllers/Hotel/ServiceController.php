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

    protected $admin;

    public function guard()
    {
        return Auth::guard();
    }

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->admin = $this->guard()->user();
    }


    public function getServiceChambre()
    {
        $chambres = Chambre::where('status_chambre', true)->orderByDesc('created_at')->get();

        $services = Service::select('services.*', 'chambres.*')
            ->join('chambres', 'chambres.id_chambre', '=', 'services.chambres_id')
            ->orderByDesc('services.created_at')
            ->get();
        // return view('packages.services.admin.service', compact(['services', 'chambres']));
        return response()->json($chambres, $services);
    }


    public function getSpecChambreService(Request $request)
    {
        $service = Service::where('chambre_id', ($request->chambre_id))->get();
        return response()->json($service);
    }



    public function infoServiceChambre(Request $request,  $id_service)
    {

        $service = Service::where("id_service", $id_service)->exists();

        if ($service) {

            $info = Service::find($id_service);


            $service = Service::where('id_service', ($request->id_service))
                ->select('services.*', 'chambres.*', 'users.*')
                ->join('chambres', 'chambres.id_chambre', '=', 'services.chambre_id')
                ->join('users', 'users.id', '=', 'services.created_by')
                ->orderByDesc('services.created_at')
                ->first();

            return response()->json([
                "status" => true,
                "reload" => false,
                "title" => "INFO SUR  LE SERVICE",
                "data" => $info, $service
            ], 200);

            // return response()->json($service);

        } else {
            return response()->json(
                [
                    "status" => 0,
                    "message" => "Aucun Service trouvé",

                ],
                404
            );
        }
    }



    public function storeServiceChambre(Request $request)
    {
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
        $service->status_service = true;
        $service->chambre_id = $request->chambre_id;
        $service->created_by = Auth::id();
        $service->save();


        return response()->json([
            "status" => true,
            "reload" => false,
            "redirect_to" => null,
            "title" => "ENREGISTREMENT DU SERVICE",
            "message" => "Le service " . $service->nom_service . " a été ajouté avec succes"
        ]);
    }




    public function updateServiceChambre(Request $request, $id_service)
    {
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
            $service->status_service = true;
            $service->chambre_id = $request->chambre_id;
            $service->update();

            return response()->json([
                "status" => true,
                "reload" => false,
                "redirect_to" => null,
                "title" => "MISE A JOUR DU SERVICE",
                "message" => "Le service " . $service->nom_service . " a été mise à jour avec succes"
            ]);
        } else {
            return response()->json(
                [
                    "status" => 0,
                    "message" => "Erreur de mise à jour ",

                ],
            );
        }
    }


    public function deleteServiceChambre(Request $request, $id_service)
    {

        $service = Service::findOrFail($id_service);



        if ($service) {


            $service = Service::findOrFail($request->id_service);
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
                "reload" => true,
                "title" => "SUPPRESSION DU SERVICE",
                "message" => "Service introuvable"
            ], 404);

            return redirect('Admin/services/')->with('message', 'Erreur de suppression');
        }
    }
}
