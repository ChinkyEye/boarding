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
use App\Shift;
use App\SClass;
use App\Section;
use App\Subject;
use App\Examhasclass;
use App\Exam;
use App\StudentHasMark;
use App\StudentHasObservation;
use App\ObservationHasMark;
use App\Studenthascount;
use App\MarkClass;
use App\Grade;
use App\Observation;
use App\User;

class LedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function countMark(Request $request)
    {
      $user_id = $request->user_id;
      $category = $request->category;
      $counts = Studenthascount::create([
        'user_id' => $user_id,
        'type_id' => 2,
        'category' => $category,
        'print_count' => 1,
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

    public function getMarkPrint($slug,$exam)
    {
      $student_id = Student::where('school_id',Auth::user()->school_id)->where('slug',$slug)->value('id'); 
      $user_id = Student::where('school_id',Auth::user()->school_id)->where('slug',$slug)->value('user_id'); 
      $class_id = Student::where('school_id',Auth::user()->school_id)->where('id',$student_id)->value('class_id');
      $shift_id = Student::where('school_id',Auth::user()->school_id)->where('id',$student_id)->value('shift_id');
      $section_id = Student::where('school_id',Auth::user()->school_id)->where('id',$student_id)->value('section_id');
      $exam_id = Exam::where('school_id',Auth::user()->school_id)->where('slug',$exam)->value('id');
      $exams_name = Exam::find($exam_id);
      $shifts_name = Shift::find($shift_id);
      $classes_name = SClass::find($class_id);
      $sections_name = Section::find($section_id);

      // dd($exams_name);
      $classexam_id = Examhasclass::where('school_id',Auth::user()->school_id)->where('exam_id',$exam_id)->where('class_id',$class_id)->value('id'); 
      $subjects = StudentHasMark::where('school_id',Auth::user()->school_id)->where('classexam_id',$classexam_id)->whereHas('getClassExam', function (Builder $query) use ($exam_id) {
          $query->where('exam_id', $exam_id);
      })->where('user_id', $user_id);
      $subjects = $subjects->get();
      return view('teacher.examsection.ledger.getmarkprint',compact('subjects','slug','exam','user_id','exams_name','shifts_name','classes_name','sections_name'));
    }

    public function getGradePrint($slug,$exam)
    {
      $student_id = Student::where('school_id',Auth::user()->school_id)->where('slug',$slug)->value('id'); 
      $user_id = Student::where('school_id',Auth::user()->school_id)->where('slug',$slug)->value('user_id'); 
      $class_id = Student::where('school_id',Auth::user()->school_id)->where('id',$student_id)->value('class_id');
      $shift_id = Student::where('school_id',Auth::user()->school_id)->where('id',$student_id)->value('shift_id');
      $section_id = Student::where('school_id',Auth::user()->school_id)->where('id',$student_id)->value('section_id');
      $exam_id = Exam::where('school_id',Auth::user()->school_id)->where('slug',$exam)->value('id');
      $exams_name = Exam::find($exam_id);
      $shifts_name = Shift::find($shift_id);
      $classes_name = SClass::find($class_id);
      $sections_name = Section::find($section_id);

      // dd($exams_name);
      $classexam_id = Examhasclass::where('school_id',Auth::user()->school_id)->where('exam_id',$exam_id)->where('class_id',$class_id)->value('id'); 
      $subjects = StudentHasMark::where('school_id',Auth::user()->school_id)->where('classexam_id',$classexam_id)->whereHas('getClassExam', function (Builder $query) use ($exam_id) {
          $query->where('exam_id', $exam_id);
      })->where('user_id', $user_id);
      $subjects = $subjects->get();
      return view('teacher.examsection.ledger.getgradeprint',compact('subjects','slug','exam','user_id','exams_name','shifts_name','classes_name','sections_name'));
    }

    public function getBothPrint($slug,$exam)
    {
      $student_id = Student::where('school_id',Auth::user()->school_id)->where('slug',$slug)->value('id'); 
      $user_id = Student::where('school_id',Auth::user()->school_id)->where('slug',$slug)->value('user_id'); 
      $class_id = Student::where('school_id',Auth::user()->school_id)->where('id',$student_id)->value('class_id');
      $shift_id = Student::where('school_id',Auth::user()->school_id)->where('id',$student_id)->value('shift_id');
      $section_id = Student::where('school_id',Auth::user()->school_id)->where('id',$student_id)->value('section_id');
      $exam_id = Exam::where('school_id',Auth::user()->school_id)->where('slug',$exam)->value('id');
      $exams_name = Exam::find($exam_id);
      $shifts_name = Shift::find($shift_id);
      $classes_name = SClass::find($class_id);
      $sections_name = Section::find($section_id);

      // dd($exams_name);
      $classexam_id = Examhasclass::where('school_id',Auth::user()->school_id)->where('exam_id',$exam_id)->where('class_id',$class_id)->value('id'); 
      $subjects = StudentHasMark::where('school_id',Auth::user()->school_id)->where('classexam_id',$classexam_id)->whereHas('getClassExam', function (Builder $query) use ($exam_id) {
          $query->where('exam_id', $exam_id);
      })->where('user_id', $user_id);
      $subjects = $subjects->get();
      return view('teacher.examsection.ledger.getbothprint',compact('subjects','slug','exam','user_id','exams_name','shifts_name','classes_name','sections_name'));
    }
    
    public function index()
    {
        $shifts = Shift::where('school_id',Auth::user()->school_id)->get();
        $exams = Exam::where('school_id',Auth::user()->school_id)->get();
        return view('teacher.examsection.ledger.index',compact('shifts','exams'));
    }

    public function ledger()
    {
        $shifts = Shift::where('school_id',Auth::user()->school_id)->get();
        $exams = Exam::where('school_id',Auth::user()->school_id)->get();
        // dd($exams);
        return view('teacher.examsection.studenthasmark.ledger',compact('shifts','exams'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'exam_id' => 'required',
            'shift_id' => 'required',
            'class_id' => 'required',
        ]);

        $shifts = Shift::where('school_id',Auth::user()->school_id)->get();
        $exams = Exam::where('school_id',Auth::user()->school_id)->get();

        $shift_id = $request->shift_id;
        $class_id = $request->class_id;
        $exam_id = $request->exam_id;
        $section_id =$request->section_id;
        // dd($shift_id,$class_id,$exam_id,$section_id);
        $all_data = json_decode($request->parameters, true);
        $datas = Student::where('school_id',Auth::user()->school_id)->orderBy('id','ASC'); 
        // dd($datas);
        if($request->shift_id){
          $datas =  $datas->where('shift_id', $request->shift_id);
          // dd($datas);
        }
        if($request->class_id){
          $datas = $datas->where('class_id',$request->class_id);
          // dd($datas);
        }
        if($request->section_id){
          $datas = $datas->where('section_id',$request->section_id);
        }
        $datas = $datas->get();
        // dd($datas);
        
        $shifts_name = Shift::where('school_id',Auth::user()->school_id)->where('id',$shift_id)->value('name');
        $sections_name = Section::where('school_id',Auth::user()->school_id)->where('id',$section_id)->value('name');
        // dd($sections_name);
        $classes_name = SClass::where('school_id',Auth::user()->school_id)->where('id',$class_id)->value('name');
        $exams_name = Exam::where('school_id',Auth::user()->school_id)->where('id',$exam_id)->value('name');
        $exam = Exam::where('school_id',Auth::user()->school_id)->where('id',$exam_id)->value('slug');
        $exam_id = Exam::where('school_id',Auth::user()->school_id)->where('id',$exam_id)->value('id');
        $class_id_get = SClass::where('school_id',Auth::user()->school_id)->where('id',$class_id)->value('id');
        $subjects = Subject::where('school_id',Auth::user()->school_id)->where('id',$class_id)->get();
        // dd($subjects);
        $getstudent_id = Student::where('school_id',Auth::user()->school_id)->where('shift_id',$shift_id)->where('class_id',$class_id)->value('user_id');
        // dd($getstudent_id); 
        $classexam_id = Examhasclass::where('school_id',Auth::user()->school_id)->where('exam_id',$exam_id)->where('class_id',$class_id)->where('shift_id',$shift_id)->value('id');
        // dd($classexam_id); 
        $classmarks_group = Markclass::where('school_id',Auth::user()->school_id)
                                    ->where('classexam_id',$classexam_id)
                                    ->orderBy('subject_id','ASC')
                                    ->groupBy('subject_id')
                                    ->get(); 
        $classmarks = Markclass::where('school_id',Auth::user()->school_id)->where('classexam_id',$classexam_id)->orderBy('subject_id','ASC')->get(); 
        // dd($classmarks);
        $studentmarks = StudentHasMark::where('school_id',Auth::user()->school_id)->where('classexam_id',$classexam_id)->get(); 

        $check_data = StudentHasMark::where('classexam_id',$classexam_id)->whereHas('getClassExam', function (Builder $query) use ($class_id) {
                                $query->where('class_id', $class_id);
                            });
        return view('teacher.examsection.ledger.create', compact('shifts','exams','datas','shifts_name','sections_name','classes_name','exams_name','exam','subjects','classmarks','classmarks_group','studentmarks','classexam_id','exam_id','class_id','request'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $classexam_id = $request->classexam_id;
        $student_id = $request->student_id;

        $user_id = $request->user_id;
        $exam_id = $request->exam_id;
        $subject_id = $request->subject_id;
        $class_id = $request->class_id;
        $obtained_mark = $request->obtained_mark;

        $grades = Grade::where('school_id',Auth::user()->school_id)->where('is_active',true)->get();
        $calc_bill_id = strtotime(date(("Y-m-d H:i:s")));
        $data_value = '';
          foreach( $user_id AS $key=>$user ){
            $student_info = User::find($user);
            $check_data = StudentHasMark::where('classexam_id',$request->classexam_id)
                                  ->whereHas('getClassExam', function (Builder $query) use ($request, $user) {
                                    $query->where('school_id',Auth::user()->school_id)
                                          ->where('exam_id', $request->exam_id)
                                          ->where('user_id', $user);
                                  })
                                  ->where('school_id',Auth::user()->school_id)
                                  ->count();
            
              if($check_data == 0){
                foreach ($subject_id as $index => $value) {
                    if ($request->obtained_mark[$index][$key] == 'a' || $request->obtained_mark[$index][$key] == '-') {
                        $percentage = "";
                        $data_value = "";
                        $grade_point = "";
                    }else{
                        $percentage = (($request->obtained_mark[$index][$key])*100)/$request->full_mark[$index][$key];
                        foreach ($grades as $value_grade) {
                            if ($value_grade->max >= $percentage) {
                                if ($value_grade->min <= $percentage) {
                                    $data_value = $value_grade->name;
                                    $grade_point = $value_grade->grade_point;
                                }
                            }
                        }
                    }
                    $studenthasmark= StudentHasMark::create([
                                    'classexam_id' => $request->classexam_id,
                                    'student_id' => Student::where('user_id', $user)->value('id'),
                                    'user_id' => $user,

                                    'subject_id' => $value,
                                    'type_id' => $request->type_id[$index][$key],
                                    'classmark_id' => $request->classmark_id[$index][$key],
                                    'obtained_mark' => $request->obtained_mark[$index][$key],

                                    'percentage' => $percentage,
                                    'grade' => $data_value,
                                    'grade_point' => $grade_point,
                                    'invoicemark_id' => $calc_bill_id.$key,
                                    'school_id' => Auth::user()->school_id,
                                    'batch_id' => Auth::user()->batch_id,
                                    'created_by' => Auth::user()->id,
                                    'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
                                ]);
                }
                $studenthasobservationmark= StudentHasObservation::create([
                                            'student_id' => $user,
                                            'invoicemark_id' => $calc_bill_id.$key,
                                            'school_id' => Auth::user()->school_id,
                                            'batch_id' => Auth::user()->batch_id,
                                            'created_by' => Auth::user()->id,
                                            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
                                        ]);

                $response = array(
                    'value' => $user,
                    'data' => $grade_point,
                    'status' => 'success',
                    'msg' => $student_info->name.' marks is saved',
                );
            }
            else{
                foreach ($subject_id as $index => $value) {
                    if ($request->obtained_mark[$index][$key] == 'a' || $request->obtained_mark[$index][$key] == '-') {
                        // dd($request->obtained_mark[$index][$key]);
                        $percentage = Null;
                        $data_value = Null;
                        $grade_point = Null;
                    }else{
                        $percentage = (($request->obtained_mark[$index][$key])*100)/$request->full_mark[$index][$key];
                        foreach ($grades as $value_grade) {
                            if ($value_grade->max >= $percentage) {
                                if ($value_grade->min <= $percentage) {
                                    $data_value = $value_grade->name;
                                    $grade_point = $value_grade->grade_point;
                                }
                            }
                        }
                    }
                    $get_subject_id = StudentHasMark::where('school_id',Auth::user()->school_id)
                                                    ->where('user_id',$user)
                                                    ->where('type_id',$request->type_id[$index][$key])
                                                    ->where('subject_id',$value)
                                                    ->where('classmark_id',$request->classmark_id[$index][$key])
                                                    ->where('classexam_id',$request->classexam_id)
                                                    ->value('id'); 
                    $std_tchr_update= StudentHasMark::find($get_subject_id);
                    $std_tchr_update->obtained_mark =  $request->obtained_mark[$index][$key];
                    $std_tchr_update->percentage =  $percentage;
                    $std_tchr_update->grade =  $data_value;
                    $std_tchr_update->grade_point =  $grade_point;
                    $std_tchr_update->updated_by = Auth::user()->id;
                    $std_tchr_update->update();
                }
                $response = array(
                    'value' => $user,
                    'data' => $grade_point,
                    'status' => 'success',
                    'msg' => $student_info->name.' marks is updated.',
                );
            }
        }

        return Response::json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
      $grades = Grade::where('school_id',Auth::user()->school_id)->where('is_active',true)->get();
      $this->validate($request, [
        'exam_id' => 'required',
        'shift_id' => 'required',
        'class_id' => 'required',
      ]);

      $shifts = Shift::where('school_id',Auth::user()->school_id)->get();
      $exams = Exam::where('school_id',Auth::user()->school_id)->get();

      $shift_id = $request->shift_id;
      $class_id = $request->class_id;
      $exam_id = $request->exam_id;
      $section_id =$request->section_id;
                // dd($request);

      $all_data = json_decode($request->parameters, true);
      $datas = Student::where('school_id',Auth::user()->school_id)->orderBy('id','ASC'); 
      if($request->shift_id){
        $datas =  $datas->where('shift_id', $request->shift_id);
                  // dd($datas);
      }
      if($request->class_id){
        $datas = $datas->where('class_id',$request->class_id);
      }
      if($request->section_id){
        $datas = $datas->where('section_id',$request->section_id);
      }
      $datas = $datas->get();

                // $shifts_name = Shift::where('school_id',Auth::user()->school_id)->where('id',$shift_id)->value('name');
      $shifts_name = Shift::where('school_id',Auth::user()->school_id)->find($shift_id);
      $classes_name = SClass::where('school_id',Auth::user()->school_id)->find($class_id);
      $sections_name = Section::where('school_id',Auth::user()->school_id)->find($section_id);
        // dd($sections_name);
      $exams_name = Exam::where('school_id',Auth::user()->school_id)->find($exam_id);
      $exam = Exam::where('school_id',Auth::user()->school_id)->find($exam_id);
                // dd($exam);
      $exam_id = Exam::where('school_id',Auth::user()->school_id)->where('id',$exam_id)->value('id');
      $class_id_get = SClass::where('school_id',Auth::user()->school_id)->where('id',$class_id)->value('id');
      $subjects = Subject::where('school_id',Auth::user()->school_id)->where('id',$class_id)->get();

      $getstudent_id = Student::where('school_id',Auth::user()->school_id)->where('shift_id',$shift_id)->where('class_id',$class_id)->value('user_id');

      $classexam_id = Examhasclass::where('school_id',Auth::user()->school_id)->where('exam_id',$exam_id)->where('class_id',$class_id)->where('shift_id',$shift_id)->value('id');

      $classmarks = Markclass::where('school_id',Auth::user()->school_id)->where('classexam_id',$classexam_id)->get(); 

      $studentmarks = StudentHasMark::where('school_id',Auth::user()->school_id)->where('classexam_id',$classexam_id)->get(); 

      $check_data = StudentHasMark::where('classexam_id',$classexam_id)->whereHas('getClassExam', function (Builder $query) use ($class_id) {
        $query->where('class_id', $class_id);
      });
      return view('teacher.examsection.studenthasmark.show', compact('shifts','exams','datas','shifts_name','classes_name','sections_name','exams_name','exam','subjects','classmarks','studentmarks','classexam_id','exam_id','class_id','grades','request'));
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
