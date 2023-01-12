<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    protected $primaryKey = 'id_reservation';
    protected $guarded = ['created_at', 'updated_at'];



    // public function users()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }

    
    public function hotels()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function chambres()
    {
        return $this->belongsTo(Chambre::class, 'chambre_id');
    }





    
}
