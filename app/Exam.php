<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
	protected $fillable = [
		'name','start_date','end_date','slug','created_by','updated_by','created_at_np','school_id','batch_id'
	];

	public function setNameAttribute($value)
	{
	    $this->attributes['name'] = ucwords($value);
	}
	
	public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

	public function getExamClass()
	{
	    return $this->hasMany('App\Examhasclass','exam_id','id');
	}

	public function getClassExamMany()
	{
		return $this->hasMany('App\MarkClass','classexam_id','id');
	}

	public function getExamAllList()
	{
	    return $this->hasMany('App\Examhasclass','exam_id','id')->where('is_active',True)->orderBy('id','DESC');
	}
	
}
