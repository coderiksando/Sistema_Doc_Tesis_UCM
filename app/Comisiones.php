<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comisiones extends Model
{
    protected $table = 'comisiones';

    protected $fillable = [
        'id','id_tesis', 'id_profesor1','id_profesor2',
        'p_externo', 'correo_p_externo', 'institucion_p_externo',
        'created_at', 'updated_at'
    ];

    public function UserP1(){
        return $this->belongsTo(User::class, 'id_profesor1', 'id_user')->first();
    }
    public function UserP2(){
        return $this->belongsTo(User::class, 'id_profesor2', 'id_user')->first();
    }
    public function Fit(){
        return $this->belongsTo(Fit::class, 'id_tesis', 'id')->first();
    }
}
