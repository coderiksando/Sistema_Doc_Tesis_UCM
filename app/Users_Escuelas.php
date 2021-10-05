<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users_Escuelas extends Model
{
    protected $table = 'users_escuelas';
    protected $fillable = [
        'id_user','id_escuela'
    ];

    public function User(){
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    public function Escuelas(){
        return $this->belongsTo(Escuelas::class, 'id_escuela', 'id');
    }
}
