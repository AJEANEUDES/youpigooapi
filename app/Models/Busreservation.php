<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Busreservation extends Model
{
    use HasFactory;

    protected $table = 'busreservations';
    protected $primaryKey = 'id_busreservation';
    protected $guarded = ['created_at', 'updated_at'];



    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public function buses()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }

   



}
