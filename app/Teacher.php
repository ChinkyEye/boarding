<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'slug','created_by','updated_by','sort_id','designation','uppertype','t_designation','training','qualification','dob','phone','address','gender','religion','nationality_id','marital_status','j_date','p_date','image','government_id','insurance_id','pan_id','cinvestment_id','pfund_id','teacher_code','created_at_np','user_id','school_id'
    ];

    public function getTeacherPeriod()
    {
      return $this->hasMany('App\Teacher_has_period','teacher_id','user_id');
    }
    public function getBank()
    {
      return $this->hasOne('App\Staff_has_bank','teacher_id','id');
    }

    public function getTeacherAttendanceAjax()
    {
        return $this->hasMany('App\Teacher_has_attendance','teacher_id','id');
    }

    public function getShiftTeacherList()
    {
        return $this->belongsTo('App\Teacher_has_shift','teacher_id','id')->where('is_active',True)->orderBy('id','DESC');
    }

    public function getShiftTeacherManyList()
    {
        return $this->hasMany(Teacher_has_shift::class)->where('is_active',True)->orderBy('id','DESC');
    }

    public function getShift()
    {
        return $this->hasMany('App\Shift','shift_id','id')->where('is_active',True)->orderBy('id','DESC');
    }

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

    public function setFNameAttribute($value)
    {
        $this->attributes['f_name'] = ucwords($value);
    }

    public function setMNameAttribute($value)
    {
        $this->attributes['m_name'] = ucwords($value);
    }

    public function setLNameAttribute($value)
    {
        $this->attributes['l_name'] = ucwords($value);
    }

    public function getTeacherAttendance()
    {
        return $this->hasOne('App\Teacher_has_attendance','teacher_id','id');
        // return $this->hasOne('App\Teacher_has_attendance','teacher_id','id')->where('date',date('Y-m-d'));
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = ucwords($value);
    }

    public function setDesignationAttribute($value)
    {
        $this->attributes['designation'] = ucwords($value);
    }

    public function setQualificationAttribute($value)
    {
        $this->attributes['qualification'] = ucwords($value);
    }

    public function setTrainingAttribute($value)
    {
        $this->attributes['training'] = ucwords($value);
    }

    public function setMaritalStatusAttribute($value)
    {
        $this->attributes['marital_status'] = ucwords($value);
    }

    public function setTDesignationAttribute($value)
    {
        $this->attributes['t_designation'] = ucwords($value);
    }

    public function getTeacherClassSalaryList()
    {
        return $this->hasMany('App\Teacher_has_period','teacher_id','user_id')->where('is_active',True)->orderBy('id','DESC');
    }

    public function getClassCount()
    {
        return $this->hasMany('App\Teacher_has_period','teacher_id','user_id');
    }

    public function getShiftTeacherCountList()
    {
        return $this->hasMany('App\Teacher_has_shift','teacher_id','id');
    }

    public function getShiftTeacherSubjectCountList()
    {
        return $this->hasMany('App\Teacher_has_subject','teacher_id','user_id');
    }

    public function getTeacherPeriodCount()
    {
        return $this->hasMany('App\Teacher_has_period','teacher_id','user_id');
    }

    public function getTeacherUser()
    {
        return $this->hasOne('App\User','id','user_id');
    }
    
    public function getSchool()
    {
        return $this->hasOne('App\Setting','id','school_id');
    }

    public function getNationality()
    {
        return $this->hasOne('App\Nationality','id','nationality_id');
    }

    public function getTeacherIncome()
    {
        return $this->hasOne('App\TeacherIncome','user_id','user_id');
    }

    public function getTeacherFromBatch(){
        return $this->belongsTo(UserHasBatch::class,'user_id','user_id');
    }
}
