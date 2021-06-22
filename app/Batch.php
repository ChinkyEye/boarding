<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\User;

class Batch extends Model
{
	use Notifiable;

    protected $fillable = [
        'name', 'slug','created_at_np','school_id','created_by','updated_by'
    ];

    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}
}
