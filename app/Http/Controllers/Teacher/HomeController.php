<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Rules\PasswordField;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;
use Response;
use App\Teacher;
use App\Teacher_has_attendance;
use App\Teacher_has_period;
use App\Teacher_has_subject;
use App\Subject;

class HomeController extends Controller
{
    public function index()
    {
        $user_check = Auth::user()->id;
        if(Auth::check()){
            $teachers = Teacher::where('user_id',$user_check)->where('is_active',true)->get();
        }else{
            $message = "You are not register";
        }
        $shifts = Teacher_has_period::where('teacher_id', Auth::user()->id)
                                    ->where('school_id', Auth::user()->school_id)
                                    ->where('batch_id', Auth::user()->batch_id)
                                    ->where('is_active', True)
                                    ->groupBy('shift_id')
                                    ->get();
        $attended = Teacher_has_attendance::where('user_id', Auth::user()->id)
                                        ->where('school_id', Auth::user()->school_id)
                                        ->where('batch_id', Auth::user()->batch_id)
                                        ->whereDate('created_at', date('Y-m-d'))
                                        ->where('shift_id', $shifts->first()->shift_id)
                                        // ->where('shift_id', $shifts->first()->id)
                                        ->value('status');
        return view('teacher.teacher_detail', compact('teachers','attended','shifts'));
    }

    public function showChangePasswordForm(){
        return view('teacher.main.changepassword');
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

    public function getAttendanceList(Request $request)
    {
        // dd('hello');
        // dd($request->date_data);
        $attended = Teacher_has_attendance::where('user_id', Auth::user()->id)
                                        ->where('school_id', Auth::user()->school_id)
                                        ->where('batch_id', Auth::user()->batch_id)
                                        ->whereDate('created_at', $request->date_data)
                                        ->where('shift_id', $request->shift_data)
                                        ->value('status');
        return Response::json($attended);
    }

    // search append
    public function getClassList(Request $request){
      $data_id = $request->data_id;
      $class_list_data = Teacher_has_period::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('teacher_id',Auth::user()->id)
                        ->where('shift_id',$data_id)
                        ->with('sclass')
                        ->get();
      return Response::json($class_list_data);
    }

    public function getSectionList(Request $request){
      $data_id = $request->data_id;
      $shift_id = $request->shift_id;
      $section_list_data = Teacher_has_period::where('school_id', Auth::user()->school_id)
                            ->where('batch_id', Auth::user()->batch_id)
                            ->where('teacher_id',Auth::user()->id)
                            ->where('shift_id',$shift_id)
                            ->where('class_id',$data_id)
                            ->with('section')
                            ->get();
      return Response::json($section_list_data);
    }

    public function getSubjectList(Request $request)
    {
        $class_id = $request->input('data_id');
        $subject_list_data = Teacher_has_period::where('school_id', Auth::user()->school_id)
                            ->where('batch_id', Auth::user()->batch_id)
                            ->where('teacher_id',Auth::user()->id)
                            ->where('class_id',$class_id)
                            ->with('getTeacherSubject.getSubject')
                            ->get();
      return Response::json(array(
        'subject' => $subject_list_data,
      ));
    }
}
