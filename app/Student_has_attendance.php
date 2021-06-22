<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student_has_attendance extends Model
{
	protected $fillable = [
		'student_id','date','date_en','remark','status','created_by','updated_by','created_at_np','school_id','batch_id','user_id'
	];

	public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

	public function getStudent()
	{
		return $this->belongsTo('App\Student','student_id','id');
	}

	public function getStudentOne()
	{
		return $this->hasOne('App\Student','id','student_id');
	}

	public function getAttendStudentList()
    {
        return $this->hasMany('App\Student','user_id','user_id')->where('is_active',True)->orderBy('id','DESC');
    }

    public function getStudentName()
	{
		return $this->belongsTo('App\User','user_id','id');
	}
}
