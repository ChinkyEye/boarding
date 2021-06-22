<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    protected $fillable = [
         'title', 'remark','value','created_by','updated_by','sort_id','created_at_np','school_id','batch_id'
    ];

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

	public function getStudentObservation()
	{
		return $this->hasOne('App\ObservationHasMark');
	}
}
