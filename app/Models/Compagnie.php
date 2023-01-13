<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Compagnie extends Model
{
    use HasFactory;

    protected $table = 'compagnies';
    protected $primaryKey =  'id_compagnie';

    protected $guarded = ['created_at', 'updated_at'];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public function pays()
    {
        return $this->belongsTo(Pays::class, 'pays_id');
    }

    public function villes()
    {
        return $this->belongsTo(Ville::class, 'ville_id');
    }


     //fonction getAllCompagnies pour la liste des compagnies

     public static function getAllCompagnies(

        $search_keyword, //recherche par mot clé
        $search_price_min = null,
        $search_price_max = null,
        $search_pays = null,
        $search_ville = null,
        $search_region = null,
        $search_prefecture = null,
        $search_date_reservation_min = null,
        $search_date_reservation_max = null

    ) {

        $compagnies = DB::table('compagnies')
            ->where('status_compagnie', true)
            ->select(

                'villes.*',
                'pays.*',
                'regions.*',
                'prefectures.*'
            )

            ->join('pays', 'pays.id_pays', '=', 'compagnies.pays_id')

            ->join(
                'villes',
                'villes.id_ville',
                '=',
                'compagnies.ville_id'
            )


            ->join(
                'regions',
                'regions.id_region',
                '=',
                'compagnies.region_id'
            )


            ->join(
                'prefectures',
                'prefectures.id_prefecture',
                '=',
                'compagnies.prefecture_id'
            )


            ->orderByDesc('compagnies.created_at');




        //condition de recherche par mot clé

        if ($search_keyword && !empty($search_keyword)) {
            $compagnies->where(function ($c) use ($search_keyword) {
                $c->where('pays.nom_pays', 'like', "%{$search_keyword}%")
                    ->orwhere('villes.nom_ville', 'like', "%{$search_keyword}%")
                    ->orwhere('regions.nom_region', 'like', "%{$search_keyword}%")
                    ->orwhere('prefectures.nom_prefecture', 'like', "%{$search_keyword}%")
                    ->orwhere('compagnies.nom_compagnie', 'like', "%{$search_keyword}%");
            });
        }


        if ($search_price_min && !empty($search_price_min)) {

            if ($search_price_min && !empty($search_price_min)) {

                $compagnies->where(function ($c) use ($search_price_min) {
                    $c->where('compagnies.prix_estimatif_reservation_bus_compagnie', '>=', $search_price_min);
                });

                $compagnies->where(function ($c) use ($search_price_max) {
                    $c->where('compagnies.prix_estimatif_reservation_bus_compagnie', '<=', $search_price_max);
                });
            }




            if ($search_date_reservation_min && !empty($search_date_reservation_min)) {

                if ($search_date_reservation_max && !empty($search_date_reservation_max)) {

                    $compagnies->where(function ($c) use ($search_date_reservation_min) {
                        $c->where('compagnies.date_reservation_hotel', '>=', $search_date_reservation_min);
                    });

                    $compagnies->where(function ($c) use ($search_date_reservation_max) {
                        $c->where('compagnies.date_reservation_hotel', '<=', $search_date_reservation_max);
                    });
                }
            }


            //condition de recherche autour de la ville

            if ($search_ville && !empty($search_ville)) {
                $compagnies->where(function ($c) use ($search_ville) {

                    $c->whereIn('compagnies.ville_id', explode(",", $search_ville));
                });
            }

            //condition de recherche autour du pays

            if ($search_pays && !empty($search_pays)) {
                $compagnies->where(function ($c) use ($search_pays) {

                    $c->whereIn('compagnies.pays_id', explode(",", $search_pays));
                });
            }


            //condition de recherche autour du type d'hébergement

            if ($search_region && !empty($search_region)) {
                $compagnies->where(function ($c) use ($search_region) {

                    $c->whereIn('compagnies.region_id', explode(",", $search_region));
                });
            }




            //condition de recherche autour de la popularité

            if ($search_prefecture && !empty($search_prefecture)) {
                $compagnies->where(function ($c) use ($search_prefecture) {
                    $c->orWhere('compagnies.prefecture_id', '=', $search_prefecture);
                });
            }
        }
    }

    
}
