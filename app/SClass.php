<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SClass extends Model
{
    protected $fillable = [
        'name', 'slug','created_by','updated_by','created_at_np','school_id','batch_id'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

    public function getClassList()
    {
        return $this->hasMany('App\Class_has_shift','class_id','id')->where('is_active',True)->orderBy('id','DESC');
    }
    public function getClassSectionList()
    {
        return $this->hasMany('App\Class_has_section','class_id','id')->where('is_active',True)->orderBy('id','DESC');
    }
    public function getRoutineClassList()
    {
        return $this->hasMany('App\Routine','class_id','id')->where('is_active',True)->orderBy('id','DESC');
    } 
    public function getPeriodClassList()
    {
        return $this->hasMany('App\Teacher_has_period','class_id','id')->where('is_active',True)->orderBy('id','DESC');
    }

    public function getSubjectList()
    {
        return $this->hasMany('App\Subject','class_id','id')->where('is_active',True)->orderBy('id','DESC');
    }
    public function getExamClass()
    {
        return $this->hasMany('App\Examhasclass','class_id','id')->where('is_active',True)->orderBy('id','DESC');
    }

    public function getClassSubject()
    {
      return $this->hasMany('App\Subject','class_id','id');
    }

    public function getCountShift()
    {
        return $this->hasMany('App\Class_has_shift','class_id','id');
    }

    public function getCountSection()
    {
        return $this->hasMany('App\Class_has_section','class_id','id');
    }

   
}
