<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Escuelas extends Model
{
    protected $table = 'escuelas';
    protected $fillable = [
        'id', 'id_facultad', 'nombre', 'created_at', 'updated_at'
    ];

    public function Facultad(){
      return $this->belongsTo(Facultad::class, 'id_facultad', 'id')->first();
    }
    public function User(){
        return $this->hasMany(User::class, 'id_escuela', 'id')->get();
    }
    public function AreaTesis(){
        return $this->hasMany(AreaTesis::class, 'id_escuela', 'id')->get();
    }
}
