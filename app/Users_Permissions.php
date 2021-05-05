<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users_Permissions extends Model
{
    protected $table = 'user_permissions';
    protected $fillable = [
        'id_user','id_permission'
    ];

    public function User(){
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    public function Permission(){
        return $this->belongsTo(Permission::class, 'id_permission', 'id');
    }
}
