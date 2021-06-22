<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Student extends Model
{
	protected $fillable = [
        'slug','roll_no','class_id','section_id','shift_id','gender','dob','register_id','register_date','image','student_code','document_name','document_photo','created_by','updated_by','created_at_np','user_type','user_id','phone_no','address','actual_roll_no','school_id','batch_id'
    ];
    

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

	public function getSchool()
	{
		return $this->hasOne('App\Setting','id','school_id');
	}

	public function getBatch()
	{
		return $this->hasOne('App\Batch','id','batch_id');
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
	public function Student_has_parent()
	{
		return $this->hasOne('App\Student_has_parent','student_id','id');
	}

	public function getTeacherStudent()
	{
		return $this->belongsTo('App\Teacher_has_period','student_id','id');
	}

	public function getStudentAttendance()
	{
		return $this->hasOne('App\Student_has_attendance','student_id','id');
	}
	public function getStudentAttendanceList()
	{
		return $this->belongsTo('App\Student_has_attendance','student_id','id');
	}

	public function getStudentAttendanceAjax()
    {
        return $this->hasMany('App\Student_has_attendance','student_id','id');
    }

	public function setFirstNameAttribute($value)
	{
	    $this->attributes['first_name'] = ucwords($value);
	}

	public function setMiddleNameAttribute($value)
	{
	    $this->attributes['middle_name'] = ucwords($value);
	}

	public function setLastNameAttribute($value)
	{
	    $this->attributes['last_name'] = ucwords($value);
	}

	public function getStudent()
	{
		return $this->belongsTo('App\Student_has_attendance','student_id','id');
	}

	public function getStudentUser()
    {
        return $this->hasOne('App\User','id','user_id');
    }

    public function getObservationMark()
    {
        return $this->hasMany('App\ObservationHasMark','student_id','user_id');
    }

	public function getBillTotal()
	{
		
		$date =Carbon::now()->month;
		// dd($date);
		return $this->hasMany('App\BillTotal','student_id','user_id')->whereMonth('created_at',$date)->where('bill_type','1');
	}

	public function getMark()
	{
		return $this->hasMany('App\StudentHasObservation','student_id','user_id')->where('is_published','1');
	}

	public function getMarkPubUnpub()
	{
		return $this->hasMany('App\StudentHasObservation','student_id','user_id');
	}


	public function getStudentMark()
	{
		return $this->hasMany('App\StudentHasMark','user_id','user_id');
	}

	public function getStudentMarkList()
	{
		return $this->belongsTo('App\StudentHasMark','user_id','user_id');
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
    	return $this->hasManyThrough(StudentHasMark::class, Examhasclass::class, 'id','classexam_id');
    	// return $this->belongsToMany(StudentHasMark::class)->using(Examhasclass::class);
    }

    // public function getStudentViaBatch(){
    // 	return $this->hasMany('App\UserHasBatch','user_id','user_id');
    // }
    
    

	
}