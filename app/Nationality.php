<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
    protected $fillable = [
        'n_name', 'slug','created_at_np','school_id','batch_id','created_by','updated_by'
    ];

    public function setNNameAttribute($value)
    {
        $this->attributes['n_name'] = ucwords($value);
    }

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}
}
