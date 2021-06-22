<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Rules\PasswordField;
use Illuminate\Support\Facades\Hash;
use Validator;
Use Exception;
use Auth;
use App\User;
use App\Student;
use App\Student_has_parent;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('student.main.app');
    }

    public function loadProfile(){
        $user_id = Auth::user()->id;
        $student_shift = Student::where('user_id', $user_id)->pluck('shift_id');
        $profiles = Student::where('user_id', $user_id)
                    ->where('school_id',Auth::user()->school_id)
                    ->orderBy('id','DESC')
                    ->with('getClass','getShift','getSection','getUser','Student_has_parent','getStudentUser')
                    ->get();
        return response()->json([
            'profilelist' => $profiles
        ],200);
        // return view('student.main.home');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
            // dd($e->getMessage());
            $response = [
                            'status' => false,
                            'message' => 'Something went wrong'
                        ];
        }
        Auth::logout();
        return $response;
    }
}
