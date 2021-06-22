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
use App\Shift;
use App\SClass;
use App\Section;
use App\Subject;
use App\Examhasclass;
use App\Exam;
use App\StudentHasMark;
use App\StudentHasObservation;
use App\ObservationHasMark;
use App\MarkClass;
use App\Grade;
use App\Observation;
use App\User;
use App\Setting;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
      $shifts = Shift::where('school_id',$school_info->id)->get();
      $exams = Exam::where('school_id',$school_info->id)->get();
      return view('main.school.info.student.examsection.ledger.index',compact('settings','school_info','shifts','exams'));
    }

    public function ledgerView(Request $request)
    {
      $settings = $this->schoolCheck($request)['setting'];
      $school_info = $this->schoolCheck($request)['school_info'];
      $shifts = Shift::where('school_id',$school_info->id)->get();
      $exams = Exam::where('school_id',$school_info->id)->get();
      return view('main.school.info.student.examsection.ledger.view',compact('settings','school_info','shifts','exams'));
    }

    public function show(Request $request)
    {
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        $grades = Grade::where('school_id',$school_info->id)->where('is_active',true)->get();
        $this->validate($request, [
            'exam_id' => 'required',
            'shift_id' => 'required',
            'class_id' => 'required',
        ]);

        $shifts = Shift::where('school_id',$school_info->id)->get();
        $exams = Exam::where('school_id',$school_info->id)->get();

        $shift_id = $request->shift_id;
        $class_id = $request->class_id;
        $exam_id = $request->exam_id;
        $section_id =$request->section_id;
        // dd($request);

        $all_data = json_decode($request->parameters, true);
        $datas = Student::where('school_id',$school_info->id)->orderBy('id','ASC'); 
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
        
        // $shifts_name = Shift::where('school_id',$school_info->id)->where('id',$shift_id)->value('name');
        $shifts_name = Shift::where('school_id',$school_info->id)->find($shift_id);
        $classes_name = SClass::where('school_id',$school_info->id)->find($class_id);
        $sections_name = Section::where('school_id',$school_info->id)->find($section_id);
        $exams_name = Exam::where('school_id',$school_info->id)->find($exam_id);
        $exam = Exam::where('school_id',$school_info->id)->find($exam_id);
        // dd($exam);
        $exam_id = Exam::where('school_id',$school_info->id)->where('id',$exam_id)->value('id');
        $class_id_get = SClass::where('school_id',$school_info->id)->where('id',$class_id)->value('id');
        $subjects = Subject::where('school_id',$school_info->id)->where('id',$class_id)->get();

        $getstudent_id = Student::where('school_id',$school_info->id)->where('shift_id',$shift_id)->where('class_id',$class_id)->value('user_id');

        $classexam_id = Examhasclass::where('school_id',$school_info->id)->where('exam_id',$exam_id)->where('class_id',$class_id)->where('shift_id',$shift_id)->value('id');

        $classmarks = Markclass::where('school_id',$school_info->id)->where('classexam_id',$classexam_id)->get(); 

        $studentmarks = StudentHasMark::where('school_id',$school_info->id)->where('classexam_id',$classexam_id)->get(); 

        $check_data = StudentHasMark::where('classexam_id',$classexam_id)->whereHas('getClassExam', function (Builder $query) use ($class_id) {
                                $query->where('class_id', $class_id);
                            });
        return view('main.school.info.student.examsection.ledger.show', compact('settings','school_info','shifts','exams','datas','shifts_name','classes_name','sections_name','exams_name','exam','subjects','classmarks','studentmarks','classexam_id','exam_id','class_id','grades','request'));
    }

    public function getMarkPrint(Request $request, $school_slug, $std_slug, $exam)
    {
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        // dd($school_info->id);

        $student_id = Student::where('school_id',$school_info->id)->where('slug',$std_slug)->value('id');
        $user_id = Student::where('school_id',$school_info->id)->where('slug',$std_slug)->value('user_id'); 
        $student_info = Student::find($student_id);

        $exam_id = Exam::where('school_id',$school_info->id)->where('slug',$exam)->value('id');
        $classexam_id = Examhasclass::where('school_id',$school_info->id)->where('exam_id',$exam_id)->where('class_id',$student_info->class_id)->value('id'); 
        $subjects = StudentHasMark::where('school_id',$school_info->id)->where('classexam_id',$classexam_id)
                    ->whereHas('getClassExam', function (Builder $query) use ($exam_id) {
                        $query->where('exam_id', $exam_id);
                    })->where('user_id', $user_id);

        $subjects = $subjects->get();
        $exam_info = Exam::find($exam_id);
        $page = $student_info->getStudentUser->name.' '.substr($student_info->getStudentUser->middle_name, 0, 1).' '.substr($student_info->getStudentUser->last_name, 0, 1).'('.$student_info->student_code.')';
        // dd($page);
        $page_print = $student_info->getStudentUser->name.' '.$student_info->getStudentUser->middle_name.' '.$student_info->getStudentUser->last_name;
        $detail = "(".$student_info->getShift->name."/".$student_info->getClass->name."/".$student_info->getSection->name.") <br><small class='h6 font-weight-bold'> ".$exam_info->name." (".$exam_info->start_date." to ".$exam_info->end_date.")</small>";
        return view('main.school.info.student.examsection.ledger.getmarkprint',compact('settings','school_info','subjects','std_slug','exam','user_id','page','detail','page_print'));
    }

    public function getGradePrint(Request $request, $school_slug, $std_slug, $exam)
    {
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];

        $student_id = Student::where('school_id',$school_info->id)->where('slug',$std_slug)->value('id'); 
        $user_id = Student::where('school_id',$school_info->id)->where('slug',$std_slug)->value('user_id'); 
        $student_info = Student::find($student_id);

        $class_id = Student::where('school_id',$school_info->id)->where('id',$student_id)->value('class_id');
        $shift_id = Student::where('school_id',$school_info->id)->where('id',$student_id)->value('shift_id');
        $exam_id = Exam::where('school_id',$school_info->id)->where('slug',$exam)->value('id');
        $classexam_id = Examhasclass::where('school_id',$school_info->id)->where('exam_id',$exam_id)->where('class_id',$class_id)->value('id'); 
        $subjects = StudentHasMark::where('school_id',$school_info->id)->where('classexam_id',$classexam_id)->whereHas('getClassExam', function (Builder $query) use ($exam_id) {
          $query->where('exam_id', $exam_id);
        })->where('user_id', $user_id);

        $subjects = $subjects->get();
        $exam_info = Exam::find($exam_id);
        $page = $student_info->getStudentUser->name.' '.substr($student_info->getStudentUser->middle_name, 0, 1).' '.substr($student_info->getStudentUser->last_name, 0, 1).'('.$student_info->student_code.')';
        $page_print = $student_info->getStudentUser->name.' '.$student_info->getStudentUser->middle_name.' '.$student_info->getStudentUser->last_name;
        $detail = "(".$student_info->getShift->name."/".$student_info->getClass->name."/".$student_info->getSection->name.") <br><small class='h6 font-weight-bold'> ".$exam_info->name." (".$exam_info->start_date." to ".$exam_info->end_date.")</small>";
        return view('main.school.info.student.examsection.ledger.getgradeprint',compact('settings','school_info','subjects','std_slug','exam','user_id','page','detail','page_print'));
    }

    public function getBothPrint(Request $request, $school_slug, $std_slug, $exam)
    {
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];

        $student_id = Student::where('school_id',$school_info->id)->where('slug',$std_slug)->value('id'); 
        $user_id = Student::where('school_id',$school_info->id)->where('slug',$std_slug)->value('user_id');
        $student_info = Student::find($student_id);

        $class_id = Student::where('school_id',$school_info->id)->where('id',$student_id)->value('class_id');
        $shift_id = Student::where('school_id',$school_info->id)->where('id',$student_id)->value('shift_id');
        $exam_id = Exam::where('school_id',$school_info->id)->where('slug',$exam)->value('id');
        $classexam_id = Examhasclass::where('school_id',$school_info->id)->where('exam_id',$exam_id)->where('class_id',$class_id)->value('id'); 
        $subjects = StudentHasMark::where('school_id',$school_info->id)->where('classexam_id',$classexam_id)
                    ->whereHas('getClassExam', function (Builder $query) use ($exam_id) {
                          $query->where('exam_id', $exam_id);
                      })->where('user_id', $user_id);

        $subjects = $subjects->get();
        
        $exam_info = Exam::find($exam_id);
        $page = $student_info->getStudentUser->name.' '.substr($student_info->getStudentUser->middle_name, 0, 1).' '.substr($student_info->getStudentUser->last_name, 0, 1).'('.$student_info->student_code.')';
        $page_print = $student_info->getStudentUser->name.' '.$student_info->getStudentUser->middle_name.' '.$student_info->getStudentUser->last_name;
        $detail = "(".$student_info->getShift->name."/".$student_info->getClass->name."/".$student_info->getSection->name.") <br><small class='h6 font-weight-bold'> ".$exam_info->name." (".$exam_info->start_date." to ".$exam_info->end_date.")</small>";
        return view('main.school.info.student.examsection.ledger.getbothprint',compact('settings','school_info','subjects','std_slug','exam','user_id','page','detail','page_print'));
    }

    public function getMarkSheetPrint(Request $request, $school_slug, $std_slug, $exam)
    {
      $settings = $this->schoolCheck($request)['setting'];
      $school_info = $this->schoolCheck($request)['school_info'];
      $settings_school = Setting::where('id', $school_info->id)->get();
      // dd($settings);
      $student_user_id = Student::where('school_id', $school_info->id)
                          ->where('slug',$std_slug)
                          ->value('user_id'); 
      $student_id = Student::where('school_id',$school_info->id)->where('slug',$std_slug)->value('id'); 
      $student_info = Student::find($student_id);
      $db_student = User::find($student_user_id);

      $exam_id = Exam::where('school_id', $school_info->id)
                    ->where('slug', $exam)
                    ->value('id');
      $exam_info = Exam::find($exam_id);

      $classexam_id = Examhasclass::where('school_id', $school_info->id)
                                ->where('exam_id',$exam_id)
                                ->where('class_id',$db_student->getSchoolUserStudent->class_id)
                                ->value('id');

      $student_mark_invoicemark_id = StudentHasMark::where('school_id', $school_info->id)->where('user_id',$student_user_id)->where('classexam_id',$classexam_id)->value('invoicemark_id');
      // dd($student_mark_invoicemark_id);

      $observations = ObservationHasMark::where('school_id', $school_info->id)->where('invoicemark_id',$student_mark_invoicemark_id)->get();

      $grades = Grade::where('school_id', $school_info->id)->get();
      $grades_asc = Grade::where('school_id', $school_info->id)->orderBy('grade_point','Asc')->get();

      // from student marks
      $grouped_class_subject = StudentHasMark::where('school_id', $school_info->id)->whereHas('getSubject', function (Builder $query) use ($school_info) {
                                  $query->where('school_id', $school_info->id);
                                })
                                ->where('user_id',$student_user_id)
                                ->where('classexam_id',$classexam_id)
                                ->get();
      $try = StudentHasMark::where('school_id', $school_info->id)->whereHas('getSubject', function (Builder $query) use ($school_info) {
                                  $query->where('school_id', $school_info->id);
                                })
                                ->where('user_id',$student_user_id)
                                ->where('classexam_id',$classexam_id)
                                ->get();
      
      $page = $student_info->getStudentUser->name.' '.substr($student_info->getStudentUser->middle_name, 0, 1).' '.substr($student_info->getStudentUser->last_name, 0, 1);
      $detail = "(".$student_info->getShift->name."/".$student_info->getClass->name."/".$student_info->getSection->name.") <br><small class='h6 font-weight-bold'> ".$exam_info->name." (".$exam_info->start_date." to ".$exam_info->end_date.")</small>";
      // dd($school_info,$school_info->id,$std_slug,$db_student);
      return view('main.school.info.student.examsection.ledger.marksheet',compact('settings','school_info','std_slug','db_student','exam_info','observations','grouped_class_subject','settings_school','grades','grades_asc','page','detail'));
    }

    public function getExamShiftList(Request $request, $school_slug){
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        $data_id = $request->input('data_id');
        // dd($data_id,$school_info,$request);
        $class_list_data = Shift::whereHas('getExamShiftList', function (Builder $query) use ($school_info,$data_id) {
          $query->where('school_id',$school_info->id)->where('exam_id', $data_id);
        })
        ->where('school_id',$school_info->id)
        ->get();
        return Response::json($class_list_data);
    }

    public function getExamClassList(Request $request){
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        $data_id = $request->input('data_id');
        $exam_id = $request->input('exam_id');
        // dd($request);
        // dd($exam_id,$data_id);
        $class_list_data = SClass::whereHas('getExamClass', function (Builder $query) use ($school_info,$data_id,$exam_id) {
          $query->where('shift_id', $data_id)->where('exam_id', $exam_id)
                ->where('school_id',$school_info->id);
        })
        ->where('school_id',$school_info->id)
        ->get();
        // dd($class_list_data);
        return Response::json($class_list_data);
    }

    public function getExamSectionList(Request $request){
      $settings = $this->schoolCheck($request)['setting'];
      $school_info = $this->schoolCheck($request)['school_info'];
      $data_id = $request->input('data_id');
      $shift_id = $request->input('shift_id');
      $exam_id = $request->input('exam_id');
      $class_list_data = Section::whereHas('getExamSectionList', function (Builder $query) use ($school_info,$data_id) {
        $query->where('class_id', $data_id)
              ->where('school_id',$school_info->id);
      })
      ->where('school_id',$school_info->id)
      ->get();
      return Response::json($class_list_data);
    }

 

}
