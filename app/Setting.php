<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'user_type','school_code','type_of_school','level','running_class','school_name','slug','address','phone_no','email','principal_name','url','image','is_active','created_by','updated_by','created_at_np'
    ];

    public function setSchoolNameAttribute($value)
    {
        $this->attributes['school_name'] = ucwords($value);
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = ucwords($value);
    }

    public function setPrincipalNameAttribute($value)
    {
        $this->attributes['principal_name'] = ucwords($value);
    }
    public function getUser()
    {
        return $this->belongsTo('App\User','created_by','id');
    }

    public function getSchoolListMany()
    {
        return $this->hasMany('App\User','school_id');
    }

    public function getCountSection()
    {
        return $this->hasMany('App\User','school_id','id')->where('user_type',1)->where('is_active',True);
    }

    public function getAdminInfo()
    {
        return $this->hasOne('App\User','school_id','id')->where('user_type',1)->where('is_active',True);
    }


}
