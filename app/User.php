<?php

namespace App;

use App\File;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'id_user','rut', 'nombres', 'apellidos',
        'id_escuela', 'id_files', 'email', 'email_verified_at',
        'password', 'remember_token', 'state', 'birthday',
        'telefono', 'direccion', 'grado_academico', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function Escuelas(){
        return $this->belongsTo(Escuelas::class, 'id_escuela', 'id');
    }
    public function File(){
        return $this->belongsTo(File::class, 'id_files', 'id');
    }
    public function Users_Roles(){
        return $this->hasMany(Users_Roles::class, 'id_user', 'id_user');
    }
    public function Users_Permissions(){
        return $this->hasMany(Users_Permissions::class, 'id_user', 'id_user');
    }
    public function Fit_User(){
        return $this->hasMany(Fit_User::class, 'id_user', 'id_user');
    }
    public function ComisionesP1(){
        return $this->hasMany(Comisiones::class, 'id_profesor1', 'id_user');
    }
    public function ComisionesP2(){
        return $this->hasMany(Comisiones::class, 'id_profesor2', 'id_user');
    }



    // protected $primaryKey = 'id_user';
    // /**
    //  * The attributes that are mass assignable.
    //  *
    //  * @var array
    //  */
    // protected $fillable = [
    //     'name', 'email', 'password',
    // ];

    // /**
    //  * The attributes that should be hidden for arrays.
    //  *
    //  * @var array
    //  */
    // protected $hidden = [
    //     'password', 'remember_token',
    // ];

    // /**
    //  * The attributes that should be cast to native types.
    //  *
    //  * @var array
    //  */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    // protected $appends = ['fullname'];

    // public function getFullNameAttribute()
    // {
    // return "{$this->nombres} {$this->apellidos}";
    // }
    // public function file()
    // {
    //     return $this->belongsTo(File::class, 'id_files');
    // }





}
