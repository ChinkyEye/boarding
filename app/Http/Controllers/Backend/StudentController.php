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
use App\Student_has_attendance;
use App\StudentHasMark;
use App\StudentHasObservation;
use App\ObservationHasMark;
use App\IssueBook;
use App\Bill;
use App\BillTotal;
use App\Fee;
use App\UserHasBatch;
use Illuminate\Support\Facades\Hash;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{

  public function export(Request $request)
  {
    $excShift = $request->excShift;
    $excClass = $request->excClass;
    $excSection = $request->excSection;
    $date = $this->helper->date_np_con_parm(date('Y-m-d'));
    return Excel::download(new StudentsExport($excShift,$excClass,$excSection), $date.'Student.xlsx');
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
    $roll_no = $request->roll_no;
    $actual_roll_no = $request->batch_id.'-'.$request->shift_id.'-'.$request->class_id.'-'.$request->section_id.'-'.$request->roll_no.'-'.Auth::user()->school_id;
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

  public function idcard(Request $request)
  {
    $idcardShift = $request->idcardShift;
    $idcardClass = $request->idcardClass;
    $idcardSection = $request->idcardSection;
    $students = Student::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id);

    if(!empty($idcardShift)){
      $students = $students->where('shift_id',$idcardShift);
    }
    if(!empty($idcardClass)){
      $students = $students->where('class_id',$idcardClass);
    }
    if(!empty($idcardSection)){
      $students = $students->where('section_id',$idcardSection);
    }
    $students = $students->get();
    // dd($students);
    return view('backend.primaryentry.student.idcard',compact('students'));
  }

  public function getAllCertificate(Request $request){
    $idcardShift = $request->idcardShift;
    $idcardClass = $request->idcardClass;
    $idcardSection = $request->idcardSection;
    $idcardBatch = $request->idcardBatch;
    
    $students = UserHasBatch::whereHas('getStudentBatch', function(Builder $query){
      $query->where('school_id', Auth::user()->school_id)
            ->where('is_active','1')
            ->orderBy('id','desc');
    })->where('batch_id',$idcardBatch)
      ->get();

    $current_date = date('Y-m-d');
    return view('backend.primaryentry.student.allcertificate',compact('students','current_date'));

  }

  public function detailPrint(Request $request)
  {
    $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->get();
    $classes = SClass::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->get();
    $sections = Section::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->get();
    $nationalities = Nationality::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->get();
    $batchs = Batch::where('school_id', Auth::user()->school_id)->get();
    $students = Student::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $request->id)->get();
    return view('backend.primaryentry.student.detail', compact(['students','shifts','classes','sections','nationalities','batchs']));
  }

  public function print(Request $request)
  {
    $user_id = Student::where('school_id',Auth::user()->school_id)->where('id', $request->id)->value('user_id');
    $students = User::find($user_id)->getUserStudent()->get();
    // $studentcount = Student::where('id',$id)->count();
    $studentcount = Studenthascount::where('user_id', $user_id)->count();
    return view('backend.primaryentry.student.idcard',compact('students','studentcount','user_id','request'));
  }

  public function count(Request $request)
  {
    // dd($request->category);
    dd($request);
    $counts = Studenthascount::create([
      'user_id' => $request->user_id,
      'type_id' => 1,
      'print_count' => 1,
      'category' => $request->category,
      'school_id' => Auth::user()->school_id,
      'batch_id' => Auth::user()->batch_id,
      'created_by' => Auth::user()->id,
      'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
    ]);
    $pass = array(
      'message' => 'This is printed!',
      'alert-type' => 'success'
    );
    return back()->with($pass)->withInput();
  }

  public function admit(Request $request)
  {
    $user_id = Student::where('school_id',Auth::user()->school_id)->where('id', $request->id)->value('user_id');
    $students = User::find($user_id)->getUserStudent()->get();
    $student_info = Student::find($request->id);
    $exam_id = ExamhasClass::where('shift_id',$student_info->shift_id)->where('class_id',$student_info->class_id)->where('section_id',$student_info->section_id)->value('exam_id');
    $exams = Exam::where('school_id',Auth::user()->school_id)->where('id',$exam_id)->value('name'); 
    return view('backend.primaryentry.student.admitcard',compact('students','exams'));
  }

  public function resetPassword(Request $request)
  {
    // dd($request);
    $student = Student::find($request->id);
    $user_id = User::find($student->user_id);
    // dd($student,$user_id);
    $password = 'admin123';
    $user_id->password = Hash::make($password);
    $user_id->reset_time = $this->helper->date_np_con()." ".date("H:i:s");

    $user_id->save();
    return back();
  }

  public function certificate(Request $request){
    $data_id = $request->id;
    $current_date = date('Y-m-d');
    $students = Student::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->withCount('Student_has_parent')->find($data_id);
    // dd($students);
    return view('backend.primaryentry.student.certificate', compact('students','current_date'));
  }


  public function index(Request $request)
  {
    $batches = Batch::all();
    $common_batch =$this->batchCheck($request)['batch'];
    $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->get();
    return view('backend.primaryentry.student.index',compact('shifts','batches','common_batch'));
  }
// dont delete kapil
  public function getClassList(Request $request){
    $shift_id = $request->shift_id;
    $class_list = Class_has_shift::where('shift_id',$shift_id)
    ->where('is_active','1')
    ->where('school_id',Auth::user()->school_id)
    ->with('getClass')
    ->get();
    return Response::json($class_list);
  }

  public function getSectionList(Request $request){
    $class_id = $request->class_id;
    $shift_id = $request->shift_id;
    $section_list = Class_has_section::where('shift_id',$shift_id)
    ->where('class_id',$class_id)
    ->where('is_active','1')
    ->where('school_id',Auth::user()->school_id)
    ->with('getSection')
    ->get();
    return Response::json($section_list);
  }


// here
  public function getShiftTeacherList(Request $request){
    $data_id = $request->input('data_id');
    $class_list_data = Teacher::where('school_id',Auth::user()->school_id)->whereHas('getShiftTeacherList', function (Builder $query) use ($data_id) {
      $query->where('shift_id', $data_id);
    })
    ->get();
// dd($class_list_data);
    return Response::json($class_list_data);
  }

  public function getTeacherList(Request $request){
    $data_id = $request->input('data_id');
    $class_list_data = Shift::where('school_id',Auth::user()->school_id)->whereHas('getShiftTeacherList', function (Builder $query) use ($data_id) {
      $query->where('shift_id', $data_id);
    })
    ->get();
    return Response::json($class_list_data);
  }


  public function getTeacherClassSalaryList(Request $request){
    $data_id = $request->input('data_id');
    $class_list_data = Teacher::whereHas('getTeacherClassSalaryList', function (Builder $query) use ($data_id) {
      $query->where('class_id', $data_id);
    })
    ->get();
    return Response::json($class_list_data);
  }

  public function getAllStudent(Request $request)
  {
    $columns = array(
      0 =>'id', 
      1 =>'first_name',
      2 =>'info',
      3 =>'phone_no',
      4 =>'created_by',
      5 =>'status',
      6 =>'action',
    );
    $batch_data = $request->data['batch_data'];
    $totalData = UserHasBatch::whereHas('getStudentBatch', function(Builder $query){
      $query->where('school_id', Auth::user()->school_id)
      ->where('is_active','1')
      ->orderBy('id','desc');
    })->where('batch_id',$batch_data)
    ->count();

    $totalFiltered = $totalData; 
    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');
    $shift_data = $request->data['shift_data'];
    $class_data = $request->data['class_data'];
    $section_data = $request->data['section_data'];
    $batch_data = $request->data['batch_data'];
    
    if(empty($request->input('search.value')))
    {  
      $posts = UserHasBatch::whereHas('getStudentBatch', function(Builder $query){
        $query->where('batch_id', Auth::user()->batch_id)
        ->where('school_id', Auth::user()->school_id)
        ->where('is_active','1')
        ->orderBy('id','desc');
      })->where('batch_id', Auth::user()->batch_id)
      ->offset($start);

      if(!empty($request->data['batch_data'])){
        $posts = UserHasBatch::whereHas('getStudentBatch', function(Builder $query){
          $query->where('batch_id', Auth::user()->batch_id)
          ->where('school_id', Auth::user()->school_id)
          ->where('is_active','1')
          ->orderBy('id','desc');
        })->where('batch_id',$batch_data)
        ->offset($start);
      }
      if(!empty($request->data['shift_data'])){
        $posts = $posts->whereHas('getStudentBatch', function($query) use ($shift_data){
          $query->where('shift_id',$shift_data);
        });
      }
      if(!empty($request->data['class_data'])){
        $posts = $posts->whereHas('getStudentBatch', function($query) use ($class_data){
          $query->where('class_id',$class_data);
        });
      }
      if(!empty($request->data['section_data'])){
        $posts = $posts->whereHas('getStudentBatch', function($query) use ($section_data){
          $query->where('section_id',$section_data);
        });
      }
    }
    else {
      $search = $request->input('search.value'); 

      $posts = UserHasBatch::offset($start)
      ->whereHas('getStudentUserBatch', function($query) use ($search){
        $query->where('name', 'LIKE', '%'.$search.'%')
        ->orWhere('middle_name','LIKE', '%'.$search.'%')
        ->orWhere('last_name','LIKE', '%'.$search.'%');
      });

      if(!empty($request->data['shift_data'])){
        $posts = $posts->whereHas('getStudentBatch', function($query) use ($shift_data){
          $query->where('shift_id',$shift_data);
        });
      }
      if(!empty($request->data['class_data'])){
        $posts = $posts->whereHas('getStudentBatch', function($query) use ($class_data){
          $query->where('class_id',$class_data);
        });
      }
      if(!empty($request->data['section_data'])){
        $posts = $posts->whereHas('getStudentBatch', function($query) use ($section_data){
          $query->where('section_id',$section_data);
        });
      }
      if(!empty($request->data['batch_data'])){
        $posts = $posts->where('batch_id',$batch_data);
      }

      $totalFiltered = UserHasBatch::whereHas('getStudentBatch', function(Builder $query){
        $query->where('school_id', Auth::user()->school_id)->where('is_active','1')->orderBy('id','desc');
      })->where('batch_id',$batch_data)
      ->count();

    }
    $posts = $posts->limit($limit) //school chutauna
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
        $nestedData['first_name'] = $post->getStudentUserBatch->name." ".$post->getStudentUserBatch->middle_name." ".$post->getStudentUserBatch->last_name." "."(".$post->getStudentBatch->student_code.")";
        $nestedData['info'] = $post->getStudentBatch->getShift->name." | ".$post->getStudentBatch->getClass->name." | ".$post->getStudentBatch->getSection->name;;
        $nestedData['phone_no'] = $post->getStudentBatch->phone_no;
        $nestedData['created_by'] = "<div class='text-center'>".$post->getUser->name."</div>";
        $nestedData['status'] = "<a class='d-block text-center' href='".route('admin.student.active',$post->getStudentBatch->slug)."' data-toggle='tooltip' data-placement='top' title='".$attribute_title."'>
        <i class='fa ".$class_icon."'></i>
        </a>
        ";
        $nestedData['action'] = "
        <div class='text-center'>
        <a href='".route('admin.student.detail.print',$post->id)."' class='btn btn-xs btn-outline-info' target='_blank' data-toggle='tooltip' data-placement='top' title='Print Student Detail'><i class='fas fa-print'></i></a>
        <a href='".route('admin.student.print',$post->getStudentBatch->id)."' class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top' title='Print Id Card'><i class='fas fa-address-card'></i></a>
        <a href='".route('admin.student.show',$post->getStudentBatch->id)."' class='btn btn-xs btn-outline-success' data-toggle='tooltip' data-placement='top' title='View Detail'><i class='fa fa-eye'></i></a>
        <a href='".route('admin.student.edit',$post->getStudentBatch->id)."' class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top'title='Update'><i class='fas fa-edit'></i></a> 

        <form action='javascript:void(0)' data_url=".route('admin.student.destroy',$post->getStudentBatch->id)."' method='post' class='d-inline-block' data-toggle='tooltip' data-placement='top' title='Permanent Delete' onclick='myFunction(this)'>
        <input type='hidden' name='_token' value='".csrf_token()."'>
        <input name='_method' type='hidden' value='DELETE'>
        <button class='btn btn-xs btn-outline-danger' type='submit' ><i class='fa fa-trash'></i></button>
        </form>
        <a href='".route('admin.student.resetPassword',$post->getStudentBatch->id)."' class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top'title='Reset Password'><i class='fas fa-key'></i></a>
        <a href='".route('admin.student.certificate',$post->getStudentBatch->id)."' class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top'title='Certificate'><i class='fas fa-certificate'></i></a> 
        </div>
        ";
        $data[] = $nestedData;
      }
    }
    $json_data = array(
      "draw"            => intval($request->input('draw')),  
      "recordsTotal"    => intval($totalData),  
      "recordsFiltered" => intval($totalFiltered), 
      "data"            => $data,
    );
    echo json_encode($json_data); 
  }

  public function create(Request $request)
  {
    $shifts = Shift::where('school_id', Auth::user()->school_id)
    ->where('batch_id', Auth::user()->batch_id)
    ->where('is_active','1')
    ->get();
    $nationalities = Nationality::where('school_id', Auth::user()->school_id)
    ->where('batch_id', Auth::user()->batch_id)
    ->where('is_active','1')
    ->get();
    $batchs = Batch::get();
    $common_batch =$this->batchCheck($request)['batch'];
    return view('backend.primaryentry.student.create',compact('shifts','nationalities','batchs','common_batch'));
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'first_name' => 'required|min:2|alpha',
      'last_name' => 'required|min:2|alpha',
      'roll_no' => 'required',
      'class_id' => 'required',
      'section_id' => 'required',
      'shift_id' => 'required',
      // 'email' => 'required|email|max:255',
      'gender' => 'required',
      'dob' => 'required',
      'address' => 'required',
      'student_address' => 'required',
      // 'register_id' => 'required',
      'register_date' => 'required',
      'document_name' => 'required',
      'doc_file' => 'required|mimes:jpg|max:1024',
      'image' => 'required|mimes:jpeg,jpg|max:1024',
      'nationality_id' => 'required',
      'contact_no' => 'required',

    ]);
    $student_code_unique = strtotime(date(("Y-m-d H:i:s.u")));
    $roll_no = $request->roll_no;
    $actual_roll_no = $request->batch_id.'-'.$request->shift_id.'-'.$request->class_id.'-'.$request->section_id.'-'.$request['roll_no'].'-'.Auth::user()->school_id;
    // dd($shift,$class,$section,$batch,$actual_roll_no);
    $request['slug'] = $this->helper->slug_converter($request->first_name).'-'.Auth::user()->school_id.'-'.rand(00,99); // always needs to be unique
    $uppdf = $request->file('image');
    if($uppdf != ""){
      $destinationPath = 'images/student/'.$request->slug;
      $extension = $uppdf->getClientOriginalExtension();
      $fileName = md5(mt_rand()).'.'.$extension;
      $uppdf->move($destinationPath, $fileName);
      $file_path = $destinationPath.'/'.$fileName;

    }
    $doc_file = $request->file('doc_file');
    if($doc_file != ""){
      $destinationPathDoc = 'images/student/'.$request->slug.'/document/';
      $extensionDoc = $doc_file->getClientOriginalExtension();
      $fileNameDoc = md5(mt_rand()).'.'.$extensionDoc;
      $doc_file->move($destinationPathDoc, $fileNameDoc);
      $file_path = $destinationPathDoc.'/'.$fileNameDoc;
    }
    $user = User::create([
      'name' => $request['first_name'],
      'middle_name' => $request['middle_name'],
      'last_name' =>$request['last_name'],
      'email' => $student_code_unique,
      'phone_no' => $request['phone_no'],
      'password' => Hash::make($request['dob']),/*.$request['roll_no']*/
      'user_type' => '2',
      'is_active' => '1',
      'school_id' => Auth::user()->school_id,
      'batch_id' => Auth::user()->batch_id,
      'created_at_np' => date("Y-m-d")." ".date("H:i:s"),
    ]);
    var_dump($user); die();
    $student= Student::create([
      'user_id' => $user->id,
      'slug' => $request['slug'],
      'batch_id' => $request['batch_id'],
      'address' => $request['student_address'],
      'roll_no' => $request['roll_no'],
      'actual_roll_no' => $actual_roll_no,
      'class_id' => $request['class_id'],
      'section_id' => $request['section_id'],
      'shift_id' => $request['shift_id'],
      'gender' => $request['gender'],
      'dob' => $request['dob'],
      'phone_no' => $request['phone_no'],
      'register_id' => $student_code_unique,
      'register_date' => $request['register_date'],
      'image' => $fileName,
      'student_code' => strtotime(date(("Y-m-d H:i:s.u"))),
      // 'student_code' => strtolower($request['first_name'].'-'.rand(0000,9999)),
      'document_name' => $request['document_name'],
      'document_photo' => $fileNameDoc,
      'school_id' => Auth::user()->school_id,
      'batch_id' => Auth::user()->batch_id,
      'created_by' => Auth::user()->id,
      'created_at_np' => date("Y-m-d")." ".date("H:i:s"),
    ]);
    // dd($request);
    // var_dump($student);
    if($student->wasRecentlyCreated){
      $parent = Student_has_parent::create([
        'student_id' => $student->id,
        'user_id' => $user->id,
        'father_name' => $request['father_name'],
        'mother_name' => $request['mother_name'],
        'address' => $request['address'],
        'nationality_id' => $request['nationality_id'],
        'contact_no' => $request['contact_no'],
        'school_id' => Auth::user()->school_id,
        'batch_id' => Auth::user()->batch_id,
        'created_by' => Auth::user()->id,
        'created_at_np' => date("Y-m-d")." ".date("H:i:s"),
      ]);
    }
    else{
      $delete_user = User::where('id', $user->id)->delete();
    }

    $user_has_batches = UserHasBatch::create([
      'user_id' => $user->id,
      'batch_id' => $student->batch_id,
      'created_by' => Auth::user()->id,
      'created_at_np' => date("Y-m-d")." ".date("H:i:s"),

    ]);

    $pass = array(
      'message' => 'Data added successfully!',
      'alert-type' => 'success'
    );
    return redirect()->route('admin.student.index')->with($pass);
  }

  public function destroy(Student $student)
  {
    // DB::beginTransaction();
    try{
      return DB::transaction(function() use ($student)
      {
        $data_id = $student->id;
        $data_parent_id = Student_has_parent::where('student_id',$data_id)->value('id');
        $delete_parent = Student_has_parent::find($data_parent_id);

        $user = Student::find($data_id);
        $delete_user = User::find($user->user_id);

        $issuebook_id = IssueBook::where('student_id', $data_id)->value('id');
        $delete_issuebook = IssueBook::find($issuebook_id);

        $student_attendance_id = Student_has_attendance::where('student_id',$data_id)->value('id');
        $delete_student_attendance = Student_has_attendance::find($student_attendance_id);

        $student_has_mark_id = StudentHasMark::where('student_id',$data_id)->value('id');
        $delete_student_has_mark = StudentHasMark::find($student_has_mark_id);
        
        $student_has_observation_id = StudentHasObservation::where('student_id',$delete_user->id)->value('id');
        $delete_student_has_observation = StudentHasObservation::find($student_has_observation_id);

        $observation_has_mark_id = ObservationHasMark::where('student_id',$delete_user->id)->value('id');
        $delete_observation_has_mark = ObservationHasMark::find($observation_has_mark_id);
        // dd($delete_observation_has_mark);

        $bill_id = Bill::where('student_id',$delete_user->id)->value('id');
        $delete_bill = Bill::find($bill_id);

        $billtotal_id = BillTotal::where('student_id',$delete_user->id)->pluck('id');
        $delete_billtotal = BillTotal::find($billtotal_id);
        // dd($delete_billtotal); die();

        $user_has_batch_id = UserHasBatch::where('user_id',$user->user_id)->value('id');
        $delete_user_has_batch = UserHasBatch::find($user_has_batch_id);

        $fee_id = Fee::where('student_id',$delete_user->id)->value('id');
        $delete_fee = Fee::find($fee_id);

        $destinationPath = 'images/student/'.$student->slug;
        $oldFilename = $destinationPath.$student->image;
        $destinationPath2 = 'images/student/'.$student->slug.'/document';
        $oldFilename2 = $destinationPath2.$student->document_photo;
        if($student && $delete_parent){
          if($delete_user_has_batch){
            $delete_user_has_batch->delete();
          }
          if($delete_bill){
            $delete_bill->delete();
          }
          // if($delete_billtotal){
          //   $delete_billtotal->delete();
          // }
          if($delete_billtotal){
            foreach ($delete_billtotal as $key => $value) {
              $value->delete();
            }
          }
          if($delete_fee){
            $delete_fee->delete();
          }
          if($delete_issuebook){
            $delete_issuebook->delete();
          }
          if($delete_observation_has_mark){
            $delete_observation_has_mark->delete();
          }
          if($delete_student_has_observation){
            $delete_student_has_observation->delete();
          }
          if($delete_student_has_mark){
            $delete_student_has_mark->delete();
          }
          if($delete_student_attendance && !empty($delete_student_attendance)){
            $delete_student_attendance->delete();
          }
          $delete_parent->delete();
          $student->delete();
          $delete_user->delete();
          if(File::exists($oldFilename)) {
            File::delete($oldFilename);
          }
          if(File::exists($oldFilename2)) {
            File::delete($oldFilename2);
          }
          $notification = array(
            'message' => 'Data deleted successfully!',
            'status' => 'success'
          );
        }else{
          $notification = array(
            'message' => 'Data could not be deleted!',
            'status' => 'error'
          );
        }
        return redirect()->route('admin.student.index')->with($notification);

      });
    } catch(\Exception $e){
      DB::rollback();
      throw $e;
      // dd($e);
      // abort(404);
    }
    DB::commit();

  }

// public function destroy(Student $student)
// {

//   DB::transaction(function() use ($student){
//   $data_id = $student->id;
//   $data_parent_id = Student_has_parent::where('student_id',$data_id)->value('id');
//   $delete_parent = Student_has_parent::find($data_parent_id);

//   $user = Student::find($data_id);
//       // dd($user);
//   $delete_user = User::find($user->user_id);
//   $student_attendance_id = Student_has_attendance::where('student_id',$data_id)->value('id');
//   $delete_student_attendance = Student_has_attendance::find($student_attendance_id);

//   $destinationPath = 'images/student/'.$student->slug;
//   $oldFilename = $destinationPath.$student->image;
//   $destinationPath2 = 'images/student/'.$student->slug.'/document';
//   $oldFilename2 = $destinationPath2.$student->document_photo;
//   if($student && $delete_parent){
//     if($delete_student_attendance && !empty($delete_student_attendance)){
//       $delete_student_attendance->delete();
//     }
//     $delete_parent->delete();
//     $students->delete();
//     $delete_user->delete();
//     if(File::exists($oldFilename)) {
//       File::delete($oldFilename);
//   }
//   if(File::exists($oldFilename2)) {
//       File::delete($oldFilename2);
//   }
//   $notification = array(
//       'message' => 'Data deleted successfully!',
//       'status' => 'success'
//   );
// }else{
//     $notification = array(
//       'message' => 'Data could not be deleted!',
//       'status' => 'error'
//   );
// }
// return redirect()->route('admin.student.index')->with($notification);
//       // return Response::json($notification);
// });
// }


  public function edit($id)
  {
    $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->get();
    $classes = SClass::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->get();
    $sections = Section::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->get();
    $nationalities = Nationality::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->get();
    $batchs = Batch::get();
    // dd($batchs);
     $students = Student::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->withCount('Student_has_parent')->where('id', $id)->get();
    $student_info = Student::find($id);
    // dd($student_info);
    return view('backend.primaryentry.student.edit', compact('students','shifts','classes','sections','nationalities','batchs','student_info'));
  }

  public function update(Request $request, Student $student)
  {
      $this->validate($request, [
        'first_name' => 'required|min:2|alpha',
        'last_name' => 'required|min:2|alpha',
        'batch_id' => 'required',
        'roll_no' => 'required',
        'class_id' => 'required',
        'section_id' => 'required',
        'shift_id' => 'required',
        'gender' => 'required',
        'dob' => 'required',
        'student_address' => 'required',
        'address' => 'required',
        // 'register_id' => 'required',
        'register_date' => 'required',
        'document_name' => 'required',
        // 'nationality_id' => 'required',
        // 'contact_no' => 'required',

    ]);
      // dd($request);
      $uppdf = $request->file('image');
      $doc_file = $request->file('doc_file');
      $all_data = $request->all();
      if($uppdf != ""){
        $this->validate($request, [
          'image' => 'required|mimes:jpeg,jpg|max:1024',
      ]);
        $destinationPath = 'images/student/'.$student->slug;
        $oldFilename = $destinationPath.$student->image;

        $extension = $uppdf->getClientOriginalExtension();
        $fileName = md5(mt_rand()).'.'.$extension;
        $uppdf->move($destinationPath, $fileName);
        $file_path = $destinationPath.'/'.$fileName;
        $all_data['image'] = $fileName;
        if(File::exists($oldFilename)) {
          File::delete($oldFilename);
      }
  }
  if($doc_file != ""){
    $this->validate($request, [
      'doc_file' => 'required|mimes:jpeg,jpg|max:1024',
  ]);
    $destinationPath2 = 'images/student/'.$student->slug.'/document';
    $oldFilename2 = $destinationPath2.$student->document_photo;

    $extension2 = $doc_file->getClientOriginalExtension();
    $fileName2 = md5(mt_rand()).'.'.$extension2;
    $doc_file->move($destinationPath2, $fileName2);
    $file_path2 = $destinationPath2.'/'.$fileName2;
    $all_data['document_photo'] = $fileName2;
    if(File::exists($oldFilename2)) {
      File::delete($oldFilename2);
  }
  }
  $all_data['updated_by'] = Auth::user()->id;
  $student_id = Student_has_parent::where('student_id',$student->id)->value('id');
  $user_id = Student::where('id',$student->id)->value('user_id');
  if($student->update($all_data)){
    $user_update= User::find($user_id);
    $user_update->name = $request['first_name'];
    $user_update->middle_name = $request['middle_name'];
    $user_update->last_name = $request['last_name'];
    $user_update->update();

    $parent = Student_has_parent::find($student_id);
    if ( $parent != null ) {
      $parent->father_name = $request->input('father_name');
      $parent->mother_name = $request->input('mother_name');
      $parent->address = $request->input('address');
      $parent->contact_no = $request->input('contact_no');
      $parent->nationality_id = $request->input('nationality_id');
      $parent->updated_by = Auth::user()->id;
      $parent->update();
    }

  }     

  $pass = array(
      'message' => 'Data updated successfully!',
      'alert-type' => 'success'
  );
        // return back()->withInput($pass);
  return redirect()->route('admin.student.index')->with($pass);
  }

  public function show($id)
  {
    $student_id = Student::where('id', $id)->value('user_id');
    $students = User::find($student_id)->getUserStudent()->withCount('Student_has_parent')->get();
    return view('backend.primaryentry.student.show', compact('students'));
  }

  public function isactive(Request $request,$id)
  {
    $student = Student::where('slug',$id)->value('id');
    $isactive = Student::find($student);
        // dd($isactive);
    $userisactive = User::find($isactive->user_id);
    $get_is_active = Student::where('slug',$id)->value('is_active');
        // dd($get_is_active,$userisactive);
    if($get_is_active == 0){
      $isactive->is_active = 1;
      $userisactive->is_active = 1;
      $notification = array(
        'message' => $isactive->first_name.$isactive->middle_name.$isactive->last_name.' is Active!',
        'alert-type' => 'success'
    );
  }
  else {
      $isactive->is_active = 0;
      $userisactive->is_active = 0;
      $notification = array(
        'message' => $isactive->first_name.$isactive->middle_name.$isactive->last_name.' is inactive!',
        'alert-type' => 'error'
    );
  }
  $userisactive->update();
  if(!($isactive->update())){
      $notification = array(
        'message' => $isactive->f_name.$isactive->l_name.$isactive->m_name.' could not be changed!',
        'alert-type' => 'error'
    );
  }
  return back()->with($notification)->withInput();
  }

  public function isSort(Request $request,$id)
  {
    dd($request);
    $sort_ids =  Student::find($request->id);
    $sort_ids->sort_id = $request->value;
    if($sort_ids->save()){
      $response = array(
        'status' => 'success',
        'msg' => 'Successfully change',
    );
  }else{
      $response = array(
        'status' => 'failure',
        'msg' => 'Sorry the data could not be change',
    );
  }
  return Response::json($response);
  }

}
