<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prefecture extends Model
{
    use HasFactory;

    protected $table = 'prefectures';
    protected $primaryKey =  'id_prefecture';

    protected $guarded = ['created_at', 'updated_at'];

    public function regions()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }


}
