<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fecha_defensa extends Model
{
    protected $table = 'fecha_defensa';
    protected $fillable = [
        'id','id_fit', 'fecha', 'sala', 'created_at', 'updated_at'
    ];

    public function Fit(){
        return $this->belongsTo(Fit::class, 'id_fit', 'id');
    }
}
