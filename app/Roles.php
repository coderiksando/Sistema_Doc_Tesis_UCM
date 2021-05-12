<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';
    protected $fillable = [
        'id', 'slug', 'name', 'created_at', 'updated_at'
    ];

    public function Users_Roles(){
        return $this->hasMany(Users_Roles::class, 'id_roles', 'id');
    }
    public function Roles_Permissions(){
        return $this->hasMany(Roles_Permissions::class, 'id_role', 'id');
    }
}
