<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Teacher;
use App\Teacher_has_attendance;
use Auth;
use Response;

class TeacherController extends Controller
{
  public function index()
  {
    $page = Auth::user()->name;
    $user_check = Auth::user()->id;
    if(Auth::check()){
      $teachers = Teacher::where('user_id',$user_check)->where('is_active',true)->get();
    }else{
      $message = "You are not register";
    }
    $attended = Teacher_has_attendance::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                                      ->whereDate('created_at', date('Y-m-d'));
    if ($attended->exists()) {
      if ($attended->value('status') == True){
        $attended_result = "Present";
      }elseif ($attended->value('status') == False){
        $attended_result = "Absent";
      }
    }else{
        $attended_result = "Not Filled";
    }
    return view('teacher.teacher_detail', compact('teachers','page','attended_result'));
  }
  
  public function getAttendanceList(Request $request)
  {
    $attended = Teacher_has_attendance::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                                      ->whereDate('created_at', $request->date_data)
                                      ->value('status');
    return Response::json(array($attended));
  }
}
