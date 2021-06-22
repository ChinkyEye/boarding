<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student_has_parent extends Model
{
    protected $fillable = [
		'father_name','mother_name','address','nationality_id','student_id','image','image_enc' ,'slug','created_by','updated_by','created_at_np','user_id','contact_no','school_id','batch_id'
	];

    public function user()
	{
		return $this->belongsTo('App\User','created_by','id');
	}
	public function getUser()
	{
		return $this->belongsTo('App\User','teacher_id','id');
	}
	public function Student_has_parent()
	{
		return $this->belongsTo('App\Nationality','nationality_id','id');
	}
}
