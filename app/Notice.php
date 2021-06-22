<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
	protected $fillable = [
		'title', 'slug','description','created_at_np','school_id','batch_id','created_by','updated_by','start_date','end_date','start_date_np','end_date_np'
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

	public function getNoticeList()
    {
    	return $this->hasMany('App\Notice_for','notice_id','id');
    }

    public function getNotice()
    {
    	return $this->belongsTo('App\Notice_for','notice_id','id')->where('is_active',True);
    }
}
