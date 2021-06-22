<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Rules\PasswordField;
use Illuminate\Support\Facades\Hash;
use App\Setting;
use App\User;
use Auth;
use Route;

class HomeController extends Controller
{
    public function index()
    {
        // dd("bitch");
    	$settings = Setting::where('created_by', Auth::user()->id)
    						->where('user_type',1)
					    	->orderBy('id','ASC')
					    	->get();
    	return view('main.main.home', compact('settings'));
    }

    public function showChangePasswordForm(){
    	$settings = Setting::where('created_by', Auth::user()->id)
    						->where('user_type',1)
					    	->orderBy('id','ASC')
					    	->get();
    	return view('main.main.changepassword', compact('settings'));
        // return view('main.main.changepassword');
    }

    public function changePassword(PasswordField $request){
        try{
            User::find(Auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
            $response = [
                            'status' => true,
                            'message' => Auth::user()->name.' password is changed !'
                        ];
        }
        catch(Exception $e)
        {
            $response = [
                            'status' => false,
                            'message' => 'Something went wrong'
                        ];
        }
        Auth::logout();
        return back()->with($response);
    }
}
