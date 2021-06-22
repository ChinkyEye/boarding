<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
    'teacher_id', 'topic_id','amount','invoice_id','created_by','updated_by','sort_id','created_at_np','school_id','batch_id',
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
		return $this->belongsTo('App\Teacher','user_id','id');
	}

	public function getUserTeacher()
	{
	    return $this->hasOne('App\User','id','teacher_id');
	}

	public function getTopic()
	{
		return $this->belongsTo('App\SalaryTopic','topic_id','id');
	}
}
