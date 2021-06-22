<?php

namespace App\Http\Controllers\Teacher;

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

class TeacherHasAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        $shifts = Shift::get();
        return view('teacher.attendance.teacher_has_attendance.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDateList(Request $request){
      $data_id = $request->input('data_id');
      $date_list_data = Teacher::get();
      return Response::json($class_list_data);
    }

    public function getTeacherList(Request $request){
      $all_data = json_decode($request->parameters, true);
      if($all_data['date'] && $all_data['date'] != date('Y-m-d')){
      // dd($all_data['shift_id'],$all_data['date']);
        $datas = Teacher_has_attendance::orderBy('id','desc');
        $dates = date('Y-m-d', strtotime($all_data['date']));
        $datas = $datas->where('date', $dates);
        if($all_data['shift_id']){
          $datas =  $datas->whereHas('getAttendShiftTeacherList', function (Builder $query) use ($all_data) {
            $query->where('shift_id', $all_data['shift_id']);
          });
        }
      }else{
        $dates = Date('Y-m-d');
        $datas = Teacher::orderBy('id','ASC');
        if($all_data['shift_id']){
          $datas =  $datas->whereHas('getShiftTeacherList', function (Builder $query) use ($all_data) {
            $query->where('shift_id', $all_data['shift_id']);
          });
        }
      }
      $datas = $datas->get();
      $shifts = Shift::where('id',$all_data['shift_id'])->value('name');
      return view('backend.attendance.teacher_has_attendance.ajax-attendance', compact('datas','dates','shifts'));
    }

    public function getAllTeacherAttendance(Request $request)
    {
        $columns = array(
            0 =>'id', 
            1 =>'teacher_id',
            2 =>'date',
            3 =>'created_by',
            4 =>'status',
            5 =>'action',
        );
        $totalData = Teacher::orderBy('id','desc')->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $posts = Teacher::offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();
        }
        else {
            $search = $request->input('search.value'); 
            $posts =  Teacher::where('teacher_id', 'LIKE',"%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = Teacher::where('teacher_id', 'LIKE',"%{$search}%")
            ->count();
        }
        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $index=>$post)
            {
                if($post->status == '1') 
                { 
                    $attribute_title = 'Click to deactivate'; 
                    $class_icon = 'fa-check check-css'; 
                }
                else{ 
                    $attribute_title = 'Click to activate'; 
                    $class_icon = 'fa-times cross-css'; 
                }
                $nestedData['id'] = $index+1;
                $nestedData['teacher_id'] = $post->f_name."". $post->m_name;
                $nestedData['date'] = $post->date;
                $nestedData['created_by'] = $post->getUser->name;
                $nestedData['status'] = "
                <a class='d-block text-center' href='' data-toggle='tooltip' data-placement='top' title='".$attribute_title."'>
                <i class='fa ".$class_icon."'></i>
                </a>
                ";
                $nestedData['action'] = "
                <a href=".route('admin.teacher-attendance.edit',$post->id)." class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top'title='Update'><i class='fas fa-edit'></i></a> 
                | 
                <form action=".route('admin.teacher-attendance.destroy',$post->id)." method='post' class='d-inline-block' data-toggle='tooltip' data-placement='top' title='Permanent Delete'>
                <input type='hidden' name='_token' value='".csrf_token()."'>
                <input name='_method' type='hidden' value='DELETE'>
                <button class='btn btn-xs btn-outline-danger' type='submit'><i class='fa fa-trash'></i></button>
                </form>
                ";
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),  
            "recordsTotal"    => intval($totalData),  
            "recordsFiltered" => intval($totalFiltered), 
            "data"            => $data   
        );
        echo json_encode($json_data); 
    }


    public function create()
    {
        $teachers = Teacher::get();
        return view('backend.attendance.teacher_has_attendance.create', compact('teachers'));
    }

    public function isAttendence($id)
    {
        $counts = Teacher_has_attendance::where('id',$id)->count();
        $teacherhasattendance = Teacher_has_attendance::get();
        $teachers = Teacher::get();
        return view('backend.attendance.teacher_has_attendance.create', compact('teachers','teacherhasattendance','counts','id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $this->validate($request, [
    //         'teacher_id' => 'required',
    //         'date' => 'required',
    //     ]);

    //     $teacherhasattendance = Teacher_has_attendance::create([
    //         'teacher_id' => $request['teacher_id'],
    //         'date' => $request['date'],
    //         'status' => $request['status'],
    //         'created_by' => Auth::user()->id,
    //     ]);
    //     $pass = array(
    //         'message' => 'Data added successfully!',
    //         'alert-type' => 'success'
    //     );
    //     return redirect()->route('admin.teacher-attendance.index')->with($pass)->withInput();
    // }



    public function store(Request $request)
    {
      // dd($request);
      $current_date = Date('Y-m-d');
      $check_date = Teacher_has_attendance::where('date',$current_date)->count();
      // dd($check_date);
      $teacher_id = $request->input('teacher_id');
      $status_id = $request->input('status');
      if($check_date == 0){
            foreach( $teacher_id AS $key=>$teacher ){
                $teacherhasattendance= Teacher_has_attendance::create([
                    'teacher_id' => $teacher,
                    'date' => $current_date,
                    'status' => (!empty($status_id[$key]) ? '1' : '0'),
                    'school_id' => Auth::user()->school_id,
'batch_id' => Auth::user()->batch_id,
                    'created_by' => Auth::user()->id,
                    'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
                ]);
            }
        }else{
          foreach( $teacher_id AS $key=>$teacher ){
                $get_tchr_atnd_id = Teacher_has_attendance::where('date',$current_date)->where('teacher_id',$teacher)->value('id'); 
                $std_tchr_update= Teacher_has_attendance::find($get_tchr_atnd_id);
                $std_tchr_update->teacher_id = $teacher;
                $std_tchr_update->date = $current_date;
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
        $teacherhasattendance = Teacher_has_attendance::where('id', $id)->get();
        return view('backend.attendance.teacher_has_attendance.edit', compact('teacherhasattendance'));
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

    public function update(Request $request, $id)
    {
        $teacherhasattendance = Teacher_has_attendance::find($id);
        $teacherhasattendance->date = $request->input('date');
        $teacherhasattendance->status = $request->input('status');
        if($teacherhasattendance->update()){
           $notification = array(
              'message' => 'Data is updated successfully!',
              'alert-type' => 'success'
          );  
       }else{
             $notification = array(
              'message' => 'Data could not updated!',
              'alert-type' => 'error'
          );
       }
        return redirect()->route('admin.teacher-attendance.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Teacher_has_attendance::find($id);
        if($data->delete()){
            $notification = array(
              'message' => 'Data deleted successfully!',
              'alert-type' => 'success'
          );
        }else{
            $notification = array(
              'message' => 'Data could not be successfully!',
              'alert-type' => 'error'
          );
        }
        return back()->with($notification)->withInput();
    }
}
