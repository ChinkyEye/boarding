<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher_has_attendance extends Model
{
    protected $fillable = [
        'teacher_id', 'date','date_en','status','created_by','updated_by','created_at_np','school_id','batch_id','user_id','shift_id'
    ];

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

	public function getUpdatedUser()
	{
		return $this->belongsTo('App\User','updated_by','id');
	}

	public function getTeacher()
	{
		return $this->belongsTo('App\Teacher','teacher_id','id');
	}

	// check
	public function getTeacherOne()
	{
		return $this->hasOne('App\Teacher','teacher_id','id');
	}
	
	public function getTeacherInfoOne()
	{
		return $this->hasOne('App\Teacher','id','teacher_id');
	}

	public function getAttendShiftTeacherList()
    {
        return $this->hasMany('App\Teacher_has_shift','teacher_id','teacher_id');
    }

    public function getTeacherName()
	{
		return $this->hasOne('App\User','id','user_id');
	}
}
