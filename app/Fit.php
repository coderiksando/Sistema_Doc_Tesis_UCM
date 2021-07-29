<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fit extends Model
{
    protected $table = 'fit';

    protected $fillable = [
        'id', 'id_vinculacion', 'id_p_guia', 'id_p_co_guia', 'id_escuela', 'nota',
        'estado', 'titulo','tipo','objetivo_general', 'objetivo_especifico', 'descripcion',
        'contribucion', 'aprobado_pg', 'motivo_pg', 'created_at','updated_at'
    ];

    public function Fit_User(){
        return $this->hasMany(Fit_User::class, 'id_fit', 'id');
    }
    public function Fit_Area(){
        return $this->hasMany(Fit_Area::class, 'id_fit', 'id');
    }
    public function Bitacoras(){
        return $this->hasMany(Bitacoras::class, 'id_tesis', 'id');
    }
    public function NotasPendientes(){
        return $this->hasOne(NotasPendientes::class, 'id_tesis', 'id');
    }
    public function Vinculaciones(){
        return $this->belongsTo(Vinculaciones::class, 'id_vinculacion', 'id');
    }
    public function ArchivoPdf(){
        return $this->hasMany(ArchivoPdf::class, 'id_fit', 'id');
    }
    public function Comisiones(){
        return $this->hasOne(Comisiones::class, 'id_tesis', 'id');
    }
    public function AvancesTesis(){
        return $this->hasMany(AvancesTesis::class, 'id_tesis', 'id');
    }
    public function User_P_Guia(){
        return $this->belongsTo(User::class, 'id_p_guia', 'id_user');
    }
    public function User_P_Coguia(){
        return $this->belongsTo(User::class, 'id_p_co_guia', 'id_user');
    }
    public function Escuela(){
        return $this->belongsTo(Escuelas::class, 'id_escuela', 'id');
    }
    public function getAlumnos(){
        return $this->Fit_User->map(function ($item, $key){
            return $item->User;
        });
    }
    public function Revision_Comision(){
        return $this->hasMany(Revision_Comision::class, 'id_fit', 'id');
    }
}
