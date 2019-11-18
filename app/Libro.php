<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
	protected $table = 'libros';
	protected $fillable = ['title', 'user_id'];
	public function user()
    {
    	return $this->belongsTo('\App\User');
    }
    public function categories()
    {
    	return $this->belongsTo('\App\Category');
    }
}
