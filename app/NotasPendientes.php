<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotasPendientes extends Model
{
    protected $table = 'notaspendientes';

    protected $fillable = [
        'id', 'id_tesis', 'id_alumno','fecha_presentacion', 'fecha_propuesta',
        'fecha_autorizada', 'fecha_prorroga', 'estado',
        'created_at', 'updated_at'
    ];

    public function Fit(){
        return $this->belongsTo(Fit::class, 'id_tesis', 'id');
    }
    public function Alumno(){
        return $this->belongsTo(User::class, 'id_alumno', 'id_user');
    }
}
