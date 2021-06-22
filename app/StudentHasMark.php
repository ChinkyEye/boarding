<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentHasMark extends Model
{
    protected $fillable = [
        'classexam_id','subject_id','obtained_mark','created_by','updated_by','student_id','percentage','created_at_np','school_id','batch_id','user_id','invoicemark_id','grade','grade_point','type_id','classmark_id'
    ];


    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

	public function getStudentUser()
    {
        return $this->hasOne('App\User','id','user_id');
    }

	public function getSchool()
	{
		return $this->hasOne('App\Setting','id','school_id');
	}

	public function getClass()
	{
		return $this->belongsTo('App\Examhasclass','classexam_id','id');
	}

	public function getSubject()
	{
		return $this->belongsTo('App\Subject','subject_id','id');
	}

	public function getSubjectOne()
	{
		return $this->hasOne('App\Subject','id','subject_id');
	}

	public function getClassExam()
	{
		return $this->hasMany('App\Examhasclass','id','classexam_id');
	}

	public function getStudent()
	{
		return $this->belongsTo('App\Student','id','student_id');
	}

	public function getStudentOne()
	{
		return $this->hasOne('App\Student','id','student_id');
	}

	public function getClassMark()
	{
		return $this->hasOne('App\MarkClass','subject_id','subject_id');
	}

	public function getClassMarkMany()
	{
		return $this->hasMany('App\MarkClass','subject_id','subject_id');
	}

	public function getClassMarkPP()
	{
		return $this->hasMany('App\SubjectHasTheoryPractical','subject_id','id');
	}

	public function getStudentMarkList()
	{
		return $this->hasOne('App\MarkClass','id','classmark_id');
	}

	public function getStudentObservationPublishedOne()
	{
		return $this->hasOne('App\StudentHasObservation','invoicemark_id','invoicemark_id');
	}

	public function getStudentObservationMark()
	{
		return $this->hasMany('App\ObservationHasMark','invoicemark_id','invoicemark_id');
	}

	public function getStudentMarkExam()
    {
        // return $this->hasManyThrough(
						  //       	'App\StudentHasMark', 
						  //       'App\Examhasclass', 
					   //          'id', 
					   //      	'classexam_id',
						  //       	'id',
						  //       	'student_id'
						  //       );
    	return $this->hasManyThrough(Examhasclass::class, Exam::class, 'exam_id','classexam_id');
    }
}
