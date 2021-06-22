<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillTotal extends Model
{
    protected $fillable = [
        'student_id','total','invoice_id','created_at_np','school_id','batch_id','created_by','updated_by','bill_date','discount','fine','nettotal','bill_type','user_id','bill_time','bill_date_en'
    ];

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

	public function getStudentBillList()
	{
		return $this->hasMany('App\Bill','invoice_id','invoice_id');
	}

	public function getStudentinfo()
	{
		return $this->hasOne('App\User','id','student_id');
	}

	public function getSchoolinfo()
	{
		return $this->hasOne('App\Setting','id','school_id');
	}

	public function getUserInfo()
	{
		return $this->belongsTo('App\Student','user_id','id');
	}
}
