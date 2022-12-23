<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    use HasFactory;

    protected $table = 'villes';
    protected $primaryKey = 'id_ville';
    protected $guarded = ['created_at', 'updated_at'];

    public function pays()
    {
        return $this->belongsTo(Pays::class, 'pays_id',);
    }


}
