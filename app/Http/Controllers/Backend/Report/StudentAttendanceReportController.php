<?php

namespace App\Http\Controllers\Backend\Report;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Auth;
use App\Shift;
use App\Student_has_attendance;



class StudentAttendanceReportController extends Controller
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
        return view('backend.attendance.studenthasattendance.report.index',compact(['shifts']));
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

    public function getReportStudentAttendance(Request $request)
    {
        // dd($request);
        $shift_data = $request->shift_data;
        $class_data = $request->class_data;
        $section_data = $request->section_data;
        $student_data = $request->student_data;
        // dd($student_data);
        $filter_month = $request->filter_month;

        $type_data = $request->type_data;
        $studentattendance_list = Student_has_attendance::where('school_id',Auth::user()->school_id)
        ->where('batch_id', Auth::user()->batch_id)
        ->where('status',$type_data)
        ->whereMonth('date', $filter_month)
        ->orderBy('id','DESC');
        // dd($studentattendance_list->get());
       if(!empty($shift_data)){
           $studentattendance_list = $studentattendance_list->whereHas('getStudent', function (Builder $query) use ($shift_data) {
                             $query->where('shift_id', $shift_data);
                         });
       }
       if(!empty($section_data)){
           $studentattendance_list = $studentattendance_list->whereHas('getStudent', function (Builder $query) use ($section_data) {
                             $query->where('section_id', $section_data);
                         });
       }
       if(!empty($class_data)){
           $studentattendance_list = $studentattendance_list->whereHas('getStudent', function (Builder $query) use ($class_data) {
                             $query->where('class_id', $class_data);
                         });
       }
       if(!empty($student_data)){
           $studentattendance_list = $studentattendance_list->where('student_id',$student_data);
       }
       if(!empty($type_data)){
           $studentattendance_list = $studentattendance_list->where('status',$type_data);
       }
       $total_count = $studentattendance_list->count('status');
       $studentattendance_list = $studentattendance_list->with('getStudentName')->paginate(50);
       return view('backend.attendance.studenthasattendance.report.create',compact(['studentattendance_list','total_count']));
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
