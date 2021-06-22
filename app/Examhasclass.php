<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Examhasclass extends Model
{
    protected $fillable = [
        'class_id','shift_id','exam_id','start_time','end_time','result_date','created_by','updated_by','created_at_np','school_id','batch_id'
    ];

    public function getExam()
	{
		return $this->belongsTo('App\Exam','exam_id','id');
	}

	public function getExamList()
	{
		return $this->hasMany('App\Exam','id','exam_id');
	}
    

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

	public function getSection()
	{
		return $this->belongsTo('App\Section','section_id','id');
	}

	public function getClassExam()
	{
		return $this->belongsTo('App\MarkClass','classexam_id','id');
	}

	public function getClassExamMany()
	{
		return $this->hasMany('App\MarkClass','classexam_id','id');
	}

	public function getStudent()
	{
		return $this->belongsTo('App\Student','student_id','id');
	}

	public function getAdmitList()
	{
		return $this->hasMany('App\StudentHasMark','classexam_id','id');
	}

	public function getClassadmit()
	{
		return $this->belongsTo('App\Student','class_id','class_id');
	}

	public function getSectionadmit()
	{
		return $this->belongsTo('App\Student','section_id','section_id');
	}
}
