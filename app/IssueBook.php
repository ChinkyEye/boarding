<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IssueBook extends Model
{
    protected $fillable = [
		'class_id','section_id','shift_id','student_id','book_id','issue_date','issue_date_en','return_date','created_by','updated_by','created_at_np','school_id','batch_id','user_id','return_date_en'
	];

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
	public function getStudent()
	{
		return $this->belongsTo('App\Student','student_id','id');
	}
	public function getStudentList()
	{
		return $this->hasMany('App\Student','id','student_id');
	}
	public function getBookList()
	{
		return $this->hasMany('App\Book','id','book_id');
	}
	public function getBook()
	{
		return $this->belongsTo('App\Book','book_id','id');
	}

	public function getStudentUser()
    {
        return $this->hasOne('App\User','id','user_id');
    }
}
