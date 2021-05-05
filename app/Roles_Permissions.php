<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles_Permissions extends Model
{
    protected $table = 'roles_permissions';
    protected $fillable = [
        'id_role', 'id_permission'
    ];
}
