<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Categoriechambre extends Model
{
    use HasFactory;

    protected $table = 'categoriechambres';
    protected $primaryKey = 'id_categoriechambre';
    protected $guarded = ['created_at', 'updated_at'];


    //fonction getAllCategorieChambres pour voir la liste de tous les catégories de chambres 
    //
    public function createdby()
    {
        return $this->belongsToMany(Categoriechambre::class, 'id_categoriechambre');
    }

    
    public static function getAllCategorieChambres(

        $search_keyword,
        $search_libelle = null,
        $search_description = null


    ) {

        $categoriechambres = DB::table('categoriechambres')
            ->where('status_categoriechambre', true)
            ->select('categoriechambres.*')
            ->orderByDesc('categoriechambres.created_at');


        if ($search_keyword && !empty($search_keyword)) {
            $categoriechambres->where(function ($c) use ($search_keyword) {
                $c->where('hotels.nom_hotel', 'like', "%{$search_keyword}%");
            });
        }


        if ($search_libelle && !empty($search_libelle)) {
            $categoriechambres->where(function ($c) use ($search_libelle) {
                $c->orWhere('categoriechambres.libelle_categoriechambre', '=', $search_libelle);
            });
        }


        if ($search_description && !empty($search_description)) {
            $categoriechambres->where(function ($c) use ($search_description) {
                $c->orWhere('categoriechambres.description_categoriechambre', '=', $search_description);
            });
        }



        return $categoriechambres->paginate(15);
    }











}
