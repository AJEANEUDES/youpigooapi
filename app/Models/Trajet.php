<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trajet extends Model
{
    use HasFactory;

    protected $table = 'trajets';
    protected $primaryKey =  'id_trajet';

    protected $guarded = ['created_at', 'updated_at'];


    public function buses()
    {
        return $this->belongsTo(Bus::class, 'ubus_id');
    }

    
    public function regions()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function prefectures()
    {
        return $this->belongsTo(Prefecture::class, 'prefecture_id');
    }
    

}
