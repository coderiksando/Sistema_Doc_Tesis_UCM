<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AreaTesis extends Model
{
    protected $table = 'areatesis';

    protected $fillable = [
        'id', 'id_escuela', 'nombre',
        'created_at', 'updated_at'
    ];

    public function Escuelas(){
        return $this->belongsTo(Escuelas::class, 'id_escuela', 'id')->first();
    }
    public function Fit_Area(){
        return $this->hasMany(Fit_Area::class, 'id_area', 'id')->get();
    }
}
