<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users_Roles extends Model
{
    protected $table = 'users_roles';
    protected $fillable = [
        'id_user','id_roles'
    ];

    public function User(){
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    public function Roles(){
        return $this->belongsTo(Roles::class, 'id_roles', 'id');
    }
}
