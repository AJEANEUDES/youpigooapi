<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voiture extends Model
{
    use HasFactory;

    protected $table = 'voitures';
    protected $primaryKey =  'id_voiture';

    protected $guarded = ['created_at', 'updated_at'];

    public function compagnies()
    {
        return $this->belongsTo(Compagnie::class, 'compagnie_id');
    }


}
