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
}
