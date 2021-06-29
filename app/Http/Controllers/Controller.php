<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use App\Helper\Helper;

use App\Setting;
use App\Batch;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(Request $request, Helper $helper, Guard $auth)
    {
      $this->request = $request;
      $this->helper = $helper;
    }

    public function schoolCheck($request){
    	$settings = Setting::where('created_by', Auth::user()->id)
    	            ->where('user_type', 1)
    	            ->orderBy('id','ASC')
    	            ->get();
    	$school_id = Setting::where('slug', $request->slug)->value('id');
    	$school_info = Setting::find($school_id);
    	return [
            'setting' => $settings , 
            'school_id' => $school_id , 
            'school_info' => $school_info
        ];
    }

    public function batchCheck($request){
        // return Batch::all();
        $batchs = Batch::all();
        return ['batch' => $batchs];
    }
   
}
