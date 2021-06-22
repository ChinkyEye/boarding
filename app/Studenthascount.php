<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Studenthascount extends Model
{
    protected $fillable = [
        'user_id','type_id','print_count','created_by','updated_by','created_at_np','school_id','batch_id','category'
    ];
}
