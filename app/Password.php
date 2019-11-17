<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Password extends Model
{
    protected $table = 'passwords';
	protected $fillable = ['title', 'password','user_id','category_id'];

    public function users()
    {
    	return $this->belongsTo('\App\User');
    }

    public function categories()
    {
    	return $this->belongsTo('\App\Category');
    }
}