<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
		'name','slug','book_no','class_id','subject_id','publisher','auther','quantity','created_by','updated_by','created_at_np','school_id','batch_id','book_code'
	];
	
	public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

	public function getBookIssue(){
		return $this->hasMany('App\IssueBook','book_id','id');
	}

	public function getBookIssueHisab(){
		return $this->hasMany('App\IssueBook','book_id','id')->where('is_return',0);
	}

	public function getBookIssueOne(){
		return $this->hasOne('App\IssueBook','book_id','id');
	}
	public function getClass()
	{
		return $this->belongsTo('App\SClass','class_id','id');
	}
	public function getSubject()
	{
		return $this->belongsTo('App\Subject','subject_id','id');
	}
}
