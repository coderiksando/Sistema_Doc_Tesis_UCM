<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AvancesTesis extends Model
{
    protected $table = 'avancestesis';
    protected $fillable = [
        'id', 'id_tesis', 'id_archivo',
        'descripcion', 'created_at', 'updated_at'
    ];

    public function Fit(){
        return $this->belongsTo(Fit::class, 'id_tesis', 'id')->first();
    }
    public function ArchivoPdf(){
        return $this->belongsTo(Fit::class, 'id_archivo', 'id')->First();
    }
}
