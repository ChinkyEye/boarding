<?php

namespace App\Http\Controllers\Backend\Report;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Auth;
use App\Shift;
use App\Teacher_has_attendance;



class TeacherAttendanceReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->orderBy('sort_id','ASC')
                        ->get();
        return view('backend.attendance.teacher_has_attendance.report.index',compact(['shifts']));
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

    public function getReportTeacherAttendance(Request $request)
    {
        // dd($request);
        $shift_data = $request->shift_data;
        $teacher_data = $request->teacher_data;
        // dd($teacher_data);
        $filter_month = $request->filter_month;
        // $filter = '9';

        $type_data = $request->type_data;
        // dd($type_data);
        $teacherattendance_list = Teacher_has_attendance::where('school_id',Auth::user()->school_id)
        ->where('batch_id', Auth::user()->batch_id)
        ->where('status',$type_data)
        ->whereMonth('date', $filter_month)
        ->orderBy('id','DESC');
        // dd($teacherattendance_list->get());
        if(!empty($shift_data)){
            $teacherattendance_list = $teacherattendance_list->whereHas('getAttendShiftTeacherList', function (Builder $query) use ($shift_data) {
              $query->where('shift_id', $shift_data);
           });
            // dd($teacherattendance_list);
        }
        
        if(!empty($teacher_data)){
            $teacherattendance_list = $teacherattendance_list->where('user_id',$teacher_data);
        }
        if(!empty($type_data)){
            // dd($type_data);
            $teacherattendance_list = $teacherattendance_list->where('status',$type_data);
            // dd($teacherattendance_list->get());

        }
        $total_count = $teacherattendance_list->count('status');
        // $net_total_fee = $teacherattendance_list->sum('nettotal');
        // $total_discount = $teacherattendance_list->sum('discount');
        // $total_fine = $teacherattendance_list->sum('fine');
        $teacherattendance_list = $teacherattendance_list->with('getTeacherName')->paginate(50);
        return view('backend.attendance.teacher_has_attendance.report.create',compact(['teacherattendance_list','total_count']));
        // return view('backend.attendance.teacher_has_attendance.report.create',compact(['teacherattendance_list','total_fee','total_discount','total_fine','net_total_fee']));
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
