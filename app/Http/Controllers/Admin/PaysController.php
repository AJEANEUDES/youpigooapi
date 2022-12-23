<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
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

class PaysController extends Controller
{
    public function getPays()
    {
        $pays = DB::table('pays')

            ->select( 'pays.*')
            // ->join('users', 'users.id', '=', 'pays.created_by')
            ->orderByDesc('pays.created_at')
            ->get();


        // $pays = Pays::where('status_pays', true)->get();


        // return view('packages.pays.admin.pays', compact([
        //     'pays'

        // ]));
        return response()->json(
            [
                "status" => 1,
                "message" => "Liste des Pays",
                "data" => $pays
            ],
            200
        );



    }


    // public function getSpecPaysVille(Request $request): JsonResponse
    // {
    //     $pays = Pays::find(($request->id_pays));
    //     return response()->json(['data' => $pays]);
    // }

    public function getSpecPaysVille(Request $request)
    {
        $ville = Ville::where('pays_id', ($request->pays_id))->get();
        return response()->json($ville);
    }

    public function getSpecPaysType(Request $request)
    {
        $type = Typehebergement::where('typehebergement_id', ($request->typehebergement_id))->get();
        return response()->json($type);
    }


    public function getSpecPaysHotel(Request $request)
    {
        $hotel = Hotel::where('hotel_id', ($request->hotel_id))->get();
        return response()->json($hotel);
    }


    public function infoPays(Request $request, $id_pays)

    {
        $pays = Pays::where("id_pays", $id_pays)->exists();

        if ($pays) {

            $info = Pays::find($id_pays);


            $pays = Pays::where('id_pays', ($request->id_pays))
                ->select(

                    'pays.*',
                    // 'users.*'
                )

                // ->join('users', 'users.id', '=', 'pays.created_by')
                ->orderByDesc('pays.created_at')
                ->first();
            // return response()->json($pays);

            return response()->json(
                [
                    "status" => 1,
                    "message" => "Pays trouvé",
                    "data" => $info

                ],
                200
            );
        } else {
            return response()->json(
                [
                    "status" => 0,
                    "message" => "Aucun Pays trouvé",

                ],
                404
            );
        }
    }


    public function createPays()
    {
        return view('packages.pays.admin.create');
    }





    public function storePays(Request $request)
    {
        $messages = [

            "nom_pays.required" => "Le nom du pays est requis",
            "nom_pays.max" => "Le nom du pays est trop long",
            "nom_pays.unique" => "Ce pays existe deja dans le système",
            "description_pays.required" => "La description du pays est requise",


        ];


        $validator = Validator::make($request->all(), [

            "nom_pays" => "bail|required|max:200|unique:pays,nom_pays",
            "description_pays" => "bail|required",


        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "ENREGISTREMENT DU PAYS",
            "message" => $validator->errors()->first()
            
        ]);


        $pays = new Pays();
        $pays->nom_pays = $request->nom_pays;
        $pays->slug_pays = Str::slug("pays-" . $request->nom_pays);
        $pays->description_pays = $request->description_pays;
        $pays->image_pays = $request->image_pays;
        $pays->status_pays =  $request->status_pays == true;

        $pays->created_by = Auth::id();

        if ($request->hasFile('image_pays')) {
            $image = $request->image_pays;
            $pays_new_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('storage/uploads/', $pays_new_name);
            $pays->image_pays = '/storage/uploads/' . $pays_new_name;
        }


        $pays->save();


        return response()->json([
            "status" => true,
            "reload" => false,
            "title" => "ENREGISTREMENT DU PAYS",
            "message" => "Le pays " . $pays->nom_pays . " a été ajoutée avec succes"
        ]);

        // return redirect('Admin/pays/')->with('message', 'Pays ajoutée avec succès');
    }



    public function editPays($id_pays)
    {
        $pays = Pays::find($id_pays);
        return view('packages.pays.admin.edit', compact('pays'));
    }



    public function updatePays(Request $request, $id_pays)

    {
        $messages = [

            "nom_pays.required" => "Le nom du pays est requis",
            "description_pays.required" => "La description du pays est requise",




        ];


        $validator = Validator::make($request->all(), [

            "nom_pays" => "bail|required|max:200",
            "description_pays" => "bail|required",



        ], $messages);

        if ($validator->fails()) return response()->json([
            "status" => false,
            "reload" => false,
            "title" => "MISE A JOUR DU PAYS",
            "message" => $validator->errors()->first()
        ]);


        $pays = Pays::where("id_pays", $id_pays)
            ->exists();

        if ($pays) {


            $pays = Pays::findOrFail($request->id_pays);
            $pays->slug_pays = Str::slug($request->nom_pays);
            $pays->nom_pays = $request->nom_pays;
            $pays->description_pays = $request->description_pays;
            $pays->image_pays = $request->image_pays;
            $pays->status_pays =  $request->status_pays == true ? '1' : '0';


            if ($request->hasfile('image_pays')) {

                $destination = 'storage/uploads/' . $pays->image_pays;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $file = $request->file('image_pays');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('storage/uploads/', $filename);
                $pays->image_pays = $filename;
            }

            $pays->update();
            return redirect('Admin/pays/')->with('message', 'Pays modifiée avec succès');


            // return response()->json([
            //     "status" => true,
            //     "reload" => false,
            //     "title" => "MISE A JOUR DU PAYS",
            //     "message" => "Le pays " . $pays->nom_pays . " a été modifié avec succès"
            // ]);


        } else {

            // return response()->json([
            //     "status" => false,
            //     "reload" => false,
            //     "title" => "MISE A JOUR DU PAYS",
            //     "message" => "Erreur de mise à jour"
            // ]);

            return redirect('Admin/pays/editer-pays/', $pays->id_pays)
                ->with('message', 'Erreur de mise à jour');
        }
    }



    public function deletePays(Request $request, $id_pays)
    {
        $pays = Pays::where("id_pays", $id_pays)
        ->exists();

    if ($pays) {


        $pays = Pays::findOrFail($id_pays);
       
        $destination = 'storage/uploads/' . $pays->image_pays;

        if (File::exists($destination)) {
            File::delete($destination);
        }

       
        $pays->delete();

        //Enregistrement du systeme de log

        // return response()->json([
        //     "status" => true,
        //     "reload" => true,
        //     "title" => "SUPPRESSION DU PAYS",
        //     "message" => "Le pays  " . $pays->nom_pays . "été bien supprimé dans le système"
        // ]);
        return redirect('Admin/pays/')->with('message', 'Pays  supprimée avec succès');



    }

    else {
        // return response()->json([
        //     "status" => false,
        //     "reload" => true,
        //     "title" => "SUPPRESSION DU PAYS",
        //     "message" => "Pays introuvable"
        // ]);

        return redirect('Admin/pays/delete-pays/', $pays->id_pays)
        ->with('message', 'Erreur de suppression du pays');

    }
}


}
