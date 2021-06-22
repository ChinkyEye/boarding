<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $fillable = [
    	'student_id','amount_received','amount_remained','discount','created_by','updated_by','created_at_np','school_id','batch_id','fine','invoice_id'
    ];

    
    public function getUser()
    {
    	return $this->belongsTo('App\User','created_by','id');
    }
    // ??????????????????????
    public function getStudent()
    {
    	return $this->belongsTo('App\Student','student_id','id');
    }
    // ????????????????????????

    public function getBill()
    {
    	return $this->belongsTo('App\Bill','bill_id','id');
    }

    public function getBillUserList()
    {
        return $this->belongsTo('App\Bill','student_id','student_id');
    }

    public function getUserStudentInfo()
    {
        return $this->hasOne('App\User','id','student_id');
    }
}
