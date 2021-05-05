<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $fillable = [
        'id', 'slug', 'name', 'created_at', 'updated_at'
    ];

    public function Users_Permissions(){
        return $this->hasMany(Users_Permissions::class, 'id_permission', 'id');
    }
    public function Roles_Permissions(){
        return $this->hasMany(Roles_Permissions::class, 'id_permission', 'id');
    }
}
