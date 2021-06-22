<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'name','slug','created_by','updated_by','sort_id','created_at_np','school_id','batch_id'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

    public function getShiftList()
    {
        return $this->hasMany('App\Class_has_shift','shift_id','id')->where('is_active',True)->orderBy('id','DESC');
    }

    public function getExamShiftList()
    {
        return $this->hasMany('App\Examhasclass','shift_id','id')->where('is_active',True)->orderBy('id','DESC');
    }

    public function getTeacherShiftList()
    {
        return $this->hasMany('App\Teacher_has_shift','shift_id','id')->orderBy('id','DESC');
    }
    
}
 
    