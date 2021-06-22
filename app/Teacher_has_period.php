<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher_has_period extends Model
{
    protected $fillable = [
        'teacher_id', 'class_id','created_by','updated_by','sort_id','shift_id','section_id','created_at_np','school_id','batch_id'
    ];

    public function sclass()
    {
    	return $this->belongsTo('App\SClass','class_id','id');
    }

    public function getBookHasAuthor()
    {
      return $this->hasMany('App\Teacher_has_period','teacher_id','id');
    }

    public function teacher()
    {
    	return $this->belongsTo('App\Teacher','teacher_id','user_id');
    }

    public function shift()
    {
    	return $this->belongsTo('App\Shift','shift_id','id');
    }

    public function getClass()
    {
    	return $this->belongsTo('App\SClass','class_id','id');
    }

    public function section()
    {
        return $this->belongsTo('App\Section','section_id','id');
    }

    public function getSubject()
    {
        return $this->belongsTo('App\Subject','section_id','id');
    }

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

    public function getTeacherUser()
    {
        return $this->belongsTo('App\User','teacher_id','id');
    }

    public function getTeacherSubject()
    {
      return $this->hasMany('App\Teacher_has_subject','teacher_period_id','id');
    }

}
