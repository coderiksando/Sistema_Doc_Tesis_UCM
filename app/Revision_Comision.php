<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Revision_Comision extends Model
{
    protected $table = 'revision_comision';

    protected $fillable = [
        'id_fit', 'id_user', 'id_archivo', 'tipo',
        'comentario', 'created_at','updated_at'
    ];

    public function Fit(){
        return $this->belongsTo(Fit::class, 'id_fit', 'id');
    }
    public function ArchivoPdf(){
        return $this->belongsTo(User::class, 'id_archivo', 'id');
    }
    public function ComisionesP1(){
        return $this->belongsTo(Comisiones::class, 'id_user', 'id_profesor1');
    }
    public function ComisionesP2(){
        return $this->belongsTo(Comisiones::class, 'id_user', 'id_profesor2');
    }

}
