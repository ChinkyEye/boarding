<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use App\Helper\Helper;
use Auth;
use App\Teacher_has_attendance;
use App\Teacher_has_shift;
use App\Teacher;
use App\Shift;
use Response;
use App\Exports\TeacherhasattendancesExport;
use Maatwebsite\Excel\Facades\Excel;

class TeacherHasAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
      $date_en = Date('Y-m-d');
      $current_date = $this->helper->date_np_con_parm($date_en);
      $check_date = Teacher_has_attendance::where('school_id', Auth::user()->school_id)
                                          ->where('batch_id', Auth::user()->batch_id)
                                          ->where('date',$current_date)
                                          ->where('shift_id',$request->shift_id)
                                          ->count();
      $teacher_id = $request->input('teacher_id');
      $status_id = $request->input('status');

      if($check_date == 0){
        foreach( $teacher_id AS $key=>$teacher ){
          $user_id = Teacher::where('id', $teacher)->value('user_id');
          $teacherhasattendance= Teacher_has_attendance::create([
                                    'teacher_id' => $teacher,
                                    'user_id' => $user_id,
                                    'shift_id' => $request->shift_id,
                                    'date' => $current_date,
                                    'date_en' => $date_en,
                                    'status' => (!empty($status_id[$key]) ? '1' : '0'),
                                    'school_id' => Auth::user()->school_id,
                                    'batch_id' => Auth::user()->batch_id,
                                    'created_by' => Auth::user()->id,
                                    'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
                                  ]);
        }
      }else{
        foreach( $teacher_id AS $key=>$teacher ){
          $get_tchr_atnd_id = Teacher_has_attendance::where('date',$current_date)
                                                    ->where('teacher_id',$teacher)
                                                    ->value('id'); 
          $std_tchr_update= Teacher_has_attendance::find($get_tchr_atnd_id);
          $std_tchr_update->date = $current_date;
          $std_tchr_update->date_en = $date_en;
          $std_tchr_update->status = (!empty($status_id[$key]) ? '1' : '0');
          $std_tchr_update->updated_by = Auth::user()->id;
          $std_tchr_update->update();
        }
      }
      $pass = array(
        'message' => 'Data added successfully!',
        'alert-type' => 'success'
      );
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

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // public function update(Request $request, Teacher_has_attendance $teacher_has_attendance)
    // {
    //     if($teacher_has_attendance->update($request->all())){
    //         $notification = array(
    //             'message' => 'Data updated successfully!',
    //             'alert-type' => 'success'
    //         );
    //     }else{
    //         $notification = array(
    //             'message' => 'Data could not be updated!',
    //             'alert-type' => 'error'
    //         );
    //     }
    //     return redirect('home/primary-entry/teacher-attendance')->with($notification);
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
}
