<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentosEscuela extends Model
{
    protected $fillable = [
        'id', 'id_escuela', 'descripcion', 'filename', 'path', 'created_at', 'updated_at'
    ];

    public function Escuelas(){
        return $this->belongsTo(Escuelas::class, 'id_escuela', 'id');
    }
}
