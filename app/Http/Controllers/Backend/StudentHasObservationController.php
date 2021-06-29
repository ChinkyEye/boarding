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
use App\Examhasclass;
use App\StudentHasMark;
use App\StudentHasObservation;
use App\ObservationHasMark;
use App\Observation;
use App\Exam;
use App\User;




class StudentHasObservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        //
    }

    public function mark($user_slug,$exam_slug)
    {
        $user_id = Student::where('school_id', Auth::user()->school_id)
                          ->whereHas('getStudentViaBatch', function(Builder $query){
                             $query->where('batch_id', Auth::user()->batch_id);
                            })
                          // ->where('batch_id', Auth::user()->batch_id)
                          ->where('slug',$user_slug)
                          ->value('user_id');
        $student_id = $user_id;
        $db_student = User::find($user_id);
        $value = Student::where('school_id', Auth::user()->school_id)
                        ->whereHas('getStudentViaBatch', function(Builder $query){
                           $query->where('batch_id', Auth::user()->batch_id);
                         })
                         // ->where('batch_id', Auth::user()->batch_id)
                         ->where('slug',$user_slug)
                         ->value('id');
        $student_findid = Student::find($value);

        $exam_id = Exam::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('slug', $exam_slug)->value('id');
        $db_exam = Exam::find($exam_id);
        $classexam_id = Examhasclass::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('exam_id', $exam_id)->where('class_id', $student_findid->class_id)->where('shift_id', $student_findid->shift_id)->value('id');
        // dd($classexam_id);

        // update
        $invoicemark_id = StudentHasMark::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                                        ->where('user_id',$user_id)
                                        ->where('classexam_id',$classexam_id)
                                        ->value('invoicemark_id');
        $observation_mark = ObservationHasMark::where('school_id',Auth::user()->school_id)
                                        ->where('classexam_id',$classexam_id)
                                        ->where('student_id', $student_id)
                                        ->where('invoicemark_id',$invoicemark_id);

        $observation_mark_list = $observation_mark->get('observation_id');
        $check_data = $observation_mark->count();

        // create
        $observations = Observation::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                                    ->where('is_active', True)
                                    ->orderBy('title','Asc')
                                    ->get();
        $groups = $observations->groupBy('title');
        // $groups = $observations->unique('title');

        // dd($groups);
        return view('backend.examsection.observation.observemark',compact('student_id','classexam_id','observations','groups','check_data','observation_mark_list','invoicemark_id','user_slug','exam_slug'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $observations = $request->observation_id;
        $classexam_id = $request->classexam_id;
        $student_id = $request->student_id;

        $user_slug = $request->user_slug;
        $exam_slug = $request->exam_slug;
        // dd($user_slug,$exam_slug);
        $invoicemark_id = StudentHasMark::where('user_id',$student_id)->where('classexam_id',$classexam_id)->value('invoicemark_id');
        // dd($invoicemark_id);
        $check_data = ObservationHasMark::where('classexam_id',$classexam_id)->where('student_id', $student_id)->where('invoicemark_id',$invoicemark_id)->count();
        // dd($check_data);
        if($check_data == 0){
            foreach( $observations AS $observation ){
              $bills= ObservationHasMark::create([
                  'student_id' => $request['student_id'],
                  'classexam_id' => $request['classexam_id'],
                  'observation_id' => $observation,
                  'invoicemark_id' => $invoicemark_id,
                  'school_id' => Auth::user()->school_id,
                  'batch_id' => Auth::user()->batch_id,
                  'created_by' => Auth::user()->id,
                  'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
              ]);
              
              $issue = StudentHasObservation::where('invoicemark_id',$invoicemark_id)->value('id');
              $id_student_observe = StudentHasObservation::find($issue);
              // $id_student_observe->is_published = '1';
              $id_student_observe->observation_id = $invoicemark_id;
              $id_student_observe->save();
            }
        }
        else{
            $get_observ_id = ObservationHasMark::where('school_id',Auth::user()->school_id)
                                                ->where('invoicemark_id',$request->invoicemark_id)
                                                ->delete();
            // dd($observations , $request->invoicemark_id , $get_observ_id);
            foreach( $observations AS $observation ){
              $bills = ObservationHasMark::create([
                  'student_id' => $request['student_id'],
                  'classexam_id' => $request['classexam_id'],
                  'observation_id' => $observation,
                  'invoicemark_id' => $request->invoicemark_id,
                  'school_id' => Auth::user()->school_id,
                  'batch_id' => Auth::user()->batch_id,
                  'created_by' => Auth::user()->id,
                  'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
              ]);
              
            }

        }

        $pass = array(
                     'message' => 'Data added successfully!',
                     'alert-type' => 'success'
                 );
        return redirect()->route('admin.studenthasmark',['slug' => $user_slug,'exam' => $exam_slug])->with($pass);
        // return back()->with($pass)->withInput();
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
