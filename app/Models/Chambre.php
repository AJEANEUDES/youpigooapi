<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Chambre extends Model
{
    use HasFactory;


    protected $table = 'chambres';
    protected $primaryKey = 'id_chambre';
    protected $guarded = ['created_at', 'updated_at'];


    public function hotels()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function typehebergements()
    {
        return $this->belongsTo(Typehebergement::class, 'typehebergement_id');
    }

    public function categoriechambres()
    {
        return $this->belongsTo(Categoriechambre::class, 'categoriechambre_id');
    }

    public function villes()
    {
        return $this->belongsTo(Ville::class, 'ville_id');
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class, 'pays_id');
    }


   
    //Fonction getAllChambres pour avoir la liste de tous les chambres de l'hôtel


    public static function getAllChambres(

        $search_keyword, //recherche par mot clé
        $search_price_min = null,
        $search_price_max = null,
        $search_hotel = null,
        $search_pays = null,
        $search_ville = null,
        $search_typehebergement = null,
        $search_categoriechambre = null,
        
        $search_classe_chambre = null,
        $search_nombre_lits_min = null,
        $search_nombre_lits_max = null,
        $search_nombre_places_min = null,
        $search_nombre_places_max = null
        


    ) {


        $chambres = DB::table('chambres')
            ->where('status_chambre', true)
            ->where('status_reserver_chambre', false)
            ->select(
                'chambres.*',
                'categoriechambres.*',
                'typehebergements.*',
                'hotels.*',
                'villes.*',
                'pays.*'
            )

            ->join('hotels', 'hotels.id_hotel', '=', 'chambres.hotel_id')

            ->join(
                'categoriechambres',
                'categoriechambres.id_categoriechambre',
                '=',
                'chambres.categoriechambre_id'
            )

            ->join(
                'typehebergements',
                'typehebergements.id_typehebergement',
                '=',
                'chambres.typehebergement_id'
            )


            ->join(
                'villes',
                'villes.id_ville',
                '=',
                'chambres.ville_id'
            )


            ->join(
                'pays',
                'pays.id_pays',
                '=',
                'chambres.pays_id'
            )

            ->orderByDesc('chambres.created_at');


        //condition de recherche par mot clé

        if ($search_keyword && !empty($search_keyword)) {
            $chambres->where(function ($c) use ($search_keyword) {
                $c->where('pays.nom_pays', 'like', "%{$search_keyword}%")
                    ->orwhere('villes.nom_ville', 'like', "%{$search_keyword}%")
                    ->orwhere('typehebergements.nom_typehebergement', 'like', "%{$search_keyword}%")
                    ->orwhere('categoriechambres.libelle_categoriechambre', 'like', "%{$search_keyword}%")
                    ->orwhere('hotels.nom_hotel', 'like', "%{$search_keyword}%")
                    ->orwhere('chambres.classe_chambre', 'like', "%{$search_keyword}%")
                    ->orwhere('chambres.nombre_lits_chambre', 'like', "%{$search_keyword}%")
                    ->orwhere('chambres.nombre_places_chambre', 'like', "%{$search_keyword}%")
                    ->orwhere('chambres.prix_standard_chambre', 'like', "%{$search_keyword}%");
            });
        }

        //condition de recherche par prix minimum et maximum

        if ($search_price_min && !empty($search_price_min)) {

            if ($search_price_max && !empty($search_price_max)) {

                $chambres->where(function ($c) use ($search_price_min) {
                    $c->where('chambres.prix_standard_chambre', '>=', $search_price_min);
                });

                $chambres->where(function ($c) use ($search_price_max) {
                    $c->where('chambres.prix_standard_chambre', '<=', $search_price_max);
                });
            }

            //condition de recherche autour de l'hôtel

            if ($search_hotel && !empty($search_hotel)) {
                $chambres->where(function ($c) use ($search_hotel) {

                    $c->whereIn('chambres.hotel_id', explode(",", $search_hotel));
                });
            }


            //condition de recherche autour de la ville

            if ($search_ville && !empty($search_ville)) {
                $chambres->where(function ($c) use ($search_ville) {

                    $c->whereIn('chambres.ville_id', explode(",", $search_ville));
                });
            }

            //condition de recherche autour du pays

            if ($search_pays && !empty($search_pays)) {
                $chambres->where(function ($c) use ($search_pays) {

                    $c->whereIn('chambres.pays_id', explode(",", $search_pays));
                });
            }


            //condition de recherche autour de la catégorie de chambre

            if ($search_categoriechambre && !empty($search_categoriechambre)) {
                $chambres->where(function ($c) use ($search_categoriechambre) {

                    $c->whereIn('chambres.categoriechambre_id', explode(",", $search_categoriechambre));
                });
            }



            //condition de recherche autour du type d'hebergement

            if ($search_typehebergement && !empty($search_typehebergement)) {
                $chambres->where(function ($c) use ($search_typehebergement) {

                    $c->whereIn('chambres.typehebergement_id', explode(",", $search_typehebergement));
                });
            }

            //condition de recherche autour de la classe chambre

            if ($search_classe_chambre && !empty($search_classe_chambre)) {
                $chambres->where(function ($c) use ($search_classe_chambre) {
                    $c->orWhere('chambres.classe_chambre', '=', $search_classe_chambre);
                });
            }



            //condition de recherche autour du nombre de lits

            if ($search_nombre_lits_min && !empty($search_nombre_lits_min)) {
                if ($search_nombre_lits_max && !empty($search_nombre_lits_max)) {
                    $chambres->where(function ($c) use ($search_nombre_lits_min) {
                        $c->orWhere('chambres.nombre_lits_chambre', '>=', $search_nombre_lits_min);
                    });
                    $chambres->where(function ($c) use ($search_nombre_lits_max) {
                        $c->orWhere('chambres.nombre_lits_chambre', '<=', $search_nombre_lits_max);
                    });
                }
            }




            //condition de recherche autour du nombre de places

            if ($search_nombre_places_min && !empty($search_nombre_places_min)) {
                if ($search_nombre_places_max && !empty($search_nombre_places_max)) {
                    $chambres->where(function ($c) use ($search_nombre_places_min) {
                        $c->orWhere('chambres.nombre_places_chambre', '>=', $search_nombre_places_min);
                    });
                    $chambres->where(function ($c) use ($search_nombre_places_max) {
                        $c->orWhere('chambres.nombre_places_chambre', '<=', $search_nombre_places_max);
                    });
                }
            }

            return $chambres->paginate(15);
        }
    }
}
