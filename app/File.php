<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';
    protected $fillable = [
        'id', 'path', 'filename', 'created_by',
        'updated_by', 'created_at', 'updated_at'
    ];

    public function User(){
        return $this->hasMany(User::class, 'id_files', 'id')->get();
    }
}
