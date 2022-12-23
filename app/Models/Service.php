<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';
    protected $primaryKey = 'id_service';
    protected $guarded = ['created_at', 'updated_at'];

 
    public function chambre()
    {
        return $this->belongsTo(Chambre::class, 'chambre_id');
    }

    

    public static function getAllServices()
    {
        
    }


    
}
