<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    
    protected $fillable = [
        'student_id', 'topic_id','amount','invoice_id','created_at_np','school_id','batch_id','created_by','updated_by'
    ];

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}
	//student ko user id sanga bill ma vako studentid milako 
	//studentid ma user ko forign key xa
	public function getStudent()
	{
		return $this->hasOne('App\Student','user_id','student_id');
	}

	public function getUserStudentInfo()
	{
		return $this->hasOne('App\User','student_id','id');
	}

	public function getTopic()
	{
		return $this->belongsTo('App\Topic','topic_id','id');
	}

	public function getBillInfo()
	{
		return $this->hasOne('App\BillTotal','invoice_id','invoice_id');
	}

	public function getUserInfo()
	{
		return $this->hasOne('App\User','id','student_id');
	}

	public function getSchoolInfo()
	{
		return $this->hasOne('App\Setting','id','school_id');
	}
}
