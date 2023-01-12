<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GestionnaireHotel extends Model
{
    use HasFactory;

    
    protected $table = 'gestionnaires_hotels';
    protected $primaryKey = 'id_gestionnaire';
    protected $guarded = ['created_at', 'updated_at'];


    public function hotels()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }




    
}
