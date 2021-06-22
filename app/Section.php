<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
	protected $fillable = [
    'name', 'slug','created_by','updated_by','sort_id','created_at_np','school_id','batch_id'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }
    
	public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

    public function getSectionList()
    {
        return $this->hasMany('App\Class_has_section','section_id','id')->where('is_active',True)->orderBy('id','DESC');
    } 
    public function getRoutineSectionList()
    {
        return $this->hasMany('App\Routine','section_id','id')->where('is_active',True)->orderBy('id','DESC');
    }

    public function getPeriodClassList()
    {
        return $this->hasMany('App\Teacher_has_period','class_id','id')->where('is_active',True)->orderBy('id','DESC');
    }

    public function getRoutineSectionListTeacher()
    {
        return $this->hasMany('App\Teacher_has_period','section_id','id')->where('is_active',True)->orderBy('id','DESC');
    }

    public function getExamSectionList()
    {
        return $this->hasMany('App\Examhasclass','section_id','id')->where('is_active',True)->orderBy('id','DESC');
    }
}
