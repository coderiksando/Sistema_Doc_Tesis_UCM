<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fit_User extends Model
{
    protected $table = 'fit_user';
    protected $fillable = [
        'id_user', 'id_fit', 'created_at', 'updated_at'
    ];
    // protected $primaryKey = ['id_user','id_fit'];

    public function User(){
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    public function Fit(){
        return $this->belongsTo(Fit::class, 'id_fit', 'id');
    }
}
