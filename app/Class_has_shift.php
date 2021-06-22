<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Class_has_shift extends Model
{
	protected $fillable = [
		'class_id','shift_id','created_by','updated_by','created_at_np','school_id','batch_id'
	];

	public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}
	public function getShift()
	{
		return $this->belongsTo('App\Shift','shift_id','id');
	}
	public function getClass()
	{
		return $this->belongsTo('App\SClass','class_id','id');
	}

	public function getClassSection()
    {
        return $this->hasMany('App\Class_has_section','class_id','class_id')->orderBy('id','DESC');
    }
    public function getShiftList()
    {
    	return $this->hasMany('App\Shift', 'id', 'shift_id');
    } 
    public function getClassList()
    {
    	return $this->hasMany('App\SClass', 'id', 'class_id');
    }
}
