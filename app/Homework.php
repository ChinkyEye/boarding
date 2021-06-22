<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    protected $fillable = [
        'class_id','section_id','shift_id','subject_id','description','date','created_by','updated_by','created_at_np','school_id','batch_id','teacher_id','date_eng'
    ];
    

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}
	public function getSubject()
	{
		return $this->belongsTo('App\Subject','subject_id','id');
	}
	public function getTeacher()
	{
		return $this->belongsTo('App\Teacher','teacher_id','user_id');
	}
	public function getShift()
	{
		return $this->belongsTo('App\Shift','shift_id','id');
	}
	public function getClass()
	{
		return $this->belongsTo('App\SClass','class_id','id');
	}
	public function getSection()
	{
		return $this->belongsTo('App\Section','section_id','id');
	}
	public function getClassList()
	{
		return $this->belongsTo('App\SClass','class_id','id');
	}
	public function getTeacherName()
	{
		return $this->belongsTo('App\User','teacher_id','id');
	}

}
