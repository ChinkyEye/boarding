<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    protected $fillable = [
        'teacher_id', 'period_id','created_by','updated_by','class_id','section_id','shift_id','created_at_np','school_id','batch_id','user_id','teacher_subject_id','day_id'
    ];

    
    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

	public function getClass()
	{
		return $this->belongsTo('App\SClass','class_id','id');
	}

	public function getTeacher()
	{
		return $this->belongsTo('App\Teacher','teacher_id','id');
	}

	public function getPeriod()
	{
		return $this->belongsTo('App\Period','period_id','id');
	}

	public function getSection()
	{
		return $this->belongsTo('App\Section','section_id','id');
	}

	public function getShift()
	{
		return $this->belongsTo('App\Shift','shift_id','id');
	}

	public function getClassList()
	{
		return $this->belongsTo('App\SClass','class_id','id');
	}

	public function getTeacherList()
	{
		return $this->belongsTo('App\Teacher','teacher_id','id');
	}

	public function getTeacherSubjectList()
	{
		return $this->belongsTo('App\Teacher_has_subject','teacher_subject_id','id');
	}

	public function getTeacherName()
	{
		return $this->belongsTo('App\User','user_id','id');
	}
}
