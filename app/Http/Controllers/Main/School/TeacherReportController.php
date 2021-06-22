<?php

namespace App\Http\Controllers\Main\School;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Teacher_has_attendance;
use App\Teacher;
use App\Shift;
use Auth;


class TeacherReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = 'profile';
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        $shifts = Shift::where('school_id', $school_info->id)
                        ->where('is_active', True)
                        ->get();
        $teachers_list = Teacher::where('school_id', $school_info->id)
                                ->with('getTeacherUser')
                                ->with('getUser');
        $teachers = $teachers_list->paginate(50);

        return view('main.school.info.report.teacher.profile.index',compact(['teachers','page','shifts','school_info','settings']));
    }

    public function profileList(Request $request)
    {
        $page = 'profile';
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        $shifts = Shift::where('school_id', $school_info->id)
                        ->where('is_active', True)
                        ->get();
        $teachers_list = Teacher::where('school_id', $school_info->id)
                                ->with('getTeacherUser')
                                ->with('getUser');
        $teachers = $teachers_list->paginate(50);
        // dd($teachers);

        return view('main.school.info.report.teacher.profile.index',compact(['teachers','page','shifts','school_info','settings']));
    }

    public function profileSearchList(Request $request)
    {
        $page = 'profile';
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        $school_id = $school_info->id;

        $shifts = Shift::where('school_id', $school_info->id)
                        // ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->get();
        $teachers_list = Teacher::where('school_id', $school_info->id);
                                // ->where('batch_id', Auth::user()->batch_id);
        if ($request->search_data) {
            $teachers_list = $teachers_list->where('teacher_code', $request->search_data);
        }
        if ($request->shift_data) {
            $teachers_list = $teachers_list->whereHas('getShiftTeacherCountList', function (Builder $query) use ($request,$school_id){
                                                    $query->where('shift_id', $request->shift_data)
                                                          ->where('school_id', $school_id);
                                                          // ->where('batch_id', Auth::user()->batch_id);
                                                });
        }
        $teachers = $teachers_list->with('getTeacherUser')
                                ->with('getUser')
                                ->paginate(50);

        return view('main.school.info.report.teacher.profile.search',compact(['teachers','page','shifts','school_info','settings','request']));
    }

    public function subjectClassList(Request $request)
    {
        $page = 'subjectclasslist';
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        // dd($request);
        $teacherhasperiods = Teacher::where('school_id', $school_info->id)
                            ->orderBy('sort_id','DESC')
                            ->orderBy('created_at','DESC');  
        $teacherhasperiods = $teacherhasperiods->with('getClassCount')->get(); 
        return view('main.school.info.report.teacher.subjectclass.index',compact(['teacherhasperiods','page','school_info','settings']));
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

        $attendances_list = Teacher::where('school_id', $school_info->id)
                                 // ->where('batch_id', Auth::user()->batch_id)
                                ->with(['getTeacherAttendance' => function ($query) use ($date,$school_id) {
                                        $query->where('school_id', $school_id)
                                              // ->where('batch_id', Auth::user()->batch_id)
                                              ->where('date', $date);
                                    }]);   
        
        $attendances = $attendances_list->paginate(50);             
        return view('main.school.info.report.teacher.attendance.index',compact(['attendances','page','shifts','current_year','current_month','current_days','school_info','settings']));
    }

    public function attendanceSearchList(Request $request)
    {

        $page = 'attendance';
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        $school_id = $school_info->id;
        $shift_data = $request->shift_data;

        $shifts = Shift::where('school_id', $school_id)
                        // ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->get();
        $date = $request->filter_date;  

        $attendances = Teacher::where('school_id', $school_id);
                                 // ->where('batch_id', Auth::user()->batch_id);
        if ($request->shift_data) {
            $shift_data = $request->shift_data;
            $attendances = $attendances->where('school_id',$school_id)->whereHas('getShiftTeacherCountList', function (Builder $query) use ($shift_data,$school_id) {
                $query->where('school_id', $school_id)
                // ->where('batch_id', Auth::user()->batch_id)
                ->where('shift_id', $shift_data);
            })
            ;
        }

        if($request->filter_date){
            $date = $request->filter_date;
            $attendances = $attendances->with(['getTeacherAttendance' => function ($query) use ($date,$school_id) {
                                        $query->where('school_id', $school_id)
                                              // ->where('batch_id', Auth::user()->batch_id)
                                              ->where('date',$date);
                                    }]);
        }
        else{
            $date = $this->helper->date_np_con_parm(date('Y-m-d')); 
            $attendances = $attendances->with(['getTeacherAttendance' => function ($query) use ($date,$school_id) {
                                        $query->where('school_id', $school_id)
                                              // ->where('batch_id', Auth::user()->batch_id)
                                              ->where('date', $date);
                                    }]);
        }        

        $attendances = $attendances->paginate(50);
        return view('main.school.info.report.teacher.attendance.search',compact(['attendances','page','shifts','school_info','settings','shift_data','request']));

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

        $attendances = Teacher_has_attendance::where('school_id', $school_id)
                                        // ->where('batch_id', Auth::user()->batch_id)
                                        ->where('user_id', $user_id);

        if ($request->shift_data) {
            $attendances = $attendances->where('shift_id', $request->shift_data);
        }
        $attendances = $attendances->get();
        $teachers_id = Teacher::where('user_id',$user_id)->value('id');
        $teacher_info = Teacher::find($teachers_id);
       
        return view('main.school.info.report.teacher.attendance.detail',compact(['attendances','page','school_info','settings','shift_data','request','current_month','current_days','current_year','teacher_info']));

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
        
        $attendances = Teacher_has_attendance::where('school_id', $school_id)
                                // ->where('batch_id', Auth::user()->batch_id)
                                ->where('user_id', $user_id);

        if ($request->shift_data) {
            $attendances = $attendances->where('shift_id', $request->shift_data);
        }
        $attendances = $attendances->get();
        $teachers_id = Teacher::where('user_id',$user_id)->value('id');
        $teacher_info = Teacher::find($teachers_id);
        // dd($attendances);
       
        return view('main.school.info.report.teacher.attendance.monthdetailsearch',compact(['attendances','page','school_info','settings','request','current_month','current_days','current_year','teacher_info']));

    }

    public function salaryList(Request $request)
    {

        $page = 'salary';
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        $nepali_month = $this->helper->date_np_con_parm_month(date('Y-m-d'));
        $school_id = $school_info->id;

        $teachers = Teacher::where('school_id', $school_info->id)
                                 // ->where('batch_id', Auth::user()->batch_id)
                                ->with(['getTeacherIncome' => function ($query) use ($nepali_month,$school_id) {
                                        $query->where('school_id', $school_id)
                                              // ->where('batch_id', Auth::user()->batch_id)
                                              ->where('month', $nepali_month);
                                    }]);

        $teachers = $teachers->paginate(50); 
        return view('main.school.info.report.teacher.salary.index',compact(['teachers','page','school_info','settings','nepali_month']));
    }

    public function salarySearchList(Request $request)
    {

        $page = 'salary';
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        $nepali_month = $this->helper->date_np_con_parm_month(date('Y-m-d'));
        $school_id = $school_info->id;
        // dd($request);

        $filter_month = $request->filter_month;
        $teachers = Teacher::where('school_id', $school_id);
                                 // ->where('batch_id', Auth::user()->batch_id);

        if($filter_month){
            $teachers = $teachers->with(['getTeacherIncome' => function ($query) use ($filter_month,$school_id) {
                                        $query->where('school_id', $school_id)
                                              // ->where('batch_id', Auth::user()->batch_id)
                                              ->where('month',$filter_month);
                                    }]);
        }
        else{
            $nepali_month = $this->helper->date_np_con_parm_month(date('Y-m-d'));
            $teachers = $teachers->with(['getTeacherIncome' => function ($query) use ($nepali_month,$school_id) {
                                        $query->where('school_id', $school_id)
                                              // ->where('batch_id', Auth::user()->batch_id)
                                              ->where('month', $nepali_month);
                                    }]);
        }
        $teachers = $teachers->paginate(50); 
        return view('main.school.info.report.teacher.salary.search',compact(['teachers','page','school_info','settings','nepali_month','request']));
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
