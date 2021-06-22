<?php

namespace App\Http\Controllers\Backend;

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
use App\Exports\StudenthasattendancesExport;
use Maatwebsite\Excel\Facades\Excel;


class StudentHasAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function attend($slug)
    {
        $teacher_id = Teacher::where('slug',$slug)->value('id');
        $teacher_shift_id = Teacher_has_period::where('teacher_id',$teacher_id)->value('shift_id');
        $teacher_class_id = Teacher_has_period::where('teacher_id',$teacher_id)->value('class_id');
        $teacher_section_id = Teacher_has_period::where('teacher_id',$teacher_id)->value('section_id');
        $student_data = Student::where('shift_id',$teacher_shift_id)->where('class_id',$teacher_class_id)->where('section_id',$teacher_section_id)->orderBy('roll_no','Asc')->get();
        $shifts = Shift::get();
        // dd($teacher_shift_id,$teacher_class_id,$teacher_section_id,$student_data); 
        return view('backend.attendance.studenthasattendance.attend', compact('student_data','shifts','teacher_id'));
    }

    public function index()
    {
        $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->get();
        return view('backend.attendance.studenthasattendance.index', compact('shifts'));
    }

    public function export(Request $request){
      // dd($request);
      $excShift = $request->excShift;
      $excClass = $request->excClass;
      $excSection = $request->excSection;
      $excDate = $request->excDate;
      return Excel::download(new StudenthasattendancesExport($excShift,$excClass,$excSection,$excDate), $excDate.'StudentAttendance.xlsx');
    }

    public function getStudentAttendenceList(Request $request)
    {
      $all_data = json_decode($request->parameters, true);
      
      // dd($all_data['shift_id'],$all_data['class_id'],$all_data['section_id'],$all_data['date']);
      $datas = Student_has_attendance::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->orderBy('id','asc');
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
        $dates = $all_data['date'];
        $datas = $datas->where('date', $all_data['date']);
        $nepali_date = $this->helper->date_np_con_parm(date('Y-m-d'));
        // dd($nepali_date);
        // dd($dates , $datas->get());
      }else{
        $dates = $this->helper->date_np_con_parm(date('Y-m-d'));
        $datas = $datas->where('date', $dates);
      }
      $datas = $datas->get();
      $absent_count = $datas->where('status',False)->count();
      // dd($dates,$datas);
      // get value name
      $shifts = Shift::where('id',$all_data['shift_id'])->value('name');
      $classes = SClass::where('id',$all_data['class_id'])->value('name');
      $sections = Section::where('id',$all_data['section_id'])->value('name');
      return view('backend.attendance.studenthasattendance.student_ajax', compact('datas','dates','shifts','classes','sections','absent_count','nepali_date'));
    }

    public function create()
    {
        $students = Student::get();
        return view('backend.attendance.studenthasattendance.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
      $date = $request->date; // eng date
      $check_date = Student_has_attendance::where('date_en',$date[0])->count();
      $student_id = $request->student_id;
      $status_id = $request->status;
      $remarks = $request->remarks;
      if($check_date == 0){
        $pass = array(
          'message' => 'You cannot modify this attendance of '.$date[0],
          'alert-type' => 'error'
        );
      }else{
        foreach( $student_id as $key=>$student ){
          $get_tchr_atnd_id = Student_has_attendance::where('date_en',$date[$key])->where('student_id',$student)->value('id'); 
          $std_tchr_update= Student_has_attendance::find($get_tchr_atnd_id);
          if ($status_id[$key] == '1') { $status = '1'; }
          else{ $status = '0'; }
          $std_tchr_update->status = $status;
          $std_tchr_update->remark = $remarks[$key];
          $std_tchr_update->updated_by = Auth::user()->id;
          $std_tchr_update->update();
        }
        $pass = array(
          'message' => 'Data changed successfully!',
          'alert-type' => 'success'
        );
      }
      return back()->with($pass)->withInput();
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
        $studenthasattendances = Student_has_attendance::where('id', $id)->get();
        $students = Student::get();
        return view('backend.attendance.studenthasattendance.edit', compact('studenthasattendances','students'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student_has_attendance $studenthasattendance)
    {
        $this->validate($request, [
          'date' => 'required',
        ]);
        $main_data = $request->all();
        $main_data['updated_by'] = Auth::user()->id;
        if($studenthasattendance->update($main_data)){
          $notification = array(
            'message' => 'Data updated successfully!',
            'alert-type' => 'success'
          );
        }else{
          $notification = array(
            'message' => 'Data could not be updated!',
            'alert-type' => 'error'
          );
        }
        return redirect()->route('admin.teacher-student-attendance.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student_has_attendance $studenthasattendance)
    {
        dd($studenthasattendance);
        if($studenthasattendance->delete()){
          $notification = array(
            'message' => 'Data deleted successfully!',
            'alert-type' => 'success'
          );
        }else{
          $notification = array(
            'message' => 'Data could not be deleted!',
            'alert-type' => 'error'
          );
        }
        return back()->with($notification);
    }
}
