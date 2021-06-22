<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
		'name','max','min','slug','remark','created_by','updated_by','created_at_np','school_id','batch_id','grade_point'
	];

	public function setNameAttribute($value)
	{
	    $this->attributes['name'] = ucwords($value);
	}
	
	public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

	public function getUpdate()
	{
		return $this->belongsTo('App\User','updated_by','id');
	}
}
