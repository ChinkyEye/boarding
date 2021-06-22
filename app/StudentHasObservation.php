<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentHasObservation extends Model
{
    protected $fillable = [
        'student_id','invoicemark_id','observation_id','is_published','created_by','updated_by','created_at_np','school_id','batch_id','exam_id'

    ];


    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

	public function getStudentObservationMark()
	{
		return $this->hasMany('App\ObservationHasMark','invoicemark_id','invoicemark_id');
	}

	public function getStudent()
	{
		return $this->hasMany('App\Student','user_id','student_id');
	}

	
}
