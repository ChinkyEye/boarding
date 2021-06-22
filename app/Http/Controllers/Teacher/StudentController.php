<?php

namespace App\Http\Controllers\Teacher;

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

    public function idcard(Request $request)
    {
      $idcardShift = $request->idcardShift;
      $idcardClass = $request->idcardClass;
      $idcardSection = $request->idcardSection;
      $students = Student::get();

      if(!empty($request->idcardShift)){
         $students = Student::where('shift_id',$idcardShift)->get();
      }
      if(!empty($request->idcardClass)){
         $students = Student::where('class_id',$idcardClass)->get();
      }
      if(!empty($request->idcardSection)){
         $students = Student::where('section_id',$idcardSection)->get();
      }
      return view('teacher.primaryentry.student.idcard',compact('students'));
    }

    public function print(Request $request)
    {
      // $id = $request->id;
      $id = $request->id;
      // dd($id);
      $students = Student::where('id',$id)->get();
      // $studentcount = Student::where('id',$id)->count();
      $studentcount = Studenthascount::where('student_id',$id)->count();
      return view('teacher.primaryentry.student.idcard',compact('students','studentcount','id'));
    }


    public function admit(Request $request)
    {
      $id = $request->id;
      $students = Student::where('id',$id)->get();
      $class_id = Student::where('id',$id)->value('class_id');
      $shift_id = Student::where('id',$id)->value('shift_id');
      $section_id = Student::where('id',$id)->value('section_id');
      $exam_id = ExamhasClass::where('shift_id',$shift_id)->where('class_id',$class_id)->where('section_id',$section_id)->value('exam_id');
      $exams = Exam::where('id',$exam_id)->value('name'); 
      return view('teacher.primaryentry.student.admitcard',compact('students','exams'));
    }


    public function index()
    {
      $shifts = Shift::get();
      return view('teacher.primaryentry.student.index',compact('shifts'));
    }

    public function getClassList(Request $request){
      // dd($request);
      $data_id = $request->input('data_id');
      // dd($data_id);
      $class_list_data = SClass::whereHas('getRoutineClassList', function (Builder $query) use ($data_id) {    //function change
                              $query->where('shift_id', $data_id)->where('user_id', Auth::user()->id);
                          })->orwhereHas('getPeriodClassList', function (Builder $query) use ($data_id) {    //function change
                              $query->where('shift_id', $data_id)->where('teacher_id', Auth::user()->id);
                          })
                        ->get();
      return Response::json($class_list_data);
    }

    public function getShiftTeacherList(Request $request){
      $data_id = $request->input('data_id');
      $class_list_data = Teacher::whereHas('getShiftTeacherList', function (Builder $query) use ($data_id) {
                              $query->where('shift_id', $data_id);
                          })
                        ->get();
      return Response::json($class_list_data);
    }

    public function getTeacherList(Request $request){
      $data_id = $request->input('data_id');
      $class_list_data = Shift::whereHas('getShiftTeacherList', function (Builder $query) use ($data_id) {
                              $query->where('shift_id', $data_id);
                          })
                        ->get();
      return Response::json($class_list_data);
    }

    public function getSectionList(Request $request){
      $data_id = $request->input('data_id');
      $shift_id = $request->input('shift_id');
      // dd($data_id,$shift_id);
      $section_list_data = Section::whereHas('getRoutineSectionList', function (Builder $query) use ($data_id,$shift_id) {          //function change  
                              $query->where('class_id', $data_id)->where('shift_id', $shift_id)->where('user_id', Auth::user()->id);
                          })
                        ->get();
                        // dd($section_list_data);
      return Response::json($section_list_data);
    }

    public function getSectionSubjectList(Request $request)
    {
      $data_id = $request->input('data_id');
      $shift_id = $request->input('shift_id');
      $section_list_data = Section::whereHas('getSectionList', function (Builder $query) use ($data_id,$shift_id) {
                              $query->where('class_id', $data_id)->where('shift_id', $shift_id);
                          })
                        ->get();
      // $subject_list_data = Subject::where('class_id', $data_id)->get(); 
      return Response::json(array(
        'section' => $section_list_data,
        // 'subject' => $subject_list_data
      ));
    }
    public function getSectionBookList(Request $request)
    {
      $data_id = $request->input('data_id');
      $shift_id = $request->input('shift_id');
      $section_list_data = Section::whereHas('getSectionList', function (Builder $query) use ($data_id,$shift_id) {
                              $query->where('class_id', $data_id)->where('shift_id', $shift_id);
                          })
                        ->get();
      $book_list_data = Book::where('class_id', $data_id)->get(); 
      return Response::json(array(
        'section' => $section_list_data,
        'book' => $book_list_data,
      ));
    }

    public function getStudentNameList(Request $request)
    { 
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
      $columns = array(
                  0 =>'id', 
                  1 =>'first_name',
                  2 =>'student_code',
                  3 =>'email',
                  4 =>'created_by',
                  5 =>'status',
                  6 =>'action',
                );
      $totalData = Student::where('is_active','1')->orderBy('id','desc')->count();
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
        $posts = Student::offset($start)
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
        $totalFiltered = Student::where('is_active','1')
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
          $nestedData['first_name'] = $post->first_name." ".$post->middle_name." ".$post->last_name;
          $nestedData['student_code'] = $post->student_code;
          $nestedData['email'] = $post->email;
          $nestedData['created_by'] = "<div class='text-center'>".$post->getUser->name."</div>";
          $nestedData['status'] = "
            <a class='d-block text-center' href='".route('admin.student.active',$post->id)."' data-toggle='tooltip' data-placement='top' title='".$attribute_title."'>
              <i class='fa ".$class_icon."'></i>
            </a>
          ";
          $nestedData['action'] = "
          <div class='text-center'>
            <a href='".route('admin.student.print',$post->id)."' class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top'title='Print Id Card'><i class='fas fa-print'></i></a>
            <a href='".route('admin.student.admit',$post->id)."' class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top'title='Print Admit Card'><i class='fas fa-print'></i></a>
            <a href='".route('admin.student.show',$post->id)."' class='btn btn-xs btn-outline-success' data-toggle='tooltip' data-placement='top' title='View Detail'><i class='fa fa-eye'></i></a>
            <a href='".route('admin.student.edit',$post->id)."' class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top'title='Update'><i class='fas fa-edit'></i></a> 
          
            <form action='".route('admin.student.destroy',$post->id)."' method='post' class='d-inline-block' data-toggle='tooltip' data-placement='top' title='Permanent Delete'>
              <input type='hidden' name='_token' value='".csrf_token()."'>
              <input name='_method' type='hidden' value='DELETE'>
              <button class='btn btn-xs btn-outline-danger' type='submit'><i class='fa fa-trash'></i></button>
            </form>
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

    public function create()
    {
      $shifts = Shift::get();
      $classes = SClass::get();
      $sections = Section::get();
      $nationalities = Nationality::get();
      $batchs = Batch::get();
      return view('teacher.primaryentry.student.create',compact('shifts','classes','sections','nationalities','batchs'));
    }
    
    public function store(Request $request)
    {
      $this->validate($request, [
        'first_name' => 'required|min:2|alpha',
        'last_name' => 'required|min:2|alpha',
        'batch_id' => 'required',
        'roll_no' => 'required',
        'class_id' => 'required',
        'section_id' => 'required',
        'shift_id' => 'required',
        'email' => 'required|email|max:255|unique:students',
        'gender' => 'required',
        'dob' => 'required',
        'address' => 'required',
        'register_id' => 'required',
        'register_date' => 'required',
        'document_name' => 'required',
        'doc_file' => 'required',
        'image' => 'required',
      ]);
      $request['slug'] = $this->helper->slug_converter($request->first_name).'-'.rand(1000,9999);
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
      $user= User::create([
        'name' => $request['first_name'].''.$request['middle_name'].''.$request['last_name'],
        'email' => $request['email'],
        'password' => Hash::make($request['first_name'].''.$request['roll_no']),
        'user_type' => '2',
        'is_active' => '1',
        'school_id' => Auth::user()->school_id,
'batch_id' => Auth::user()->batch_id,
        'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),

      ]);
      $student= Student::create([
        'user_id' => $user->id,
        'first_name' => $request['first_name'],
        'middle_name' => $request['middle_name'],
        'last_name' => $request['last_name'],
        'slug' => $request['slug'],
        'batch_id' => $request['batch_id'],
        'roll_no' => $request['roll_no'],
        'class_id' => $request['class_id'],
        'section_id' => $request['section_id'],
        'shift_id' => $request['shift_id'],
        'email' => $request['email'],
        'gender' => $request['gender'],
        'dob' => $request['dob'],
        'register_id' => $request['register_id'],
        'register_date' => $request['register_date'],
        'image' => $fileName,
        'student_code' => $request['first_name'].''.$request['roll_no'],
        'document_name' => $request['document_name'],
        'document_photo' => $fileNameDoc,
        'school_id' => Auth::user()->school_id,
'batch_id' => Auth::user()->batch_id,
        'created_by' => Auth::user()->id,
        'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
      ]);
      $parent = Student_has_parent::create([
        'student_id' => $student->id,
        'father_name' => $request['father_name'],
        'mother_name' => $request['mother_name'],
        'address' => $request['address'],
        'nationality_id' => $request['nationality_id'],
        'school_id' => Auth::user()->school_id,
'batch_id' => Auth::user()->batch_id,
        'created_by' => Auth::user()->id,
        'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
      ]);

      $pass = array(
        'message' => 'Data added successfully!',
        'alert-type' => 'success'
      );
      return redirect()->route('admin.student.index')->with($pass);
    }

    public function destroy(Student $student)
    {
      $data_id = $student->id;
      $data_parent_id = Student_has_parent::where('student_id',$data_id)->value('id');
      $delete_parent = Student_has_parent::find($data_parent_id);

      $destinationPath = 'images/student/'.$student->slug;
      $oldFilename = $destinationPath.$student->image;
      $destinationPath2 = 'images/student/'.$student->slug.'/document';
      $oldFilename2 = $destinationPath2.$student->document_photo;
      if($student && $delete_parent){
        $delete_parent->delete();
        $student->delete();
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
      return Response::json($notification);
    }

    public function edit($id)
    {
      $shifts = Shift::get();
      $classes = SClass::get();
      $sections = Section::get();
      $nationalities = Nationality::get();
      $batchs = Batch::get();
      $students = Student::where('id', $id)->get();
      return view('teacher.primaryentry.student.edit', compact('students','shifts','classes','sections','nationalities','batchs'));
    }

    public function update(Request $request, Student $student)
    {
      dd($student);
      $uppdf = $request->file('image');
      $doc_file = $request->file('doc_file');
      $all_data = $request->all();
      if($uppdf != ""){
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
      if($student->update($all_data)){
       $parent = Student_has_parent::find($student_id);
       // dd($parent);
       $parent->father_name = $request->input('father_name');
       $parent->mother_name = $request->input('mother_name');
       $parent->address = $request->input('address');
       $parent->created_by = Auth::user()->id;
       $parent->updated_by = Auth::user()->id;
       $parent->update();
      }      
      $pass = array(
        'message' => 'Data updated successfully!',
        'alert-type' => 'success'
      );
      return back()->withInput($pass);
      // return redirect()->route('admin.student.index')->with($pass);
    }

    public function show($id)
    {
      $students = Student::where('id', $id)->get();
      return view('teacher.primaryentry.student.show', compact('students'));
    }

    public function isactive(Request $request,$id)
    {
      $get_is_active = Student::where('id',$id)->value('is_active');
      $isactive = Student::find($id);
      if($get_is_active == 0){
        $isactive->is_active = 1;
        $notification = array(
          'message' => 'Data is published!',
          'alert-type' => 'success'
        );
      }
      else {
        $isactive->is_active = 0;
        $notification = array(
          'message' => 'Data could not be published!',
          'alert-type' => 'error'
        );
      }
      $isactive->update();
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
