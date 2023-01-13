<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $table = 'buses';
    protected $primaryKey =  'id_bus';

    protected $guarded = ['created_at', 'updated_at'];

    public function compagnies()
    {
        return $this->belongsTo(Compagnie::class, 'compagnie_id');
    }


}
