<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
	protected $fillable = [
         'topic', 'slug','created_by','updated_by','sort_id','fee','class_id','created_at_np','school_id','batch_id'
    ];

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

	public function getClass()
	{
		return $this->belongsTo('App\SClass','class_id','id');
	}

	public function setTopicAttribute($value)
	{
	    $this->attributes['topic'] = ucwords($value);
	}

}
