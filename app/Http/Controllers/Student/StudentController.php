<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use App\Helper\Helper;
use App\User;
use App\Student;
use Auth;

class StudentController extends Controller
{
    public function __construct(Request $request, Helper $helper)
    {
      $this->request = $request;
      $this->helper = $helper;
    }

    public function index()
    {
      $user_check = Auth::user()->id;
      if(Auth::check()){
        $students = Student::where('user_id',$user_check)->where('is_active',true)->get();
      }else{
        $message = "You are not register";
      }
      return view('student.student_detail', compact('students'));
    }

    
    public function store(Request $request)
    {

    }

    public function destroy(Student $student)
    {

    }

    public function edit($id)
    {

    }

    public function update(Request $request, Student $student)
    {

    }

    public function show($id)
    {

      // ------------------------- 
      // $user_email =User::where('id', $id)->pluck('email');
      // $user_password =User::where('id', $id)->pluck('password');
      // dd($user_password);
      // $password =Crypt::decrypt($user_password);
      // $students = Student::where('email', $user_email)
                          // ->where('student_code', $user_password)
                          // ->get();
      // dd($students);
      return view('student.student_detail', compact('students'));
    }
}
