<?php

namespace App\Http\Controllers\Main\School;

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
    public function index(Request $request)
    {
      $settings = $this->schoolCheck($request)['setting'];
      $school_info = $this->schoolCheck($request)['school_info'];
      $shifts = Shift::where('school_id', $school_info->id)->where('is_active','1')->get();
      $page = "Teacher Attendance";
      return view('main.school.info.teacher_has_attendance.index', compact('settings','school_info','shifts','page'));
    }

    public function getDateList(Request $request){
      $data_id = $request->input('data_id');
      $date_list_data = Teacher::get();
      return Response::json($class_list_data);
    }

    public function getTeacherList(Request $request, $school_slug){
      $settings = $this->schoolCheck($request)['setting'];
      $school_info = $this->schoolCheck($request)['school_info'];

      
      // ------------------------
      $all_data = json_decode($request->parameters, true);
      $date = $all_data['date'];
      if($date == NULL){
         // $date = date('Y-m-d');
         $date = $this->helper->date_np_con_parm(date('Y-m-d'));
      }
      //yp chai current date lai cocnvert gareko.
      $nepali_date = $this->helper->date_np_con_parm(date('Y-m-d'));

      $shift_id = $all_data['shift_id'];
      $getdatas = Teacher_has_attendance::where('school_id', $school_info->id)->where('date',$date)->orderBy('id','desc');
      if($shift_id != Null){
        $datas =  $getdatas->whereHas('getAttendShiftTeacherList', function (Builder $query) use ($shift_id) {
                                    $query->where('shift_id', $shift_id);
                                  });
      }else{
        $datas =  $getdatas;
      }
      $check_count = $datas->count();
      if($check_count){
        $datas = $getdatas;
      }
      else{
        
      }
      $datas = $datas->get();
      // dd($datas);
      $absent_count = $datas->where('status',False)->count();
      $shifts = Shift::where('id',$shift_id)->value('name');
      return view('main.school.info.teacher_has_attendance.ajax-attendance', compact('settings','school_info','datas','date','shifts','check_count','absent_count','shift_id','nepali_date'));
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

    public function isAttendence($id)
    {
        $counts = Teacher_has_attendance::where('id',$id)->count();
        $teacherhasattendance = Teacher_has_attendance::get();
        $teachers = Teacher::get();
        return view('main.school.info.attendance.teacher_has_attendance.create', compact('teachers','teacherhasattendance','counts','id'));
    }

    
}
