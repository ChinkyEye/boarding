<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarkClass extends Model
{
    protected $fillable = [
        'classexam_id','subject_id','type_id','full_mark','pass_mark','room','created_by','updated_by','created_at_np','school_id','batch_id'
    ];


    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

	public function getClass()
	{
		return $this->belongsTo('App\Examhasclass','classexam_id','id');
	}

	public function getSubject()
	{
		return $this->belongsTo('App\Subject','subject_id','id');
	}

	public function getClassExam()
	{
		return $this->hasMany('App\Examhasclass','id','classexam_id');
	}

	public function getClassExamMark()
	{
		return $this->belongsTo('App\StudentHasMark','subject_id','subject_id');
	}

	public function getSubjectList()
	{
		return $this->belongsTo('App\Subject','id','subject_id');
	}
}
