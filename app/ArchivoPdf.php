<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchivoPdf extends Model
{
    protected $table = 'archivopdf';
    protected $fillable = [
        'id', 'id_fit', 'path',
        'filename', 'tipo_pdf', 'created_at',
        'updated_at'
    ];

    public function Fit(){
        return $this->belongsTo(Fit::class, 'id_fit', 'id');
    }
    public function AvancesTesis(){
        return $this->hasMany(AvancesTesis::class, 'id_archivo', 'id');
    }
    public function Revision_Comision(){
        return $this->hasOne(Revision_Comision::class, 'id_archivo', 'id');
    }
}
