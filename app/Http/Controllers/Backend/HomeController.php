<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Rules\PasswordField;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\User;
use App\Shift;
use App\SClass;
use App\Section;
use App\Student;
use App\Student_has_attendance;
use App\Teacher;
use App\Teacher_has_attendance;
use App\Book;
use App\Batch;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $common_batch =$this->batchCheck($request)['batch'];
        $count_shift = Shift::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->count(); 
        $count_sclass = SClass::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->count(); 
        $count_section = Section::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->count(); 
        $count_student = User::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('user_type','2')
                        ->where('is_active', True)
                        ->count(); 
        $count_student_present = Student_has_attendance::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('date_en', Date('Y-m-d'))
                        ->where('status', True)
                        ->count(); 
        $count_teacher = User::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('user_type','3')
                        ->where('is_active', True)
                        ->count(); 
        $count_teacher_present = Teacher_has_attendance::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('date_en', Date('Y-m-d'))
                        ->where('status', True)
                        ->count(); 
        $count_book = Book::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->count(); 
        $count_list = collect([
                            ['shift' => $count_shift], 
                            ['sclass' => $count_sclass], 
                            ['section' => $count_section], 
                            ['student' => $count_student], 
                            ['student_present' => $count_student_present], 
                            ['teacher' => $count_teacher], 
                            ['teacher_present' => $count_teacher_present], 
                            ['book' => $count_book],
                        ])->collapse()->all();
        return view('backend.main.home', compact(['count_list'],'common_batch'));
    }

    public function showChangePasswordForm(){
        return view('backend.main.changepassword');
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
        return back()->withInput($response);
    }

}
