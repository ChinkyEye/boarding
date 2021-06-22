<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherIncome extends Model
{
    protected $fillable = [
         'teacher_id', 'user_id','date','month','amount','grade','mahangi_vatta','durgam_vatta','permanent_allowance','pradyanadhyapak_bhattā','chadparva_kharcha','pension','pension_added','citizen_investment_deduction','loan_deduction','cloth_amount','remark','total1','total2','insurance','total_insurance','pro_f1','pro_f2','total_pf','soc_sec_tax','rcv_amount','sal_tax','net_salary','gross_salary','created_by','updated_by','school_id','batch_id','created_at_np'
    ];

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

	public function getTeacher()
	{
		return $this->belongsTo('App\Teacher','teacher_id','id');
	}
	
	public function getTeacherUser()
	{
	    return $this->hasOne('App\User','id','user_id');
	}

	public function getSchoolInfo()
	{
		return $this->hasOne('App\Setting','id','school_id');
	}

	public function getTeacherBankInfo()
	{
		return $this->hasOne('App\Staff_has_bank','teacher_id','teacher_id');
	}

	public function getShiftTeacherCountList()
	{
	    return $this->hasMany('App\Teacher_has_shift','user_id','user_id');
	}
}
