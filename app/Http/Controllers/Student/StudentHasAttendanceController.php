<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Student;
use App\Student_has_attendance;
use Auth;

class StudentHasAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = Auth::user()->id;
        $total = '0';
        $total_present = '0';
        $total_absent = '0';
        $total_status = '0';
        $attendances = Student_has_attendance::where('user_id', $user_id)
                                            ->where('school_id',Auth::user()->school_id)
                                            ->where('batch_id',Auth::user()->batch_id)
                                            ->orderBy('id','DESC')
                                            ->with('getStudentOne');
        if($request->startdate || $request->enddate ){
            $attendances = $attendances->whereBetween('date_en',[$request->startdate, $request->enddate]);
            $total_present = $attendances->sum('status');
        }
        if($request->get('status') != ''){
            $attendances = $attendances->where('status',$request->status);
            $total_status = $attendances->count();
        }

        $total = $attendances->count();
        $total_absent = $total - $total_present;
        $attendancetot = $attendances->get();
        $response = [
            'attendancelists' => $attendancetot,
            'totallist' => $total,
            'totalpresentlist' => $total_present,
            'totalabsentlist' => $total_absent,
            'totalstatuslist' => $total_status
        ];
        return response()->json($response);

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
