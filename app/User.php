<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Auth;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','middle_name','last_name','email', 'password','is_active','user_type','school_id','created_at_np','school_id','batch_id','created_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','user_type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    public function getUserStudent()
    {
        return $this->hasOne('App\Student','user_id','id')->where('school_id', Auth::user()->school_id);
    }

    public function getSchoolUserStudent()
    {
        // for palika 
        return $this->hasOne('App\Student','user_id','id');
    }

    public function getUserTeacher()
    {
        return $this->hasOne('App\Teacher','user_id','id')->where('school_id', Auth::user()->school_id);
    }

    public function getUserTeacherInfo()
    {
        return $this->hasOne('App\Teacher','user_id','id');
    }
    // ???????????????????????????????
    public function getUserStudentInfo()
    {
        return $this->hasOne('App\Teacher','user_id','id');
    }
    // ???????????????????????????????

    public function getUserStudentList()
    {
        return $this->hasMany('App\Student','user_id','id');
    }

    public function getSchool()
    {
        return $this->hasOne('App\Setting','id','school_id');
    }

    public function getBatch()
    {
        return $this->hasOne('App\Batch','id','batch_id');
    }

    public function getStudentMarkStatus()
    {
        return $this->hasMany('App\StudentHasObservation','student_id','id');
    }

    public function getUser()
    {
        return $this->belongsTo('App\User','created_by','id');
    }

}
