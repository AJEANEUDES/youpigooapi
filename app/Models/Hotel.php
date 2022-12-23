<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \Illuminate\Database\Eloquent\Builder;

class Hotel extends Model
{
    use HasFactory;

    protected $table = 'hotels';
    protected $primaryKey =  'id_hotel';

    protected $guarded = ['created_at', 'updated_at'];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public function pays()
    {
        return $this->belongsTo(Pays::class, 'pays_id');
    }

    public function ville()
    {
        return $this->belongsTo(Ville::class, 'ville_id');
    }

    public function typehebergement()
    {
        return $this->belongsTo(Typehebergement::class, 'typehebergement_id');
    }


    //fonction getAllHotels pour la liste des hôtels

    public static function getAllHotels(

        $search_keyword, //recherche par mot clé
        $search_price_min = null,
        $search_price_max = null,
        $search_etoile_max = null,
        $search_etoile_min = null,
        $search_pays = null,
        $search_ville = null,
        $search_typehebergement = null,
        $search_date_reservation_min = null,
        $search_date_reservation_max = null,
        $search_populaire_hotel = null

    ) {

        $hotels = DB::table('hotels')
            ->where('status_hotel', true)
            ->select(

                'typehebergements.*',
                'villes.*',
                'pays.*'
            )

            ->join('pays', 'pays.id_pays', '=', 'hotels.pays_id')

            ->join(
                'villes',
                'villes.id_ville',
                '=',
                'hotels.ville_id'
            )


            ->join(
                'typehebergements',
                'typehebergements.id_typehebergement',
                '=',
                'hotels.typehebergement_id'
            )

            ->orderByDesc('hotels.created_at');




        //condition de recherche par mot clé

        if ($search_keyword && !empty($search_keyword)) {
            $hotels->where(function ($c) use ($search_keyword) {
                $c->where('pays.nom_pays', 'like', "%{$search_keyword}%")
                    ->orwhere('villes.nom_ville', 'like', "%{$search_keyword}%")
                    ->orwhere('typehebergements.nom_typehebergement', 'like', "%{$search_keyword}%")
                    ->orwhere('hotels.nom_hotel', 'like', "%{$search_keyword}%");
            });
        }


        if ($search_price_min && !empty($search_price_min)) {

            if ($search_price_min && !empty($search_price_min)) {

                $hotels->where(function ($c) use ($search_price_min) {
                    $c->where('hotels.prix_estimatif_chambre_hotel', '>=', $search_price_min);
                });

                $hotels->where(function ($c) use ($search_price_max) {
                    $c->where('hotels.prix_estimatif_chambre_hotel', '<=', $search_price_max);
                });
            }


            if ($search_etoile_min && !empty($search_etoile_min)) {

                if ($search_etoile_max && !empty($search_etoile_max)) {

                    $hotels->where(function ($c) use ($search_etoile_min) {
                        $c->where('hotels.etoile', '>=', $search_etoile_min);
                    });

                    $hotels->where(function ($c) use ($search_etoile_max) {
                        $c->where('hotels.etoile', '<=', $search_etoile_max);
                    });
                }
            }


            if ($search_date_reservation_min && !empty($search_date_reservation_min)) {

                if ($search_date_reservation_max && !empty($search_date_reservation_max)) {

                    $hotels->where(function ($c) use ($search_date_reservation_min) {
                        $c->where('hotels.date_reservation_hotel', '>=', $search_date_reservation_min);
                    });

                    $hotels->where(function ($c) use ($search_date_reservation_max) {
                        $c->where('hotels.date_reservation_hotel', '<=', $search_date_reservation_max);
                    });
                }
            }


            //condition de recherche autour de la ville

            if ($search_ville && !empty($search_ville)) {
                $hotels->where(function ($c) use ($search_ville) {

                    $c->whereIn('hotels.ville_id', explode(",", $search_ville));
                });
            }

            //condition de recherche autour du pays

            if ($search_pays && !empty($search_pays)) {
                $hotels->where(function ($c) use ($search_pays) {

                    $c->whereIn('hotels.pays_id', explode(",", $search_pays));
                });
            }


            //condition de recherche autour du type d'hébergement

            if ($search_typehebergement && !empty($search_typehebergement)) {
                $hotels->where(function ($c) use ($search_typehebergement) {

                    $c->whereIn('hotels.typehebergement_id', explode(",", $search_typehebergement));
                });
            }




            //condition de recherche autour de la popularité

            if ($search_populaire_hotel && !empty($search_populaire_hotel)) {
                $hotels->where(function ($c) use ($search_populaire_hotel) {
                    $c->orWhere('hotels.populaire_hotel', '=', $search_populaire_hotel);
                });
            }
        }
    }
}
