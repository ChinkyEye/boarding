<?php

namespace App\Http\Controllers\Main\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use App\Helper\Helper;
use Auth;
use Validator;
use Response;
use File;
use App\Student;
use App\User;
use App\Studenthascount;
use App\Shift;
use App\SClass;
use App\Section;
use App\Teacher;
use App\Subject;
use App\Nationality;
use App\Student_has_parent;
use App\Teacher_has_period;
use App\Batch;
use App\Class_has_shift;
use App\Class_has_section;
use App\Book;
use App\Exam;
use App\Examhasclass;
use Illuminate\Support\Facades\Hash;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function export(Request $request)
    {
      $excShift = $request->excShift;
      $excClass = $request->excClass;
      $excSection = $request->excSection;
      return Excel::download(new StudentsExport($excShift,$excClass,$excSection), 'Student.xlsx');
    }

    public  function checkemail(Request $request)
    {
       $email = $request->get('email');
        $data = User::
        where('email','=', $email)
        ->count();
       if($data > 0)
       {
        echo 'not_unique';
       }
       else
       {
        echo 'unique';
       }
    }

    public  function checkrollno(Request $request)
    {
       $school_info = $this->schoolCheck($request)['school_info'];
       $roll_no = $request->get('roll_no');
       // dd($request->shift_id);
       $shift = Shift::where('school_id', $school_info->id)->where('id', $request->shift_id)->value('name');
       $class = SClass::where('school_id', $school_info->id)->where('id', $request->class_id)->value('name');
       $section = Section::where('school_id', $school_info->id)->where('id', $request->section_id)->value('name');
       $batch = Batch::where('school_id', $school_info->id)->where('id', $request->batch_id)->value('name');
       // dd($shift,$class,$section);
       $roll_no = $request->roll_no;
       $actual_roll_no = $batch.'-'.$shift.'-'.$class.'-'.$section.'-'.$request->roll_no.'-'.$school_info->id;
       // dd($actual_roll_no);
        $data = Student::
        where('actual_roll_no','=', $actual_roll_no)
        ->count();

       if($data > 0){
         $response = array(
           'status' => 'failure',
           'msg' => ' Sorry! This roll_no is already inserted.',
         );
       }else{
          $response = array(
           'status' => 'success',
           'msg' => 'This roll_no is available',
         );
       }
       return Response::json($response);
      
    }

    public function detailPrint(Request $request)
    {
      $settings = $this->schoolCheck($request)['setting'];
      $school_info = $this->schoolCheck($request)['school_info'];
      $shifts = Shift::where('school_id', $school_info->id)->get();
      $classes = SClass::where('school_id', $school_info->id)->get();
      $sections = Section::where('school_id', $school_info->id)->get();
      $nationalities = Nationality::where('school_id', $school_info->id)->get();
      $batchs = Batch::where('school_id', $school_info->id)->get();
      $students = Student::where('school_id', $school_info->id)->where('id', $request->id)->withCount('Student_has_parent')->get();
      return view('main.school.info.student.detail.detail', compact(['students','shifts','classes','sections','nationalities','batchs','school_info','settings']));
    }

    public function index(Request $request)
    {
      $settings = $this->schoolCheck($request)['setting'];
      $school_info = $this->schoolCheck($request)['school_info'];

      $shifts = Shift::where('school_id', $school_info->id)->where('is_active','1')->get();
      return view('main.school.info.student.detail.index',compact('settings','school_info','shifts'));
    }

    public function getClassList(Request $request){
      $school_info = $this->schoolCheck($request)['school_info'];
      $shift_id = $request->input('shift_id');
      // $class_list_data = SClass::where('school_id',$school_info->id)->whereHas('getClassList', function (Builder $query) use ($shift_id) {
      //                         $query->where('shift_id', $shift_id);
      //                     })
      //                   ->get();
      $class_list_data = Class_has_shift::where('shift_id',$shift_id)
      ->where('is_active','1')
      ->where('school_id',$school_info->id)
      ->with('getClass')
      ->get();   
      // dd($class_list_data);               
      return Response::json($class_list_data);
    }

    public function getShiftTeacherList(Request $request){
      $school_info = $this->schoolCheck($request)['school_info'];
      $data_id = $request->input('data_id');
      $class_list_data = Teacher::where('school_id',$school_info->id)->whereHas('getShiftTeacherList', function (Builder $query) use ($data_id) {
                              $query->where('shift_id', $data_id);
                          })
                        ->get();
                        // dd($class_list_data);
      return Response::json($class_list_data);
    }

    public function getTeacherList(Request $request){
      $school_info = $this->schoolCheck($request)['school_info'];
      $data_id = $request->input('data_id');
      $class_list_data = Shift::where('school_id',$school_info->id)->whereHas('getShiftTeacherList', function (Builder $query) use ($data_id) {
                              $query->where('shift_id', $data_id);
                          })
                        ->get();
      return Response::json($class_list_data);
    }

    public function getSectionList(Request $request){
      $school_info = $this->schoolCheck($request)['school_info'];
      $class_id = $request->input('class_id');
      $shift_id = $request->input('shift_id');
      // $section_list_data = Section::where('school_id',$school_info->id)->whereHas('getSectionList', function (Builder $query) use ($data_id,$shift_id) {
      //                         $query->where('class_id', $data_id)->where('shift_id', $shift_id);
      //                     })
      //                   ->get();
      $section_list_data = Class_has_section::where('shift_id',$shift_id)
      ->where('class_id',$class_id)
      ->where('is_active','1')
      ->where('school_id',$school_info->id)
      ->with('getSection')
      ->get();                  
                        // dd($section_list_data);
      return Response::json($section_list_data);
    }

    public function getSectionSubjectList(Request $request)
    {
      $school_info = $this->schoolCheck($request)['school_info'];
      $data_id = $request->input('data_id');
      $shift_id = $request->input('shift_id');
      $section_list_data = Section::where('school_id',$school_info->id)->whereHas('getSectionList', function (Builder $query) use ($data_id,$shift_id) {
                              $query->where('class_id', $data_id)->where('shift_id', $shift_id);
                          })
                        ->get();
      $subject_list_data = Subject::where('class_id', $data_id)->get(); 
      return Response::json(array(
        'section' => $section_list_data,
        'subject' => $subject_list_data
      ));
    }
    public function getSectionBookList(Request $request)
    {
      $school_info = $this->schoolCheck($request)['school_info'];
      $data_id = $request->input('data_id');
      $shift_id = $request->input('shift_id');
      $section_list_data = Section::where('school_id',$school_info->id)->whereHas('getSectionList', function (Builder $query) use ($data_id,$shift_id) {
                              $query->where('class_id', $data_id)->where('shift_id', $shift_id);
                          })
                        ->get();
      $book_list_data = Book::where('class_id', $data_id)->get(); 
      return Response::json(array(
        'section' => $section_list_data,
        'book' => $book_list_data,
      ));
    }

    public function getTeacherClassSalaryList(Request $request){
      $school_info = $this->schoolCheck($request)['school_info'];
      $data_id = $request->input('data_id');
      $class_list_data = Teacher::whereHas('getTeacherClassSalaryList', function (Builder $query) use ($data_id) {
        $query->where('class_id', $data_id);
      })
      ->get();
      return Response::json($class_list_data);
    }

    public function getStudentNameList(Request $request)
    { 
      $school_info = $this->schoolCheck($request)['school_info'];
      $data_id = $request->input('data_id');
      $shift_id = $request->input('shift_id');
      $class_id = $request->input('class_id');
      $student_list_data = Student::where('section_id', $data_id)
                          ->where('shift_id', $shift_id)
                          ->where('class_id', $class_id)
                          ->get();
      return Response::json($student_list_data);
    }

    public function getAllStudent(Request $request)
    {
      $school_info = $this->schoolCheck($request)['school_info'];
      $columns = array(
                  0 =>'id', 
                  1 =>'first_name',
                  2 =>'info',
                  3 =>'phone_no',
                  4 =>'created_by',
                  5 =>'status',
                  6 =>'action',
                );
      $totalData = Student::where('school_id', $school_info->id)->where('is_active','1')->orderBy('id','desc')->count(); //school chutauna
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
        $posts = Student::offset($start);
        if(!empty($request->data['filter_shift'])){
          $posts = $posts->where('shift_id',$filter_shift);
        }
        if(!empty($request->data['filter_class'])){
          $posts = $posts->where('class_id',$filter_class);
        }
        if(!empty($request->data['filter_section'])){
          $posts = $posts->where('section_id',$filter_section);
        }
      }
      else {
        $search = $request->input('search.value'); 
        // $posts = Student::offset($start)
        //                 ->where('first_name', 'LIKE',"%{$search}%")
        //                 ->orWhere('middle_name', 'LIKE',"%{$search}%")
        //                 ->orWhere('last_name', 'LIKE',"%{$search}%")
        //                 ->orWhere('phone_no', 'LIKE',"%{$search}%")
        //                 ->orWhere('student_code', 'LIKE',"%{$search}%");
        $posts = Student::offset($start)
                       ->whereHas('getStudentUser', function($query) use ($search){
                          $query->where('name', 'LIKE', '%'.$search.'%')
                       ->orWhere('middle_name','LIKE', '%'.$search.'%')
                       ->orWhere('last_name','LIKE', '%'.$search.'%');
                       })
                       ->orWhere('phone_no', 'LIKE',"%{$search}%")
                       ->orWhere('student_code', 'LIKE',"%{$search}%");
                                       
        if(!empty($request->data['filter_shift'])){
          $posts = $posts->where('shift_id',$filter_shift);
        }
        if(!empty($request->data['filter_class'])){
          $posts = $posts->where('class_id',$filter_class);
        }
        if(!empty($request->data['filter_section'])){
          $posts = $posts->where('section_id',$filter_section);
        }
        $totalFiltered = Student::where('school_id',$school_info->id)->where('is_active','1')
                                // ->where('first_name', 'LIKE',"%{$search}%")
                                // ->orWhere('middle_name', 'LIKE',"%{$search}%")
                                // ->orWhere('last_name', 'LIKE',"%{$search}%")
                                // ->orWhere('phone_no', 'LIKE',"%{$search}%")
                                ->orWhere('student_code', 'LIKE',"%{$search}%")
                                ->count();
      }
      $posts = $posts->where('school_id', $school_info->id)->limit($limit) //school chutauna
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
          $nestedData['first_name'] = $post->getStudentUser->name." ".$post->getStudentUser->middle_name." ".$post->getStudentUser->last_name."($post->student_code)";
          $nestedData['info'] = $post->getShift->name." | ".$post->getClass->name." | ".$post->getSection->name;
          $nestedData['phone_no'] = $post->phone_no;
          $nestedData['created_by'] = "<div class='text-center'>".$post->getUser->name."</div>";
          $nestedData['status'] = "
            <div class='text-center' title='".$attribute_title."'>
              <i class='fa ".$class_icon."'></i>
            </div>
          ";
          $nestedData['action'] = "
          <div class='text-center'>
            <a href='".route('main.student.detail.print',[$school_info->slug,$post->id])."' class='btn btn-xs btn-outline-info' target='_blank' data-toggle='tooltip' data-placement='top' title='Print Student Detail'><i class='fas fa-print'></i></a>
            <a href='".route('main.student.show',[$school_info->slug,$post->slug])."' class='btn btn-xs btn-outline-success' data-toggle='tooltip' data-placement='top' title='View Detail' target='_blank'><i class='fa fa-eye'></i></a>
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
    }

    public function show(Request $request, $school_slug, $student_slug)
    {
      $settings = $this->schoolCheck($request)['setting'];
      $school_info = $this->schoolCheck($request)['school_info'];

      $student_id = Student::where('slug', $student_slug)->where('school_id',$school_info->id)->value('id');
      $students_info = Student::withCount('Student_has_parent')->find($student_id);
      // $student = Student::withCount('Student_has_parent')->get();
      // dd($student);
      $students = User::find($students_info->user_id);
      $page = $students_info->first_name.' '.substr($students_info->middle_name, 0, 1).' '.substr($students_info->last_name, 0, 1)." Detail";
      // dd($students_info,$students);
      return view('main.school.info.student.detail.show', compact(['settings','school_info','students_info','students','page']));
    }
}
