<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facultad extends Model
{
    protected $table = 'facultad';
    protected $fillable = [
        'id', 'nombre', 'created_at', 'updated_at'
    ];

    public function Escuelas(){
      return $this->hasMany(Escuelas::class, 'id_facultad', 'id');
    }
}
