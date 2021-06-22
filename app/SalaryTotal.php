<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryTotal extends Model
{
    protected $fillable = [
        'teacher_id','total','invoice_id','created_at_np','school_id','batch_id','created_by','updated_by','bill_date','tds','nettotal','salary_type'
    ];

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}
	public function getTeacherSalaryList()
	{
		return $this->hasMany('App\Salary','invoice_id','invoice_id');
	}

}
