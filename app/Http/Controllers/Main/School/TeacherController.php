<?php

namespace App\Http\Controllers\Main\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use App\Teacher;
use App\Teacher_has_shift;
use App\Teacher_has_period;
use App\Shift;
use App\User;
use App\Setting;
use App\Exports\TeachersExport;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{

  public function export(Request $request)
  {
    return Excel::download(new TeachersExport($request->id), 'Teacher.xlsx');
  }

  public function index(Request $request)
  {
    // dd($request->slug);
    $settings = $this->schoolCheck($request)['setting'];
    $school_info = $this->schoolCheck($request)['school_info'];

    $shifts = Shift::where('school_id', $school_info->id)->where('is_active',True)->get();
    return view('main.school.info.teacher.index',compact('settings','school_info','shifts'));
  }


  public function getAllTeacher(Request $request)
  {
    $school_id = Setting::where('slug', $request->slug)->value('id');
    $school_info = Setting::find($school_id);
    $columns = array(
      0 =>'id', 
      1 =>'f_name',
      2 =>'teacher_code',
      3 =>'email',
      4 =>'created_by',
      5 =>'status',
      6 =>'action',
    );
    $totalData = Teacher::where('school_id', $school_id)->where('is_active','1')->orderBy('id','desc')->count();
    $totalFiltered = $totalData; 
    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');
    $filter_shift = $request->data['filter_shift'];
    $filter_class = $request->data['filter_class'];
    $filter_section = $request->data['filter_section'];

    if(empty($request->input('search.value')))
    {       
      $posts = Teacher::offset($start);
      $teachershifts = Teacher_has_shift::offset($start);
      if(!empty($request->data['filter_shift'])){
        $shift_id = Shift::value('id');
        $posts = $posts->whereHas('getShiftTeacherManyList', function (Builder $query) use ($filter_shift) {
          $query->where('shift_id', $filter_shift);
        });
      }
      if(!empty($request->data['filter_class'])){
        $posts = $posts->whereHas('getTeacherPeriod', function (Builder $query) use ($filter_class) {
          $query->where('class_id', $filter_class);
        });
      }
      if(!empty($request->data['filter_section'])){
        $posts = $posts->whereHas('getTeacherPeriod', function (Builder $query) use ($filter_section) {
          $query->where('section_id', $filter_section);
        });
      }
    }

    else {
      $search = $request->input('search.value'); 

      $posts = Teacher::offset($start)
                     ->whereHas('getTeacherUser', function($query) use ($search){
                        $query->where('name', 'LIKE', '%'.$search.'%')
                              ->orWhere('middle_name','LIKE', '%'.$search.'%')
                              ->orWhere('last_name','LIKE', '%'.$search.'%')
                              ->orWhere('email','LIKE', '%'.$search.'%');
                     })
                    ->orWhere('phone', 'LIKE',"%{$search}%")
                    ->orWhere('teacher_code', 'LIKE',"%{$search}%");  
      // $posts = Teacher::offset($start)
      // ->where('f_name', 'LIKE',"%{$search}%")
      // ->orWhere('m_name', 'LIKE',"%{$search}%")
      // ->orWhere('l_name', 'LIKE',"%{$search}%")
      // ->orWhere('email', 'LIKE',"%{$search}%")
      // ->orWhere('teacher_code', 'LIKE',"%{$search}%");
      if(!empty($request->data['filter_shift'])){
        $posts = $posts->whereHas('getShiftTeacherList', function (Builder $query) use ($filter_shift) {
                    $query->where('shift_id', $filter_shift);
                  });
      }
      if(!empty($request->data['filter_class'])){
        $posts = $posts->whereHas('getTeacherPeriod', function (Builder $query) use ($filter_class) {
                    $query->where('class_id', $filter_class);
                  });
      }
      if(!empty($request->data['filter_section'])){
        $posts = $posts->whereHas('getTeacherPeriod', function (Builder $query) use ($filter_section) {
                   $query->where('section_id', $filter_section);
                 });
      }
      $totalFiltered = Teacher::where('is_active','1')
                    ->whereHas('getTeacherUser', function($query) use ($search){
                        $query->where('name', 'LIKE', '%'.$search.'%')
                              ->orWhere('middle_name','LIKE', '%'.$search.'%')
                              ->orWhere('last_name','LIKE', '%'.$search.'%')
                              ->orWhere('email','LIKE', '%'.$search.'%');
                     })
                    ->orWhere('phone', 'LIKE',"%{$search}%")
                    ->orWhere('teacher_code', 'LIKE',"%{$search}%") 
      // ->where('f_name', 'LIKE',"%{$search}%")
      // ->orWhere('m_name', 'LIKE',"%{$search}%")
      // ->orWhere('l_name', 'LIKE',"%{$search}%")
      // ->orWhere('email', 'LIKE',"%{$search}%")
      // ->orWhere('teacher_code', 'LIKE',"%{$search}%")
      ->count();
    }

    $posts = $posts->where('school_id', $school_id)->where('is_active','1')->limit($limit)
    ->orderBy($order,$dir)
    ->get();
    $data = array();
    if(!empty($posts))
    {
      foreach ($posts as $index=>$post)
      {
        if($post->is_active == '1') 
        { 
          $attribute_title = 'Click to deactivate'; 
          $class_icon = 'fa-check check-css'; 
        }
        else{ 
          $attribute_title = 'Click to activate'; 
          $class_icon = 'fa-times cross-css'; 
        }
        $nestedData['id'] = $index+1;
        $nestedData['f_name'] = $post->getTeacherUser->name." ".$post->getTeacherUser->middle_name." ".$post->getTeacherUser->last_name;
        $nestedData['teacher_code'] = $post->teacher_code;
        $nestedData['email'] = $post->getTeacherUser->email;
        $nestedData['created_by'] = "<div class='text-center'>".$post->getUser->name."</div>";
        $nestedData['status'] = "
          <span class='d-block text-center' title='".$attribute_title."'>
            <i class='fa ".$class_icon."'></i>
          </span>
        ";
        $nestedData['action'] = "
        <div class='text-center'>
         
          <a href='".route('main.teacher.show',[$school_info->slug,$post->slug])."' class='btn btn-xs btn-outline-success' data-toggle='tooltip' data-placement='top' title='View Detail'><i class='fa fa-eye'></i></a>
        
        </div>
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
    // <a href='".route('main.teacher.export',[$school_info->slug,$post->id])."' class='btn btn-xs btn-outline-success' data-toggle='tooltip' data-placement='top' title='View Detail'><i class='fa fa-download'></i></a>
  } 
  
  public function show(Request $request, $school_slug, $slug)
  {
    $settings = $this->schoolCheck($request)['setting'];
    $school_info = $this->schoolCheck($request)['school_info'];

    $teacher_id = Teacher::where('school_id', $school_info->id)->where('slug', $slug)->value('id');
    $teachers_detail = Teacher::findOrFail($teacher_id);
    $teachers = User::findOrFail($teachers_detail->user_id);
    // dd($school_info, $slug, $school_info->id, $teacher_id, $teachers_detail, $teachers);
    $page = $teachers_detail->f_name.' '.substr($teachers_detail->m_name, 0, 1).' '.substr($teachers_detail->l_name, 0, 1)." Detail";
    return view('main.school.info.teacher.show', compact(['settings','school_info','teachers','teachers_detail','page']));
  }
}
