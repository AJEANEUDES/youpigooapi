<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table = 'password_resets';
    const UPDATED_AT = null;
    
    // protected $fillable = [
    //     'email_user',
    // ];
    protected $primaryKey = 'id_password_reset';
    protected $guarded = ['created_at', 'updated_at'];

}
