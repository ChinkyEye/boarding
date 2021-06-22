<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff_has_bank extends Model
{
    protected $fillable = [
    	'user_id','teacher_id','bank_name','bank_address','account_no','is_active','created_by','updated_by','school_id','batch_id','created_at_np'
    ];
    public function getUser()
	{
		return $this->belongsTo('App\User','created_by','id');
	}
}
