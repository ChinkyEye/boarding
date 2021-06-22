<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectPath()
    {
        // if (\Auth::user()->user_type == '1' && \Auth::user()->email == 'kerabari@gmail.com') {
        //     return '/home';
        // }
        if (\Auth::user()->user_type == '1') {
            return '/home';
        }
        elseif (\Auth::user()->user_type == '2') {
            return '/student';
        }
        elseif (\Auth::user()->user_type == '3') {
            return '/teacher';
        }
        elseif (\Auth::user()->user_type == '4') {
            return '/main';
        }
        elseif (\Auth::user()->user_type == '5') {
            return '/library';
        }
        elseif (\Auth::user()->user_type == '6') {
            return '/account';
        }
        else{
            return '/';
        }
        return '/';
    }
}
