<?php

namespace App\Http\Controllers\Backend;

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
use App\Shift;
use App\SClass;
use App\Section;
use App\Subject;
use App\Setting;
use App\Examhasclass;
use App\Exam;
use App\StudentHasMark;
use App\StudentHasObservation;
use App\ObservationHasMark;
use App\MarkClass;
use App\Grade;
use App\Observation;
use App\User;


class StudentHasMarkController extends Controller
{
    public function index()
    {
        $shifts = Shift::where('school_id',Auth::user()->school_id)->get();
        $exams = Exam::where('school_id',Auth::user()->school_id)->get();
        return view('backend.examsection.studenthasmark.index',compact('shifts','exams'));
    }

    public function isPublish(Request $request)
    {
      $mid = $request->mid;
      foreach ($mid as $key => $value) {
        $stupub = StudentHasObservation::find($value);
        $stupub->is_published = '1';
        $response = array(
          'status' => 'success',
          'msg' => 'Marks is Published',
        );

        $stupub->save();
      }
      return Response::json($response);
    }

    public function isunPublish(Request $request)
    {
      $mid = $request->mid;
      // dd($mid);
      foreach ($mid as $key => $value) {
        $stupub = StudentHasObservation::find($value);
        $stupub->is_published = '0';
        $response = array(
          'status' => 'failure',
          'msg' => 'Marks is not Published',
        );
        
        $stupub->save();
      }
      return Response::json($response);
    }

    public function isSinglePublish(Request $request,$invoiceid)
    {
      // dd($request);
        $observation = StudentHasObservation::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('invoicemark_id',$invoiceid)
                        ->value('id');
        $get_is_active = StudentHasObservation::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('invoicemark_id',$invoiceid)
                        ->value('is_published');
                        // dd('hh');

        $find_observation = StudentHasObservation::find($observation);
        if($get_is_active == 0){
        $find_observation->is_published = 1;
        $notification = array(
          'message' => 'Mark'.' is Published!',
          'alert-type' => 'success'
        );
        }
        else {
        $find_observation->is_published = 0;
        $notification = array(
          'message' => 'Mark'.' is not Published!',
          'alert-type' => 'error'
        );
        }
        $find_observation->save();
        return back()->with($notification);

    }



    public function getExamList(Request $request){
      $this->validate($request, [
          'exam_id' => 'required',
      ]);
      $exams = Exam::where('school_id',Auth::user()->school_id)->get();

      $shift_id = $request->shift_id;
      $class_id = $request->class_id;
      $exam_id = $request->exam_id;
      $section_id = $request->section_id;
// var_dump($exam_id); die();
      $datas = Student::where('school_id',Auth::user()->school_id)->orderBy('id','ASC'); 
      
      if($request->shift_id){
        $datas =  $datas->where('shift_id', $request->shift_id);
      }
      if($request->class_id){
        $datas = $datas->where('class_id',$request->class_id);
      }
      if($request->section_id){
        $datas = $datas->where('section_id',$request->section_id);
      }

      // $examhasclass_id = Examhasclass::where('exam_id',$exam_id)
      //               ->where('class_id',$class_id)
      //               ->where('shift_id',$shift_id)
      //               ->value('id');
      // var_dump($exam_id, $class_id, $shift_id, $examhasclass_id); die();
      // if($request->exam_id)
      //   $datas = $datas->whereHas('getObservationMark', function (Builder $query) use ($exam_id) {
      //     $query->where('exam_id', $exam_id);
      // });
      $datas = $datas->get();
      // dd($datas);
      
      $exam = Exam::where('school_id',Auth::user()->school_id)->where('id',$exam_id)->value('slug');
      return view('backend.examsection.studenthasmark.student_ajax', compact('datas','exam','exams','exam_id','request'));
    }


    public function main($slug,$exam)
    {
      $student_id = Student::where('slug',$slug)
                            ->where('school_id', Auth::user()->school_id)
                            ->where('batch_id', Auth::user()->batch_id) 
                            ->where('is_active', True)
                            ->value('id');
      $student_info = Student::with('getStudentUser')->find($student_id);
      $class_id = $student_info->class_id;
      $shift_id = $student_info->shift_id;
      $section_id = $student_info->section_id;

      // $exam_id = Exam::where('slug',$exam)->value('id');
      $exam_id = Exam::where('school_id',Auth::user()->school_id)->where('slug',$exam)->value('id');
      $exam_name = Exam::find($exam_id);
      $class_name = SClass::find($class_id);
      $shift_name = Shift::find($shift_id);
      $section_name = Section::find($section_id);

      // dd($exam_name,$class_name,$shift_name,$section_name);

      $classexam_id = Examhasclass::where('school_id',Auth::user()->school_id)->where('exam_id',$exam_id)->where('class_id',$class_id)->value('id'); 

      $subjects = MarkClass::where('school_id',Auth::user()->school_id)->where('classexam_id',$classexam_id);
      $check_data = StudentHasMark::where('school_id',Auth::user()->school_id)->where('classexam_id',$classexam_id)->whereHas('getClassExam', function (Builder $query) use ($exam_id,$student_id) {
        $query->where('exam_id', $exam_id)->where('student_id', $student_id);
      })
      ->count();
      if($check_data != 0){
        $subjects = StudentHasMark::where('school_id',Auth::user()->school_id)->where('classexam_id',$classexam_id)->whereHas('getClassExam', function (Builder $query) use ($exam_id,$student_id) {
          $query->where('exam_id', $exam_id)->where('student_id', $student_id);
        });
      }
      $subjects = $subjects->get();
      // dd($subjects);

      return view('backend.examsection.studenthasmark.create',compact('student_info','student_id','subjects','classexam_id','check_data','exam_id','exam_name','class_name','shift_name','section_name','class_id'));
    }

   

    public function marksheet($slug,$exam)
    {
      $settings =Setting::where('id', Auth::user()->school_id)->get();
      // dd($settings);
      $student_id = Student::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('slug',$slug)->value('user_id'); 
      $db_student = User::find($student_id);

      $exam_id = Exam::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('slug', $exam)->value('id');
      $db_exam = Exam::find($exam_id);

      $classexam_id = Examhasclass::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                                ->where('exam_id',$exam_id)->where('class_id',$db_student->getUserStudent->class_id)->value('id');

      $student_mark_invoicemark_id = StudentHasMark::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('user_id',$student_id)->where('classexam_id',$classexam_id)->value('invoicemark_id');
      // dd($student_mark_invoicemark_id);

      $observations = ObservationHasMark::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('invoicemark_id',$student_mark_invoicemark_id)->get();

      $grades = Grade::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->get();
      $grades_asc = Grade::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->orderBy('grade_point','Asc')->get();


      // dd($observations);

      // $collection_subject = Subject::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->whereHas('getClassSubject', function (Builder $query) use ($student_id,$classexam_id) {
      //                             $query->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
      //                                   ->where('user_id',$student_id)
      //                                   ->where('classexam_id',$classexam_id);
      //                           })->get();
      // foreach ($collection_subject as $key => $value) {
      //   $collection_subject[$key]['classexam_id'] = $classexam_id;
      //   $collection_subject[$key]['student_id'] = $student_id;
      // }
      // $collection = collect($collection_subject);
      // $grouped_class_subject = $collection->groupBy('name');
      // $grouped_class_subject->toArray();

      // from student marks
      $grouped_class_subject = StudentHasMark::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->whereHas('getSubject', function (Builder $query) {
                                  $query->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id);
                                })
                                ->where('user_id',$student_id)
                                ->where('classexam_id',$classexam_id)
                                ->get();
                                $try = StudentHasMark::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->whereHas('getSubject', function (Builder $query) {
                                                            $query->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id);
                                                          })
                                                          ->where('user_id',$student_id)
                                                          ->where('classexam_id',$classexam_id)
                                                          ->get();
      // $result = $collection_mark->groupBy([
      //   'subject_id',
      //   function ($item) {
      //     return $item['name'];
      //   },
      // ], $preserveKeys = true);
      // dd($collection_mark);

      // dd($grouped_class_subject);
      return view('backend.examsection.studenthasmark.marksheet',compact('db_student','db_exam','observations','grouped_class_subject','settings','grades','grades_asc'));
    }

    public function getStudentExamGrade(Request $request){
      $percentage = $request->percentage;
      $grades = Grade::where('school_id',Auth::user()->school_id)->where('is_active',true)->get();
      $data_value = '';
      foreach ($grades as $key => $value) {
        if ($value->max >= $percentage) {
          if ($value->min <= $percentage) {
             $data_value = $value->name;
             $grade_point = $value->grade_point;

          }
        }
      }
      // dump($data_value);
      if(!empty($data_value)){
        $response = array(
          'value' => $data_value,
          'data' => $grade_point,
          'status' => 'success',
          'msg' => $data_value.' grade is calculated.',
        );
      }else{
         $response = array(
          'value' => '-',
          'status' => 'failure',
          'msg' => 'Sorry, check the full marks',
        );
      }
      return Response::json($response);
    }


    public function getExamShiftList(Request $request){
      $data_id = $request->input('data_id');
      // dd($data_id,$request);
      $class_list_data = Shift::whereHas('getExamShiftList', function (Builder $query) use ($data_id) {
        $query->where('school_id',Auth::user()->school_id)->where('exam_id', $data_id);
      })
      ->where('school_id',Auth::user()->school_id)
      ->get();
      return Response::json($class_list_data);
    }

    public function getExamClassList(Request $request){
      $data_id = $request->input('data_id');
      $exam_id = $request->input('exam_id');
      // dd($request);
      // dd($exam_id,$data_id);
      $class_list_data = SClass::whereHas('getExamClass', function (Builder $query) use ($data_id,$exam_id) {
        $query->where('shift_id', $data_id)->where('exam_id', $exam_id)
              ->where('school_id',Auth::user()->school_id);
      })
      ->where('school_id',Auth::user()->school_id)
      ->get();
      // dd($class_list_data);
      return Response::json($class_list_data);
    }

    public function getExamSectionList(Request $request){
      $data_id = $request->input('data_id');
      $shift_id = $request->input('shift_id');
      $exam_id = $request->input('exam_id');
      $class_list_data = Section::whereHas('getExamSectionList', function (Builder $query) use ($data_id) {
        $query->where('class_id', $data_id)
              ->where('school_id',Auth::user()->school_id);
      })
      ->where('school_id',Auth::user()->school_id)
      ->get();
      return Response::json($class_list_data);
    }


    public function store(Request $request)
    {
        // dd($request);
        $classexam_id = $request->classexam_id;
        // dd($classexam_id);
        $student_id = $request->student_id;
        $exam_id = $request->exam_id;
        $user_id = Student::where('school_id',Auth::user()->school_id)->where('id',$student_id)->value('user_id');
        $slug = Student::where('school_id',Auth::user()->school_id)->where('id',$student_id)->value('slug');
        $exam = Exam::where('school_id',Auth::user()->school_id)->where('id',$exam_id)->value('slug');
        $calc_bill_id = strtotime(date(("Y-m-d H:i:s")));
        $check_data = StudentHasMark::where('classexam_id',$classexam_id)
        ->whereHas('getClassExam', function (Builder $query) use ($exam_id,$student_id) {
          $query->where('school_id',Auth::user()->school_id)
                ->where('exam_id', $exam_id)
                ->where('student_id', $student_id);
        })
        ->where('school_id',Auth::user()->school_id)
        ->count();
        $subject_id = $request->input('subject_id');
        if($check_data == 0){
          foreach( $subject_id AS $key=>$subject ){
            $studenthasmark= StudentHasMark::create([
                'classexam_id' => $request['classexam_id'],
                'student_id' => $request['student_id'],
                'user_id' => $user_id,
                'subject_id' => $subject,
                'type_id' => $request->type[$key],
                'classmark_id' => $request->classmark_id[$key],
                'obtained_mark' => $request->obtained_mark[$key],
                'percentage' => $request->percentage[$key],
                'grade' => $request->grade[$key],
                'grade_point' => $request->grade_point[$key],
                'invoicemark_id' => $calc_bill_id,
                'school_id' => Auth::user()->school_id,
                'batch_id' => Auth::user()->batch_id,
                'created_by' => Auth::user()->id,
                'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
            ]);
          }

          $studenthasobservationmark= StudentHasObservation::create([
              'student_id' => $user_id,
              'invoicemark_id' => $calc_bill_id,
              'exam_id' => $exam_id,
              'school_id' => Auth::user()->school_id,
              'batch_id' => Auth::user()->batch_id,
              'created_by' => Auth::user()->id,
              'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),

          ]);
         
          return redirect()->route('admin.observation.mark',['user_id' => $slug,'classexam_id' => $exam]);
        }
        else{
          $subject_id = $request->input('subject_id');
          foreach( $subject_id AS $key=>$subject ){
                $get_subject_id = StudentHasMark::where('school_id',Auth::user()->school_id)->where('classexam_id',$classexam_id)
                ->whereHas('getClassExam', function (Builder $query) use ($exam_id,$student_id,$subject) {
                                $query->where('exam_id',$exam_id);
                                $query->where('student_id',$student_id);
                                $query->where('subject_id',$subject);
                            })
                            ->value('id'); 
                $std_tchr_update= StudentHasMark::find($get_subject_id);
                $std_tchr_update->subject_id = $subject;
                $std_tchr_update->obtained_mark = $request->obtained_mark[$key];
                $std_tchr_update->percentage = $request->percentage[$key];
                $std_tchr_update->grade = $request->grade[$key];
                $std_tchr_update->grade_point = $request->grade_point[$key];
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

    public function getAllStudentMarkList(Request $request){
      $all_data = json_decode($request->parameters, true);
      $datas = Student::where('school_id',Auth::user()->school_id)->orderBy('id','ASC');
      if($all_data['shift_id']){
        $datas = $datas->where('shift_id',$all_data['shift_id']);
      }
      if($all_data['class_id']){
        $datas = $datas->where('class_id',$all_data['class_id']);
      }
      if($all_data['section_id']){
        $datas = $datas->where('section_id',$all_data['section_id']);
      }
      $datas = $datas->get();
      $shifts = Shift::where('school_id',Auth::user()->school_id)->where('id',$all_data['shift_id'])->value('name');
      return view('backend.attendance.teacher_has_attendance.ajax-attendance', compact('datas','dates','shifts'));
    }

    public function getAllStudentMark(Request $request)
    {
      $columns = array(
        0 =>'id', 
        1 =>'first_name',
        2 =>'student_code',
        3 =>'email',
        4 =>'created_by',
        5 =>'action',
      );
      $totalData = Student::where('school_id',Auth::user()->school_id)->where('is_active','1')->orderBy('id','desc')->count();
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
        $posts = Student::where('school_id',Auth::user()->school_id)->offset($start);
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
        $posts = Student::where('school_id',Auth::user()->school_id)->offset($start)
        ->where('first_name', 'LIKE',"%{$search}%")
        ->orWhere('middle_name', 'LIKE',"%{$search}%")
        ->orWhere('last_name', 'LIKE',"%{$search}%");
        if(!empty($request->data['filter_shift'])){
          $posts = $posts->where('shift_id',$filter_shift);
        }
        if(!empty($request->data['filter_class'])){
          $posts = $posts->where('class_id',$filter_class);
        }
        if(!empty($request->data['filter_section'])){
          $posts = $posts->where('section_id',$filter_section);
        }
        $totalFiltered = Student::where('school_id',Auth::user()->school_id)->where('is_active','1')
        ->where('first_name', 'LIKE',"%{$search}%")
        ->orWhere('middle_name', 'LIKE',"%{$search}%")
        ->orWhere('last_name', 'LIKE',"%{$search}%")
        ->count();
      }
      $posts = $posts->limit($limit)
      ->orderBy($order,$dir)
      ->get();
      $data = array();
      if(!empty($posts))
      {
        foreach ($posts as $index=>$post)
        {
          $nestedData['id'] = $post->student_code;
          $nestedData['first_name'] = $post->first_name." ".$post->middle_name." ".$post->last_name;
          $nestedData['student_code'] = $post->student_code;
          $nestedData['email'] = $post->email;
          $nestedData['created_by'] = "<div class='text-center'>".$post->getUser->name."</div>";
          $nestedData['action'] = "
          <div class='text-center'>
           <a  href='".route('admin.studenthasmark',$post->slug)."'  class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top' title='Add Class'><i class='fa fa-plus'></i></a>
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

    public  function check(Request $request)
    {
      if($request->get('$request->obtained_mark'))
      {
        // dd($request->obtained_mark);
       $obtained_mark = $request->get('obtained_mark');
        $data = MarkClass::where('school_id',Auth::user()->school_id)->where('full_mark','>=', $obtained_mark);
       if($data > 0)
       {
        echo 'not_unique';
       }
       else
       {
        echo 'unique';
       }
      }
    }

}
