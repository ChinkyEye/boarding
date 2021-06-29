<?php

namespace App\Http\Controllers\Backend\Report;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Teacher_has_attendance;
use App\TeacherIncome;
use App\Teacher_has_period;
use App\Teacher;
use App\User;
use App\Shift;
use App\SClass;
use Auth;
use App\Exports\Report\Teacher\Profile\PDFExport;
use App\Exports\Report\Teacher\Profile\TeacherProfileExport;
use App\Exports\Report\Teacher\ClassSubject\ClassSubjectsExport;
use App\Exports\Report\Teacher\Salary\SalaryExport;
use App\Exports\Report\Teacher\Attendance\AttendanceReportExport;
use App\Exports\Report\Teacher\Attendance\AttendanceDetailReport;
use App\Exports\Report\Teacher\AllReport\AllTeacherExport;
use Maatwebsite\Excel\Facades\Excel;

class TeacherReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        $page = 'profile';
        $shifts = Shift::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->get();
        $teachers_list = Teacher::where('school_id', Auth::user()->school_id)
                                ->whereHas('getTeacherFromBatch', function(Builder $query){
                                    $query->where('batch_id', Auth::user()->batch_id);
                                })
                                // ->where('batch_id', Auth::user()->batch_id)
                                ->with('getTeacherUser')
                                ->with('getUser');
        $teachers = $teachers_list->paginate(50);

        return view('backend.report.teacher.profile.index',compact(['teachers','page','shifts']));
    }

    public function profileSearch(Request $request)
    {
        $page = 'profile';
        $shifts = Shift::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->get();
        $teachers_list = Teacher::where('school_id', Auth::user()->school_id)
                                ->whereHas('getTeacherFromBatch', function(Builder $query){
                                    $query->where('batch_id', Auth::user()->batch_id);
                                });
                                // ->where('batch_id', Auth::user()->batch_id);
        if ($request->search_data) {
            $teachers_list = $teachers_list->where('teacher_code', $request->search_data);
        }
        if ($request->shift_data) {
            $teachers_list = $teachers_list->whereHas('getShiftTeacherCountList', function (Builder $query) use ($request){
                                                    $query->where('shift_id', $request->shift_data)
                                                          ->where('school_id', Auth::user()->school_id)
                                                          ->where('batch_id', Auth::user()->batch_id);
                                                });
        }
        $teachers = $teachers_list->with('getTeacherUser')
                                ->with('getUser')
                                ->paginate(50);

        return view('backend.report.teacher.profile.search',compact(['teachers','page','shifts','request']));
    }

    public function exportProfile(Request $request)
    {
        $date = $this->helper->date_np_con_parm(date('Y-m-d'));
        return Excel::download(new PDFExport($request), $date.'TeacherProfile.pdf');
    }

    public function exportProfileExcel(Request $request)
    {
        $date = $this->helper->date_np_con_parm(date('Y-m-d'));
        return Excel::download(new PDFExport($request), $date.'TeacherProfile.xlsx');
    }

    public function exportTeacherProfile(Request $request)
    {
        $excTeacherCode = $request->excTeacherCode;
        $excShiftData = $request->excShiftData;
        $date = $this->helper->date_np_con_parm(date('Y-m-d'));
        return Excel::download(new TeacherProfileExport($excTeacherCode,$excShiftData), $date.'TeacherProfiles.xlsx');
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

        $attendances_list = Teacher::where('school_id', Auth::user()->school_id)
                                 ->where('batch_id', Auth::user()->batch_id)
                                ->with(['getTeacherAttendance' => function ($query) use ($date) {
                                        $query->where('school_id', Auth::user()->school_id)
                                              ->where('batch_id', Auth::user()->batch_id)
                                              ->where('date', $date);
                                    }]);   
        
        $attendances = $attendances_list->paginate(50);             
        return view('backend.report.teacher.attendance.index',compact(['attendances','page','shifts','current_year','current_month','current_days']));
    }

    public function attendanceSearch(Request $request)
    {
        $page = 'attendance';
        $shifts = Shift::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->get();
        $date = $request->filter_date;  
        

        $attendances = Teacher::where('school_id', Auth::user()->school_id)
                                 ->where('batch_id', Auth::user()->batch_id);
        if ($request->shift_data) {
            $shift_data = $request->shift_data;
            $attendances = $attendances->where('school_id',Auth::user()->school_id)->whereHas('getShiftTeacherCountList', function (Builder $query) use ($shift_data) {
                $query->where('school_id', Auth::user()->school_id)
                ->where('batch_id', Auth::user()->batch_id)
                ->where('shift_id', $shift_data);
            })
            ;
        }

        if($request->filter_date){
            $date = $request->filter_date;
            $attendances = $attendances->with(['getTeacherAttendance' => function ($query) use ($date) {
                                        $query->where('school_id', Auth::user()->school_id)
                                              ->where('batch_id', Auth::user()->batch_id)
                                              ->where('date',$date);
                                    }]);
        }
        else{
            $date = $this->helper->date_np_con_parm(date('Y-m-d')); 
            $attendances = $attendances->with(['getTeacherAttendance' => function ($query) use ($date) {
                                        $query->where('school_id', Auth::user()->school_id)
                                              ->where('batch_id', Auth::user()->batch_id)
                                              ->where('date', $date);
                                    }]);
        }        

        $attendances = $attendances->paginate(50);
        return view('backend.report.teacher.attendance.search',compact(['attendances','page','request','shifts']));
    }

    public function attendanceDetail(Request $request,$id)
    {
        $page = 'attendance';

        $date = $this->helper->date_np_con_parm(date('Y-m-d'));  
        $current_year = $this->helper->date_np_con_parm_year(date('Y-m-d'));
        $current_month = $this->helper->date_np_con_parm_month(date('Y-m-d'));
        $current_days_in_month = $this->helper->current_days_in_month(substr($current_year,2),$current_month);
        $current_days = $current_days_in_month;

        $attendances = Teacher_has_attendance::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('user_id', $id);

        if ($request->shift_data) {
            $attendances = $attendances->where('shift_id', $request->shift_data);
        }
        $attendances = $attendances->get();
        $teachers_id = Teacher::where('user_id',$id)->value('id');
        $teacher_info = Teacher::find($teachers_id);
        return view('backend.report.teacher.attendance.detail',compact(['attendances','page','request','teacher_info','current_year','current_month','current_days']));
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
       
        $attendances = Teacher_has_attendance::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('user_id', $user_id);

        if ($request->shift_data) {
            $attendances = $attendances->where('shift_id', $request->shift_data);
        }
        $attendances = $attendances->get();
        $teachers_id = Teacher::where('user_id',$user_id)->value('id');
        $teacher_info = Teacher::find($teachers_id);
        return view('backend.report.teacher.attendance.detailmonthsearch',compact(['attendances','page','request','teacher_info','current_year','current_month','current_days','user_id']));
    }

    public function exportTeacherAttendance(Request $request)
    {
        $date = $this->helper->date_np_con_parm(date('Y-m-d'));
        $excShift = $request->excShift;
        $excDate = $request->excDate;
        return Excel::download(new AttendanceReportExport($excShift,$excDate), $date.'AttendanceReport.xlsx');
    }

    public function exportTeacherDetailMonthAttendance(Request $request)
    {
        $date = $this->helper->date_np_con_parm_month(date('Y-m-d'));
        // dd($request);
        $excUser = $request->user_id;
        $excCurrentYear = $request->current_year;
        $excCurrentMonth = $request->current_month;
        $excCurrentDays = $request->current_days;
        return Excel::download(new AttendanceDetailReport($excUser,$excCurrentYear,$excCurrentMonth,$excCurrentDays), $date.'AttendanceReport.xlsx');
    }

    public function salary(Request $request)
    {
        $page = 'salary';
        $nepali_month = $this->helper->date_np_con_parm_month(date('Y-m-d'));

        $teachers = Teacher::where('school_id', Auth::user()->school_id)
                                 ->where('batch_id', Auth::user()->batch_id)
                                ->with(['getTeacherIncome' => function ($query) use ($nepali_month) {
                                        $query->where('school_id', Auth::user()->school_id)
                                              ->where('batch_id', Auth::user()->batch_id)
                                              ->where('month', $nepali_month);
                                    }]);

        $teachers = $teachers->paginate(50); 
        return view('backend.report.teacher.salary.index',compact(['teachers','page','nepali_month']));
    }

    public function salarySearch(Request $request)
    {
        $page = 'salary';
        $filter_month = $request->filter_month;
        $teachers = Teacher::where('school_id', Auth::user()->school_id)
                                 ->where('batch_id', Auth::user()->batch_id);

        if($filter_month){
            $teachers = $teachers->with(['getTeacherIncome' => function ($query) use ($filter_month) {
                                        $query->where('school_id', Auth::user()->school_id)
                                              ->where('batch_id', Auth::user()->batch_id)
                                              ->where('month',$filter_month);
                                    }]);
        }
        else{
            $nepali_month = $this->helper->date_np_con_parm_month(date('Y-m-d'));
            $teachers = $teachers->with(['getTeacherIncome' => function ($query) use ($nepali_month) {
                                        $query->where('school_id', Auth::user()->school_id)
                                              ->where('batch_id', Auth::user()->batch_id)
                                              ->where('month', $nepali_month);
                                    }]);
        }
        $teachers = $teachers->paginate(50); 

        return view('backend.report.teacher.salary.search',compact(['teachers','page','request']));
    }

    public function exportTeacherSalary(Request $request)
    {
        $date = $this->helper->date_np_con_parm(date('Y-m-d'));
        $excMonth = $request->excMonth;
        return Excel::download(new SalaryExport($excMonth), $date.'TeacherSalaryReport.xlsx');
    }



    public function SubjectClassList(Request $request)
    {
        $page = 'subjectclasslist';
        $teacherhasperiods = Teacher::where('school_id', Auth::user()->school_id)
                            ->where('batch_id', Auth::user()->batch_id)
                            ->orderBy('sort_id','DESC')
                            ->orderBy('created_at','DESC');  
        $teacherhasperiods = $teacherhasperiods->with('getClassCount')->get(); 
        return view('backend.report.teacher.subjectclasslist.index',compact(['teacherhasperiods','page']));
    }

    public function exportTeacherSubjectClassList(Request $request)
    {
        $date = $this->helper->date_np_con_parm(date('Y-m-d'));
        if($request->export == 'exportExcel'){
            // dd('hello');
            return Excel::download(new ClassSubjectsExport(), $date.'ClassSubject.xlsx');

        }
        else{
            // dd('pdf');
            return Excel::download(new ClassSubjectsExport(), $date.'ClassSubject.pdf');

        }
    }

    public function exportAllTeacherReport(Request $request)
    {
        $month = $this->helper->date_np_con_parm_month(date('Y-m-d'));
        // dd($date);
        $date = $this->helper->date_np_con_parm(date('Y-m-d'));
        $excMonth = $month;
        $excDate = $date;
        return Excel::download(new AllTeacherExport($excMonth,$excDate), $date.'AttendanceReport.xlsx');
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
    public function show(Request $request)
    {
        // dd($request);
        $shifts = $request->shift_data;
        $teachers = $request->teacher_data;
        $date_month = $request->date_month;
        $page = $request->page;
        // dd($date_month);
        $url_request = substr($request->fullUrl(), (strpos($request->fullUrl(), '?') ? : -1) + 1);

        if ($page == 'profile') {
             $teachers_list = Teacher::where('school_id', Auth::user()->school_id)
                                ->where('batch_id', Auth::user()->batch_id);
            if($shifts){
                $teachers = $teachers_list->whereHas('getShiftTeacherCountList', function (Builder $query) use ($shifts){
                                            $query->where('shift_id', $shifts);
                                        });
            }
            $teachers = $teachers_list->get();
        // dd($teachers, $request , $page);
            return view('backend.report.teacher.show.profile',compact(['shifts','teachers','date_month','page','url_request','teachers']));
        }

        if($page == 'attendance'){
            $attendances_list = Teacher_has_attendance::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id);
            if($shifts){
                $attendances = $attendances_list->where('shift_id', $shifts);
            }
            $attendances = $attendances_list->groupBy('user_id')->get();             
            // dd($attendances);
            return view('backend.report.teacher.show.attendance',compact(['shifts','teachers','date_month','page','url_request','attendances']));
        }

        if($page == 'salary'){
            $teachers = TeacherIncome::where('school_id', Auth::user()->school_id)
                                     ->where('batch_id', Auth::user()->batch_id);
            if($shifts){
                $teachers = $teachers->whereHas('getShiftTeacherCountList', function (Builder $query) use ($shifts){
                                            $query->where('shift_id', $shifts);
                                        });
            }
            if($date_month){

                $teachers = $teachers->where('month',$date_month);
            }
            else{
                $nepali_month = $this->helper->date_np_con_parm_month(date('Y-m-d'));
                $teachers = $teachers->where('month', $nepali_month);
            }
            $teachers = $teachers->get();           
            // dd(date('mm'));
            return view('backend.report.teacher.show.salary',compact(['shifts','teachers','date_month','page','url_request','teachers']));
        }

        $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->orderBy('sort_id','ASC')
                        ->get();
        return view('backend.report.teacher.index',compact(['shifts']));
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
