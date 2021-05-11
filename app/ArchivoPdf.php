<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchivoPdf extends Model
{
    protected $table = 'archivopdf';
    protected $fillable = [
        'id', 'id_fit', 'path',
        'filename', 'tipo_pdf', 'created_at',
        'update_at'
    ];

    public function Fit(){
        return $this->belongsTo(Fit::class, 'id_fit', 'id')->first();
    }
    public function AvancesTesis(){
        return $this->hasMany(AvancesTesis::class, 'id_archivo', 'id')->get();
    }
}
