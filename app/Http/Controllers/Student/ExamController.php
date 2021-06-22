<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\User;
use App\Student;
use Auth;
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

class ExamController extends Controller
{
    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
      // var_dump("expression"); die();
        $user_id = Auth::user()->id;
        $average_grade = 0;
        $gpa = 0;
        $remarks = 0;
        $student_id = Student::where('user_id',$user_id)->value('id');
        $student_info = Student::find($student_id);

        $grades = Grade::where('school_id', Auth::user()->school_id)
                    ->where('batch_id', Auth::user()->batch_id)
                    ->with('getUser')
                    ->get(); 
        $exam_class = Examhasclass::where('shift_id',$student_info->shift_id)
                                    ->where('class_id',$student_info->class_id)
                                    ->where('school_id', Auth::user()->school_id)
                                    ->where('batch_id', Auth::user()->batch_id)
                                    ->where('is_active', True)
                                    ->orderBy('id', 'DESC');
        // dd($request->search);
        $exams = StudentHasMark::where('user_id', $user_id)
                                ->where('school_id', Auth::user()->school_id)
                                ->where('batch_id', Auth::user()->batch_id)
                                // ->where('classexam_id', "2")
                                ->where('is_active', "1")
                                ->orderBy('id', 'DESC');
                                
        $all_exam_class = $exam_class->with('getExam')->get();
        if ($request->search != "") {
        	$classexam_id = Examhasclass::where('exam_id',$request->search)
                                        ->where('school_id', Auth::user()->school_id)
                                        ->where('batch_id', Auth::user()->batch_id)
                                        ->where('is_active', True)
                                        ->value('id');
        	$exams = $exams->where('classexam_id',$classexam_id);
        }else{
            $classexam_id = $exam_class->first('id');
        	$exams = $exams->where('classexam_id',$classexam_id->id);
        }

        // dd($classexam_id,$all_exam_class);
        $pre_grade_point_value = 0; 
        $total = $exams->sum('obtained_mark');
        // dd($exams,$total);     
        // $gpa = "2.5";
        $gpa = number_format(round(($exams->sum('grade_point')) / ($exams->count() + 1) , 2) , 2, '.', '');
        foreach ($grades as $loop=>$value_grade){
            if ($gpa >= $value_grade->grade_point){
                if ($gpa < $pre_grade_point_value){
                    $average_grade = $value_grade->name;
                    $remarks = $value_grade->remark;
                }
            }
            $pre_grade_point_value = $value_grade->grade_point;
        }
        if ($gpa == 0){
            $average_grade = "F";
            $remarks = "";
        }

        $exams_list = $exams->with(
                                'getUser',
                                'getStudentUser',
                                'getSubject',
                                'getStudentOne',
                                'getStudentOne.getShift',
                                'getStudentOne.getClass',
                                'getStudentOne.getSection',
                                'getStudentOne.getBatch',
                                'getClassExam',
                                'getClassExam.getExam',
                                'getSchool',
                                'getStudentObservationPublishedOne',
                                'getStudentObservationPublishedOne.getStudentObservationMark',
                                'getStudentObservationPublishedOne.getStudentObservationMark.getObservation'
                                )
                            ->get();
        $examclass = Examhasclass::where('school_id',Auth::user()->school_id)
                                   ->where('batch_id', Auth::user()->batch_id)
                                   ->where('class_id', $student_info->class_id)
                                   ->where('shift_id', $student_info->shift_id)
                                   ->where('is_active', True)
                                   ->with('getExam')
                                   ->get();
        $student_class = $student_info->class_id ;
        // dd($student_class);
        $try = Exam::where('school_id',Auth::user()->school_id)->get();
        // dd($examclass);                    
        $response = [
            'examlist' => $examclass,
            'studentlist' => $try,
            
        ];
        return response()->json($response,200);
    }

    public function create()
    {
      //                
    }

    public function store(Request $request)
    {
        //
    }

   public function show(Request $request, $exam_id)
   {
       // dd($request, $exam_id);
     

               $user_id = Auth::user()->id;
               $average_grade = 0;
               $gpa = 0;
               $remarks = 0;
               $student_id = Student::where('user_id',$user_id)->value('id');
               $student_info = Student::find($student_id);
               // dd($student_info);

               $grades = Grade::where('school_id', Auth::user()->school_id)
                           ->where('batch_id', Auth::user()->batch_id)
                           ->with('getUser')
                           ->get(); 
               $exam_class = Examhasclass::where('shift_id',$student_info->shift_id)
                                           ->where('class_id',$student_info->class_id)
                                           ->where('school_id', Auth::user()->school_id)
                                           ->where('batch_id', Auth::user()->batch_id)
                                           ->where('is_active', True)
                                           ->orderBy('id', 'DESC');
               // dd($request->search);
               $exams = StudentHasMark::where('user_id', $user_id)
                                       ->where('school_id', Auth::user()->school_id)
                                       ->where('batch_id', Auth::user()->batch_id)
                                       // ->where('classexam_id', "2")
                                       ->where('is_active', "1")
                                       ->orderBy('id', 'DESC');
                                       // dd($exams->get());
                                       
               $all_exam_class = $exam_class->with('getExam')->get();
               // dd($all_exam_class);
               // $classexam_id = Examhasclass::where('exam_id',$exam_id)
               //                                ->where('school_id', Auth::user()->school_id)
               //                                ->where('class_id', $student_info->class_id)
               //                                ->where('shift_id', $student_info->shift_id)
               //                                ->where('batch_id', Auth::user()->batch_id)
               //                                ->where('is_active', True)
               //                                ->value('id');
               $exams = $exams->where('classexam_id',$exam_id);
               //yo exam_id examhasclass ko id ho
               
               // dd($exams);
               // dd($classexam_id,$exams->get());
               // if ($request->search != "") {
               //  $classexam_id = Examhasclass::where('exam_id',$exam_id)
               //                                 ->where('school_id', Auth::user()->school_id)
               //                                 ->where('batch_id', Auth::user()->batch_id)
               //                                 ->where('is_active', True)
               //                                 ->value('id');
               //  $exams = $exams->where('classexam_id',$classexam_id);
               // }else{
               //     $classexam_id = $exam_class->first('id');
               //  $exams = $exams->where('classexam_id',$classexam_id->id);
               // }

               // dd($classexam_id,$all_exam_class);
               $pre_grade_point_value = 0; 
               $total = $exams->sum('obtained_mark');
               // dd($exams->get(),$total);     
               // $gpa = "2.5";
               $gpa = number_format(round(($exams->sum('grade_point')) / ($exams->count() + 1) , 2) , 2, '.', '');
               foreach ($grades as $loop=>$value_grade){
                   if ($gpa >= $value_grade->grade_point){
                       if ($gpa < $pre_grade_point_value){
                           $average_grade = $value_grade->name;
                           $remarks = $value_grade->remark;
                       }
                   }
                   $pre_grade_point_value = $value_grade->grade_point;
               }
               if ($gpa == 0){
                   $average_grade = "F";
                   $remarks = "";
               }

               $exams_list = $exams->with(
                                       'getUser',
                                       'getStudentUser',
                                       'getSubject',
                                       'getStudentOne',
                                       'getStudentOne.getShift',
                                       'getStudentOne.getClass',
                                       'getStudentOne.getSection',
                                       'getStudentOne.getBatch',
                                       'getClassExam',
                                       'getClassExam.getExam',
                                       'getSchool',
                                       'getStudentObservationPublishedOne',
                                       'getStudentObservationPublishedOne.getStudentObservationMark',
                                       'getStudentObservationPublishedOne.getStudentObservationMark.getObservation'
                                       )
                                   ->get();
            $response = [
                   'studentlists' => $exams_list,
                   'examlist' => $all_exam_class,
                   'gradelist' => $grades,
                   'average_grade' => $average_grade,
                   'gpa' => $gpa,
                   'remarks' => $remarks,
               ];
            return response()->json($response,200);   
           
   }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
