<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notice_for extends Model
{
    protected $fillable = [
		'notice_id', 'shift_id','class_id','section_id','created_at_np','school_id','batch_id','created_by','updated_by'
	];
	public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}
	public function getNotice()
	{
		return $this->belongsTo('App\Notice','notice_id','id');
	}
	public function getShift()
	{
		return $this->hasMany('App\Shift','shift_id','id');
	}
	public function getClass()
	{
		return $this->hasMany('App\SClass','class_id','id');
	}
	public function getSection()
	{
		return $this->hasMany('App\Section','section_id','id');
	}

	public function getClassOne()
	{
		return $this->hasOne('App\SClass','id','class_id');
	}
}
