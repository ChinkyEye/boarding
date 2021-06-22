<?php

namespace App\Http\Controllers\Backend\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Shift;
use App\SClass;
use App\Student;
use App\Student_has_attendance;
use Auth;
use App\Exports\Report\Student\Profile\StudentProfileExport;
use App\Exports\Report\Student\Attendance\AttendanceReportExport;
use App\Exports\Report\Student\Attendance\AttendanceDetailReportExport;
use App\Exports\Report\Student\AllReport\AllStudentReport;
use Maatwebsite\Excel\Facades\Excel;

class StudentReportController extends Controller
{
    public function profile(Request $request)
    {
        $page = 'profile';
        $shifts = Shift::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->get();
        $students_list = Student::where('school_id', Auth::user()->school_id)
                                ->where('batch_id', Auth::user()->batch_id)
                                ->with('getStudentUser')
                                ->with('Student_has_parent')
                                ->withCount('Student_has_parent')
                                ->with('getUser');
        $students = $students_list->paginate(50);
        // dd($students);

        return view('backend.report.student.profile.index',compact(['students','page','shifts']));
    }

    public function profileSearch(Request $request)
    {
        $page = 'profile';
        $shifts = Shift::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->get();
        $students_list = Student::where('school_id', Auth::user()->school_id)
                                ->where('batch_id', Auth::user()->batch_id);
                                // dd($request);
        if ($request->search_data) {
            $students_list = $students_list->where('student_code', $request->search_data);
        }
        if ($request->shift_data) {
            $students_list = $students_list->where('shift_id', $request->shift_data);
        }
        if ($request->class_data) {
            $students_list = $students_list->where('class_id', $request->class_data);
        }
        if ($request->section_data) {
            $students_list = $students_list->where('section_id', $request->section_data);
        }
        // dd($students_list->get());
        $students = $students_list->with('getStudentUser')
                                ->with('Student_has_parent')
                                ->withCount('Student_has_parent')
                                ->with('getUser')
                                ->paginate(50);
                                // dd($students);
        return view('backend.report.student.profile.search',compact(['students','page','shifts','request']));
    }

    public function exportProfile(Request $request)
    {
        $date = $this->helper->date_np_con_parm(date('Y-m-d'));
        $excShiftData = $request->shift_data;
        $excClassData = $request->class_data;
        $excSectionData = $request->section_data;
        // dd($request);
        // dd($excShiftData,$excClassData,$excSectionData);
        return Excel::download(new StudentProfileExport($excShiftData,$excClassData,$excSectionData), $date.'StudentProfile.xlsx');
    }

    public function attendance(Request $request)
    {
        $page = 'attendance';
        $shifts = Shift::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->get();
                        
        $date = $this->helper->date_np_con_parm(date('Y-m-d'));  
                   
        $current_year = $this->helper->date_np_con_parm_year(date('Y-m-d'));
        $current_month = $this->helper->date_np_con_parm_month(date('Y-m-d'));
        $current_days_in_month = $this->helper->current_days_in_month(substr($current_year,2),$current_month);
        $current_days = $current_days_in_month;

        $attendances_list = Student::where('school_id', Auth::user()->school_id)
                                 ->where('batch_id', Auth::user()->batch_id)
                                ->with(['getStudentAttendance' => function ($query) use ($date) {
                                        $query->where('school_id', Auth::user()->school_id)
                                              ->where('batch_id', Auth::user()->batch_id)
                                              ->where('date', $date);
                                    }]);   
        
        $attendances = $attendances_list->paginate(50);   
        // dd($attendances);          
        return view('backend.report.student.attendance.index',compact(['attendances','page','shifts','current_year','current_month','current_days']));
    }

    public function attendanceSearch(Request $request)
    {
        $page = 'attendance';
        $shifts = Shift::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->get();
        $date = $request->filter_date;  
        

        $attendances = Student::where('school_id', Auth::user()->school_id)
                                 ->where('batch_id', Auth::user()->batch_id);
        if ($request->shift_data) {
            $shift_data = $request->shift_data;
            $attendances = $attendances->where('school_id',Auth::user()->school_id)
                                       ->where('shift_id', $shift_data);
        }
        if ($request->class_data) {
            $attendances = $attendances->where('class_id', $request->class_data);
        }
        if ($request->section_data) {
            $attendances = $attendances->where('section_id', $request->section_data);
        }

        if($request->filter_date){
            $date = $request->filter_date;
            $attendances = $attendances->with(['getStudentAttendance' => function ($query) use ($date) {
                                        $query->where('school_id', Auth::user()->school_id)
                                              ->where('batch_id', Auth::user()->batch_id)
                                              ->where('date',$date);
                                    }]);
        }
        else{
            $date = $this->helper->date_np_con_parm(date('Y-m-d')); 
            $attendances = $attendances->with(['getStudentAttendance' => function ($query) use ($date) {
                                        $query->where('school_id', Auth::user()->school_id)
                                              ->where('batch_id', Auth::user()->batch_id)
                                              ->where('date', $date);
                                    }]);
        }        

        // $attendances = $attendances->paginate(50);
        $attendances = $attendances->with('getStudentUser')
                                ->with('getUser')
                                ->paginate(50);
                                // dd($attendances);
        return view('backend.report.student.attendance.search',compact(['attendances','page','request','shifts']));
    }

    public function attendanceDetail(Request $request,$id)
    {
        $page = 'attendance';

        $date = $this->helper->date_np_con_parm(date('Y-m-d'));  
        $current_year = $this->helper->date_np_con_parm_year(date('Y-m-d'));
        $current_month = $this->helper->date_np_con_parm_month(date('Y-m-d'));
        $current_days_in_month = $this->helper->current_days_in_month(substr($current_year,2),$current_month);
        $current_days = $current_days_in_month;

        $attendances = Student_has_attendance::where('school_id', Auth::user()->school_id)
                                             ->where('batch_id', Auth::user()->batch_id)
                                             ->where('user_id', $id);

        if ($request->shift_data) {
            $attendances = $attendances->where('shift_id', $request->shift_data);
        }
        $attendances = $attendances->get();
        $students_id = Student::where('user_id',$id)->value('id');
        $student_info = Student::find($students_id);
        // dd($student_info);
        return view('backend.report.student.attendance.detail',compact(['attendances','page','request','student_info','current_year','current_month','current_days']));
    }

    public function attendanceMonthDetail(Request $request)
    {
        $page = 'attendance';
        $user_id = $request->user_id;

        $date = $this->helper->date_np_con_parm(date('Y-m-d'));  
        $current_year = $this->helper->date_np_con_parm_year(date('Y-m-d'));
        $current_month = $request->filter_month;
        $current_days_in_month = $this->helper->get_date_nepali_parm_month_days(substr($current_year,2),$current_month);
        $current_days = $current_days_in_month;
       
        $attendances = Student_has_attendance::where('school_id', Auth::user()->school_id)
                                  ->where('batch_id', Auth::user()->batch_id)
                                  ->where('user_id', $user_id);

        if ($request->shift_data) {
            $attendances = $attendances->where('shift_id', $request->shift_data);
        }
        $attendances = $attendances->get();
        $students_id = Student::where('user_id',$user_id)->value('id');
        $student_info = Student::find($students_id);
        return view('backend.report.student.attendance.detailmonthsearch',compact(['attendances','page','request','student_info','current_year','current_month','current_days','user_id']));
    }

    public function exportStudentAttendance(Request $request)
    {
        $date = $this->helper->date_np_con_parm_month(date('Y-m-d'));
        // dd($request);
        $exportShift = $request->shift_data;
        $exportClass = $request->class_data;
        $exportSection = $request->section_data;
        $excDate = $request->excDate;
        // dd($excDate);
        return Excel::download(new AttendanceReportExport($exportShift,$exportClass,$exportSection,$excDate), $date.'AttendanceReport.xlsx');
    }

    public function exportStudentDetailMonthAttendance(Request $request)
    {
        $date = $this->helper->date_np_con_parm_month(date('Y-m-d'));
        // dd($request);
        $excUser = $request->user_id;
        $excCurrentYear = $request->current_year;
        $excCurrentMonth = $request->current_month;
        $excCurrentDays = $request->current_days;
        return Excel::download(new AttendanceDetailReportExport($excUser,$excCurrentYear,$excCurrentMonth,$excCurrentDays), $date.'AttendanceReport.xlsx');
    }

    public function exportAllStudentReport(Request $request)
    {
        $month = $this->helper->date_np_con_parm_month(date('Y-m-d'));
        // dd($date);
        $date = $this->helper->date_np_con_parm(date('Y-m-d'));
        $excMonth = $month;
        $excDate = $date;
        return Excel::download(new AllStudentReport($excMonth,$excDate), $date.'AttendanceReport.xlsx');
    }


    public function index()
    {
        //
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
}
