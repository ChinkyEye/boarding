<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use App\Helper\Helper;
use Auth;
use App\Teacher;
use App\Teacher_has_period;
use App\Teacher_has_shift;
use App\Student_has_attendance;
use App\Student;
use App\Shift;
use App\SClass;
use App\Section;
use App\User;
use Response;


class StudentHasAttendanceController extends Controller
{
    public function index()
    {
      $shifts = Teacher_has_shift::where('user_id', Auth::user()->id)
                ->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                ->where('is_active', True)
                ->get();
      $check_attendence = '0';
      // dd($shifts);
      return view('teacher.attendance.studenthasattendance.index', compact('shifts','check_attendence'));
    }

    public function getStudentAttendance(Request $request){
      // dd('kk');
      // var_dump($request->date_data, date('Y-m-d')); die();
      $shift = $request->shift_data;
      $sclass = $request->class_data;
      $section = $request->section_data;
      $nepali_date = $this->helper->date_np_con_parm(date('Y-m-d'));
        $datas = Student_has_attendance::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('date',$request->date_data)->orderBy('id','asc')->where('created_by',Auth::user()->id);
        if($request->shift_data){
          $datas = $datas->whereHas('getStudent', function (Builder $query) use ($shift) {
                        $query->where('shift_id',$shift);
                    });
        }
        if($request->class_data){
          $datas = $datas->whereHas('getStudent', function (Builder $query) use ($sclass) {
                        $query->where('class_id',$sclass);
                    });
        }
        if($request->section_data){
          $datas = $datas->whereHas('getStudent', function (Builder $query) use ($section) {
                        $query->where('section_id',$section);
                    });
        }
        $check_attendence = $datas->count();
        // var_dump($check_attendence);
        if($check_attendence){
          $check_attendence = '1';
          $students = $datas->with('getStudent')->get();
          $date = $request->date_data;
          $students_count = count($students);
          // var_dump($students);
        }
        else{
          // $students = Student::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
          //                   ->orderBy('roll_no', 'Asc');

          $students = Student::whereHas('getStudentViaBatch', function(Builder $query){
                        $query->where('batch_id', Auth::user()->batch_id);
                       })
                      ->where('school_id', Auth::user()->school_id)
                      ->orderBy('roll_no', 'Asc');


          if(!empty($shift))
          {            
            $students = $students->where('shift_id', $shift);
          }

          if(!empty($sclass))
          {            
            $students = $students->where('class_id', $sclass);
          }

          if(!empty($section))
          {            
            $students = $students->where('section_id', $section);
          }
          $date = $request->date_data;
          // dd($date);
          $students = $students->get();
          $students_count = count($students);
          $check_attendence = '0';
        }
      return view('teacher.attendance.studenthasattendance.index-ajax', compact('students','date','students_count','check_attendence','nepali_date'));
    }

    
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
      $current_date = Date('Y-m-d');
      $check_date = Student_has_attendance::where('date_en',$current_date)->count();
      $student_id = $request->input('student_id');
      $status_id = $request->input('status');
      $remarks = $request->input('remark');
      if($check_date == 0){
            foreach( $student_id AS $key=>$student ){
              $user_id = Student::where('id', $student)->value('user_id');
              $studenthasattendance= Student_has_attendance::create([
                  'student_id' => $student,
                  'user_id' => $user_id,
                  'date' => $this->helper->date_np_con_parm($current_date),
                  'date_en' => $current_date,
                  'status' => ($status_id[$key] != '0' ? '1' : '0'),
                  'remark' => $remarks[$key],
                  'school_id' => Auth::user()->school_id,
'batch_id' => Auth::user()->batch_id,
                  'created_by' => Auth::user()->id,
                  'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
              ]);
            }
        }else{
          foreach( $student_id AS $key=>$student ){
                $get_tchr_atnd_id = Student_has_attendance::where('date_en',$current_date)->where('student_id',$student)->value('id'); 
                $std_tchr_update= Student_has_attendance::find($get_tchr_atnd_id);
                $std_tchr_update->status = (!empty($status_id[$key]) ? '1' : '0');
                $std_tchr_update->remark = $remarks[$key];
                $std_tchr_update->updated_by = Auth::user()->id;
                $std_tchr_update->update();
            }
        }
        $pass = array(
            'message' => 'Data changed successfully!',
            'alert-type' => 'success'
        );
        return back()->with($pass)->withInput();
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
      //
    }

    public function update(Request $request, Student_has_attendance $studenthasattendance)
    {
      //
    }

    public function destroy(Student_has_attendance $studenthasattendance)
    {
        //
    }
}
