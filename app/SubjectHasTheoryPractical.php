<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectHasTheoryPractical extends Model
{
    protected $fillable = [
        'subject_id','created_by','updated_by','sort_id','theory_practical','created_at_np','school_id','batch_id',
    ];

    public function getUser()
    {
    	return $this->belongsTo('App\User','created_by','id');
    }

    public function getClassSubjectList()
    {
        return $this->hasOne('App\Subject','id','subject_id');
    }
}
