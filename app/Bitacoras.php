<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bitacoras extends Model
{
    protected $table = 'bitacoras';

    protected $fillable = [
        'id', 'id_tesis', 'comentario',
        'acuerdo', 'fecha', 'created_at', 'updated_at'
    ];

    public function Fit(){
        return $this->belongsTo(Fit::class, 'id_tesis', 'id');
    }
}
