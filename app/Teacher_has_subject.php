<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher_has_subject extends Model
{
    protected $fillable = [
        'teacher_id', 'subject_id','teacher_period_id','date','date_en','created_by','updated_by','created_at_np','school_id','batch_id'
    ];

    public function getSubject()
    {
        return $this->belongsTo('App\Subject','subject_id','id');
    }

    public function getTeacherSubjectRoutine()
    {
      return $this->hasMany('App\Routine','teacher_subject_id','id');
    }
}
