<?php

namespace App\Http\Controllers\Main\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Student_has_attendance;
use App\Student;
use App\Shift;
use Auth;

class StudentReportController extends Controller
{
    public function profileList(Request $request)
    {
        $page = 'profile';
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        $shifts = Shift::where('school_id', $school_info->id)
                        // ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->get();
        $students_list = Student::where('school_id', $school_info->id)
                                // ->where('batch_id', Auth::user()->batch_id)
                                ->with('getStudentUser')
                                ->withCount('Student_has_parent')
                                ->with('Student_has_parent')
                                ->with('getUser');
        $students = $students_list->paginate(50);
        // dd($students);

        return view('main.school.info.report.student.profile.index',compact(['students','page','shifts','school_info','settings']));
    }

    public function profileSearchList(Request $request)
    {
        $page = 'profile';
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        // dd($request);
        $shifts = Shift::where('school_id', $school_info->id)
                        // ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->get();
        $students_list = Student::where('school_id', $school_info->id);
                                // ->where('batch_id', Auth::user()->batch_id);
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
        return view('main.school.info.report.student.profile.search',compact(['students','page','shifts','school_info','settings','request']));
    }


    public function attendanceList(Request $request)
    {
        $page = 'attendance';
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];

        $shifts = Shift::where('school_id', $school_info->id)
                        // ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->get();
                        
        $date = $this->helper->date_np_con_parm(date('Y-m-d'));  
                   
        $current_year = $this->helper->date_np_con_parm_year(date('Y-m-d'));
        $current_month = $this->helper->date_np_con_parm_month(date('Y-m-d'));
        $current_days_in_month = $this->helper->current_days_in_month(substr($current_year,2),$current_month);
        $current_days = $current_days_in_month;
        $school_id = $school_info->id;
        // dd($school_id);

        $attendances_list = Student::where('school_id', $school_info->id)
                                 // ->where('batch_id', Auth::user()->batch_id)
                                ->with(['getStudentAttendance' => function ($query) use ($date,$school_id) {
                                        $query->where('school_id', $school_id)
                                              // ->where('batch_id', Auth::user()->batch_id)
                                              ->where('date', $date);
                                    }]);   
        
        $attendances = $attendances_list->paginate(50);   
        // dd($attendances);          
        return view('main.school.info.report.student.attendance.index',compact(['attendances','page','shifts','current_year','current_month','current_days','school_info','settings']));
    }

    public function attendanceSearchList (Request $request)
    {
        $page = 'attendance';
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        $school_id = $school_info->id;
        $shifts = Shift::where('school_id', $school_id)
                        // ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->get();
        $date = $request->filter_date;  
        

        $attendances = Student::where('school_id', $school_id);
                                 // ->where('batch_id', Auth::user()->batch_id);
        if ($request->shift_data) {
            $shift_data = $request->shift_data;
            $attendances = $attendances->where('school_id',$school_id)
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
            $attendances = $attendances->with(['getStudentAttendance' => function ($query) use ($date,$school_id) {
                                        $query->where('school_id', $school_id)
                                              // ->where('batch_id', Auth::user()->batch_id)
                                              ->where('date',$date);
                                    }]);
        }
        else{
            $date = $this->helper->date_np_con_parm(date('Y-m-d')); 
            $attendances = $attendances->with(['getStudentAttendance' => function ($query) use ($date,$school_id) {
                                        $query->where('school_id', $school_id)
                                              // ->where('batch_id', Auth::user()->batch_id)
                                              ->where('date', $date);
                                    }]);
        }        

        // $attendances = $attendances->paginate(50);
        $attendances = $attendances->with('getStudentUser')
                                ->with('getUser')
                                ->paginate(50);
                                // dd($attendances);
        return view('main.school.info.report.student.attendance.search',compact(['attendances','page','shifts','school_info','settings','request']));
    }

    public function attendanceDetailList($slug,$user_id,Request $request)
    {
        // dd($slug,$user_id);
        // dd($request);
        $shift_data = $request->shift_data;
        // dd($shift_data);

        $page = 'attendance';
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        $school_id = $school_info->id;
        $page = 'attendance';

        $date = $this->helper->date_np_con_parm(date('Y-m-d'));  
        $current_year = $this->helper->date_np_con_parm_year(date('Y-m-d'));
        $current_month = $this->helper->date_np_con_parm_month(date('Y-m-d'));
        $current_days_in_month = $this->helper->current_days_in_month(substr($current_year,2),$current_month);
        $current_days = $current_days_in_month;

        $attendances = Student_has_attendance::where('school_id', $school_id)
                                        // ->where('batch_id', Auth::user()->batch_id)
                                        ->where('user_id', $user_id);

        if ($request->shift_data) {
            $attendances = $attendances->where('shift_id', $request->shift_data);
        }
        $attendances = $attendances->get();
        $students_id = Student::where('user_id',$user_id)->value('id');
        $student_info = Student::find($students_id);
       
        return view('main.school.info.report.student.attendance.detail',compact(['attendances','page','school_info','settings','shift_data','request','current_month','current_days','current_year','student_info']));

    }

    public function attendanceMonthDetailList($slug,Request $request)
    {
        // dd($slug,$user_id);
        // dd($request);
        // dd($shift_data);

        $page = 'attendance';
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        $school_id = $school_info->id;
        $user_id = $request->user_id;

        $date = $this->helper->date_np_con_parm(date('Y-m-d'));  
        $current_year = $this->helper->date_np_con_parm_year(date('Y-m-d'));
        $current_month = $request->filter_month;
        $current_days_in_month = $this->helper->get_date_nepali_parm_month_days(substr($current_year,2),$current_month);
        $current_days = $current_days_in_month;
        
        $attendances = Student_has_attendance::where('school_id', $school_id)
                                // ->where('batch_id', Auth::user()->batch_id)
                                ->where('user_id', $user_id);

        if ($request->shift_data) {
            $attendances = $attendances->where('shift_id', $request->shift_data);
        }
        $attendances = $attendances->get();
        $students_id = Student::where('user_id',$user_id)->value('id');
        $student_info = Student::find($students_id);
        // dd($attendances);
       
        return view('main.school.info.report.student.attendance.monthdetailsearch',compact(['attendances','page','school_info','settings','request','current_month','current_days','current_year','student_info']));

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
