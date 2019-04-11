<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $table = 'offices';

    protected $fillable = ['code', 'name'];


    //
	public function users()
	{
	  return $this->belongsToMany(User::class);
	}

	public function clients()
	{
  		return $this->hasMany(Client::class);
  	}

	public function calendars()
	{
  		return $this->hasMany(Calendar::class);
  	}
}
