<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name', 'slug','created_by','updated_by','sort_id','subject_code','credit_hour','theory_practical','compulsory_optional','class_id','created_at_np','school_id','batch_id',
    ];

    public function sclass()
    {
    	return $this->belongsTo('App\SClass','class_id','id');
    }

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    public function setTheoryPracticalAttribute($value)
    {
        $this->attributes['theory_practical'] = ucwords($value);
    }

    public function setCompulsoryOptionalAttribute($value)
    {
        $this->attributes['compulsory_optional'] = ucwords($value);
    }

    public function getMarkSubjectList()
    {
        return $this->hasMany('App\MarkClass','subject_id','id');
    }

    public function getMarkSubject()
    {
        return $this->hasOne('App\MarkClass','subject_id','id');
    }

    public function getClassSubject()
    {
        return $this->hasOne('App\StudentHasMark');
    }
    public function getClassSubjectM()
    {
        return $this->hasMany('App\StudentHasMark');
    }

    public function getClassSubjectPP()
    {
        return $this->hasMany('App\SubjectHasTheoryPractical','subject_id','id');
    }

    public function getClassSubjectPPOne()
    {
        return $this->hasOne('App\SubjectHasTheoryPractical','subject_id','id');
    }

}
