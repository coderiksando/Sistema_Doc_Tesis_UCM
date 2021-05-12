<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles_Permissions extends Model
{
    protected $table = 'roles_permissions';
    protected $fillable = [
        'id_role', 'id_permission'
    ];

    public function Permission(){
        return $this->belongsTo(Permission::class, 'id_permission', 'id');
    }
    public function Roles(){
        return $this->belongsTo(Roles::class, 'id_role', 'id');
    }
}
