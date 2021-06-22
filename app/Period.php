<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $fillable = [
    'name', 'slug','created_by','updated_by','sort_id','start_time','end_time','created_at_np','school_id','batch_id',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }
    
    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}
}
