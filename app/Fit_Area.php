<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fit_Area extends Model
{
    protected $table = 'fit_area';
    protected $fillable = [
        'id_area', 'id_fit', 'created_at', 'updated_at'
    ];
}
