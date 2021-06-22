<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
		'title', 'slug','start_date','end_date','created_at_np','school_id','batch_id','created_by','updated_by','eng_start_date','eng_end_date','color','start_time','end_time'
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
