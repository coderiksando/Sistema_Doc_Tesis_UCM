<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fit extends Model
{
    protected $table = 'fit';

    protected $fillable = [
        'id', 'id_vinculacion', 'nota','estado',
        'titulo','tipo','objetivo','descripcion',
        'contribucion', 'aprobado_pg','created_at','updated_at'
    ];

    public function Fit_User(){
        return $this->hasmany(Fit_User::class, 'id_fit', 'id');
    }
}
