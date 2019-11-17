<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'users';
    protected $fillable = ['name', 'email'];     
    protected $hidden = ['password'];

    public function role()
    {
        return $this->belongsTo('\App\Role');
    }

    public function categories()
    {
        return $this->hasMany('\App\Category');
    }

    public function password()
    {
        return $this->hasMany('\App\Password');
    }
}

