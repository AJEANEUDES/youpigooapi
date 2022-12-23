<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Typehebergement extends Model
{
    use HasFactory;


    protected $table = 'typehebergements';
    protected $primaryKey = 'id_typehebergement';
    protected $guarded = ['created_at', 'updated_at'];

    public function pays()
    {
        return $this->belongsTo(Pays::class, 'pays_id');
    }

    public function ville()
    {
        return $this->belongsTo(Ville::class, 'ville_id');
    }



    public static function getAllTypeHebergements(

        $search_keyword,
        $search_nom = null,
        $search_pays = null,
        $search_ville = null

    ) {

        $typehebergements = DB::table('typehebergements')
            ->where('status_typehebergement', true)
            ->select('typehebergements.*', 'pays.*', 'villes.*')
            ->join('pays', 'pays.id_pays', '=', 'typehebergements.pays_id')
            ->join('villes', 'villes.id_ville', '=', 'typehebergements.ville_id')
            ->orderByDesc('typehebergements.created_at');


        if ($search_keyword && !empty($search_keyword)) {
            $typehebergements->where(function ($c) use ($search_keyword) {
                $c->where('pays.nom_pays', 'like', "%{$search_keyword}%")
                    ->orWhere('villes.nom_ville', 'like', "%{$search_keyword}%");

            });
        }


        
        if($search_nom && !empty($search_nom))
        {
            $typehebergements->where(function ($c) use ($search_nom) {
                $c->orWhere('typehebergements.nom_typehebergement', '=', $search_nom);
            });
        }


        if ($search_pays && !empty($search_pays)) {
            $typehebergements->where(function ($c) use ($search_pays) {
                $c->whereIN('typehebergements.hotel_id', explode(",", $search_pays));
            });
        }

        if ($search_ville && !empty($search_ville)) {
            $typehebergements->where(function ($c) use ($search_ville) {
                $c->whereIN('typehebergements.ville_id', explode(",", $search_ville));
            });
        }

        return $typehebergements->paginate(10);


    }
}
