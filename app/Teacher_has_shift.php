<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher_has_shift extends Model
{
    protected $fillable = [
        'teacher_id', 'shift_id','created_by','updated_by','created_at_np','school_id','batch_id','user_id'
    ];

    public function getTeacherShift()
	{
		return $this->belongsTo('App\Shift','shift_id','id');
	}
	public function user()
    {
        return $this->belongsTo('App\User','teacher_id','id');
    }

    public function teacher()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function getTeacherInfo()
    {
        return $this->hasOne('App\teacher','user_id','user_id');
    }
}
