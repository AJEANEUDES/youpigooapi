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
    
    public function getServiceChambre()
    {
        $chambres = Chambre::where('status_chambre', true)->orderByDesc('created_at')->get();

        $services = Service::select('services.*', 'chambres.*')
            ->join('chambres', 'chambres.id_chambre', '=', 'services.chambres_id')
            ->orderByDesc('services.created_at')
            ->get();
        return view('packages.services.hotel.service', compact(['services', 'chambres']));
    }

    
    public function getSpecChambreService(Request $request)
    {
        $service = Service::where('chambre_id', decodeId($request->chambre_id))->get();
        return response()->json($service);
    }



    public function infoServiceChambre(Request $request)
    {
        $service = Service::where('id_service', decodeId($request->id_service))
            ->select('services.*', 'chambres.*', 'users.*')
            ->join('chambres', 'chambres.id_chambre', '=', 'services.chambre_id')
            ->join('users', 'users.id', '=', 'services.created_by')
            ->orderByDesc('services.created_at')
            ->first();
        return response()->json($service);
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
        $service->code_service = "service-" . generateToken(6, DIGIT_TOKEN);
        $service->slug_service = Str::slug("service-" . $request->nom_service);
        $service->status_service = true;
        $service->chambre_id = $request->chambre_id;
        $service->created_by = Auth::id();
        $process = $service->save();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Enregistrement du service " . $service->nom_service . " avec succes dans le système.", Auth::id());
        else saveSysActivityLog(SYS_LOG_ERROR, "Echec d'enregistrement du service " . $service->nom_service . " avec succes dans le système.", Auth::id());

        return response()->json([
            "status" => true,
            "reload" => false,
            "redirect_to" => null,
            "title" => "ENREGISTREMENT DU SERVICE",
            "message" => "Le service " . $service->nom_service . " a été ajouté avec succes"
        ]);
    }




    public function updateServiceChambre(Request $request)
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

        $service = service::findOrFail($request->id_service);
        $service->nom_service = $request->nom_service;
        $service->slug_service = Str::slug("service-" . $request->nom_service);
        $service->status_service = true;
        $service->chambre_id = $request->chambre_id;
        $process = $service->save();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Mise à jour du service " . $service->nom_service . " avec succes dans le système.", Auth::id());
        else saveSysActivityLog(SYS_LOG_ERROR, "Echec de mise à jour du service " . $service->nom_service . " avec succes dans le système.", Auth::id());

        return response()->json([
            "status" => true,
            "reload" => false,
            "redirect_to" => null,
            "title" => "MISE A JOUR DU SERVICE",
            "message" => "Le service " . $service->nom_service . " a été mise à jour avec succes"
        ]);
    }


    public function deleteServiceChambre(Request $request)
    {
        $service = Service::findOrFail($request->id_service);
        $process = $service->delete();

        //Enregistrement du systeme de log
        if ($process) saveSysActivityLog(SYS_LOG_SUCCESS, "Suppression du service " . $service->nom_service . " dans le système", Auth::id());
        else saveSysActivityLog(SYS_LOG_SUCCESS, "Echec de suppression du service " . $service->nom_service . " dans le système", Auth::id());

        return response()->json([
            "status" => $process,
            "reload" => true,
            "title" => "SUPPRESSION DU SERVICE",
            "message" => "Le service " . $service->nom_service . " a été bien supprimé dans le système"
        ]);
    }


}
