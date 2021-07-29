<?php

namespace App;

use App\ClavesMultiples;
use Illuminate\Database\Eloquent\Model;

class Revision_Comision extends ClavesMultiples
{
    protected $table = 'revision_comision';
    protected $primaryKey = ['id_fit', 'id_user', 'id_archivo'];
    public $incrementing = false;

    protected $fillable = [
        'id_fit', 'id_user', 'id_archivo', 'tipo',
        'comentario', 'created_at','updated_at'
    ];

    public function Fit(){
        return $this->belongsTo(Fit::class, 'id_fit', 'id');
    }
    public function ArchivoPdf(){
        return $this->belongsTo(ArchivoPdf::class, 'id_archivo', 'id');
    }
    public function User(){
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

}
