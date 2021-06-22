<?php

namespace App\Http\Controllers\Main\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use App\Helper\Helper;
use Auth;
use App\Teacher;
use App\Teacher_has_period;
use App\Student_has_attendance;
use App\Student;
use App\Shift;
use App\SClass;
use App\Section;
use Response;


class StudentHasAttendanceController extends Controller
{
    public function attend($slug)
    {
      $settings = $this->schoolCheck($request)['setting'];
      $school_info = $this->schoolCheck($request)['school_info'];
        $teacher_id = Teacher::where('slug',$slug)->value('id');
        $teacher_shift_id = Teacher_has_period::where('teacher_id',$teacher_id)->value('shift_id');
        $teacher_class_id = Teacher_has_period::where('teacher_id',$teacher_id)->value('class_id');
        $teacher_section_id = Teacher_has_period::where('teacher_id',$teacher_id)->value('section_id');
        $student_data = Student::where('shift_id',$teacher_shift_id)->where('class_id',$teacher_class_id)->where('section_id',$teacher_section_id)->orderBy('roll_no','Asc')->get();
        $shifts = Shift::get();
        // dd($teacher_shift_id,$teacher_class_id,$teacher_section_id,$student_data); 
        return view('main.school.info.student.studenthasattendance.attend', compact('settings','school_info','student_data','shifts','teacher_id'));
    }

    public function index(Request $request)
    {
      // dd($request);
      $settings = $this->schoolCheck($request)['setting'];
      $school_info = $this->schoolCheck($request)['school_info'];
      $shifts = Shift::where('school_id', $school_info->id)->where('is_active','1')->get();
      return view('main.school.info.student.studenthasattendance.index', compact('settings','school_info','shifts'));
    }

    public function getStudentList(Request $request){
      $settings = $this->schoolCheck($request)['setting'];
      $school_info = $this->schoolCheck($request)['school_info'];

      $all_data = json_decode($request->parameters, true);
      
      // dd($all_data['shift_id'],$all_data['class_id'],$all_data['section_id'],$all_data['date']);
      $datas = Student_has_attendance::where('school_id', $school_info->id)->orderBy('id','asc');
      if($all_data['shift_id']){
        $datas = $datas->whereHas('getStudent', function (Builder $query) use ($all_data) {
                              $query->where('shift_id',$all_data['shift_id']);
                          });
      }
      if($all_data['class_id']){
        $datas = $datas->whereHas('getStudent', function (Builder $query) use ($all_data) {
                              $query->where('class_id',$all_data['class_id']);
                          });
      }
      if($all_data['section_id']){
        $datas = $datas->whereHas('getStudent', function (Builder $query) use ($all_data) {
                              $query->where('section_id',$all_data['section_id']);
                          });
      }
      if($all_data['date']){
        // $dates = date('Y-m-d', strtotime($all_data['date']));
        // dd($dates);
        $dates = $all_data['date'];
        $datas = $datas->where('date', $dates);
        // dd($dates , $datas->get());
      }else{
        // $dates = Date('Y-m-d');
        $dates = $this->helper->date_np_con_parm(date('Y-m-d'));;
        $datas = $datas->where('date', $dates);
        // $datas = Student::where('is_active', true);
      }
      $datas = $datas->get();
      $datas_present = ($datas->where('status',true)->count()) + ($datas->where('restatus',true)->count()); //status + restatus
      // dd($dates,$datas);
      // get value name
      $shifts = Shift::where('id',$all_data['shift_id'])->value('name');
      $classes = SClass::where('id',$all_data['class_id'])->value('name');
      $sections = Section::where('id',$all_data['section_id'])->value('name');
      return view('main.school.info.student.studenthasattendance.student_ajax', compact('datas','dates','datas_present','shifts','classes','sections'));
    }
}
