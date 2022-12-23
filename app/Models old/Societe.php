<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Societe extends Model
{
    use HasFactory;

    protected $table = 'societes';
    protected $primaryKey = 'id_societe';
    protected $guarded = ['created_at', 'updated_at'];

 
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }


    public function typehebergement()
    {
        return $this->belongsTo(Typehebergement::class, 'typehebergement_id');
    }
    
    
    public function ville()
    {
        return $this->belongsTo(Ville::class, 'ville_id');
    }
    

    public function pays()
    {
        return $this->belongsTo(Pays::class, 'pays_id');
    }
    

}
