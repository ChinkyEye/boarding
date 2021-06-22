<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Batch;
use Auth;

class SchoolInfo extends Model
{
	/**
	 * [totalSchool description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 * count total school
	 */
    public static function totalSchool(Request $request){
        return Auth::user()->where('user_type','1')->where('is_active', True)->count();
    }

    // public static function getBatch(Request $request){
    // 	return Batch::all();
    // }
}
