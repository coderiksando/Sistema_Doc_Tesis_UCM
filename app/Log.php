<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'id', 'user_id', 'rol', 'actividad', 'ip', 'target', 'created_at', 'updated_at'
    ];
}
