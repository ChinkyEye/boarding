<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remainder extends Model
{
	protected $fillable = [
        'class_id', 'shift_id','section_id','title','description','is_active','created_by','updated_by','created_at_np','school_id','batch_id'
    ];

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

	public function getShift()
	{
		return $this->belongsTo('App\Shift','shift_id','id');
	}
	public function getClass()
	{
		return $this->belongsTo('App\SClass','class_id','id');
	}
	public function getSection()
	{
		return $this->belongsTo('App\Section','section_id','id');
	}
}
