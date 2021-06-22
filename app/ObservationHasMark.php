<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ObservationHasMark extends Model
{
    protected $fillable = [
         'student_id', 'observation_id','invoicemark_id','classexam_id','created_by','updated_by','sort_id','created_at_np','school_id','batch_id'
    ];

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}

	public function getObservation()
	{
		return $this->belongsTo('App\Observation','observation_id','id');
	}
}
