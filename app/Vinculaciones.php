<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vinculaciones extends Model
{
    protected $table = 'vinculaciones';
    protected $fillable = [
        'id','tipo', 'nombre', 'descripcion',
        'estado', 'created_at', 'updated_at'
    ];
}
