<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users_Roles extends Model
{
    protected $table = 'user_roles';
    protected $fillable = [
        'id_user','id_roles'
    ];

    public function User(){
        return $this->belongsTo(User::class, 'id_user', 'id_user')->first();
    }
    public function Roles(){
        return $this->belongsTo(Roles::class, 'id_roles', 'id')->first();
    }
}
