<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helper\Helper;
use Validator;
use App\SClass;
use App\Routine;
use App\Period;
use App\Teacher;
use App\Teacher_has_shift;
use App\Teacher_has_period;
use App\Teacher_has_subject;
use App\Section;
use App\Subject;
use App\Shift;
use Auth;
use Response;

class RoutineController extends Controller
{
    public function index()
    {
        $shifts = Shift::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active','1')
                        ->get();
        $routines = Routine::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->where('day_id', date('w'))
                        ->orderBy('period_id','ASC')
                        ->get();
        return view('backend.dailyrecord.routine.index',compact('routines','shifts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shifts = Shift::where('school_id', Auth::user()->school_id)
                    ->where('batch_id', Auth::user()->batch_id)
                    ->where('is_active','1')
                    ->orderBy('sort_id','DESC')
                    ->get();
        $periods = Period::where('school_id', Auth::user()->school_id)
                    ->where('batch_id', Auth::user()->batch_id)
                    ->where('is_active','1')
                    ->orderBy('sort_id','DESC')
                    ->get();
        return view('backend.dailyrecord.routine.create',compact('shifts','periods'));
    }

    public function getShiftRTeacherList(Request $request){
        $shift_id = $request->shift_id;
        $shiftteacherlist = Teacher_has_shift::where('shift_id',$shift_id)
                                            ->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                                            ->with('teacher')
                                            ->get();
        return Response::json($shiftteacherlist);
    }

    public function getShiftRClassList(Request $request){
        $shift_id = $request->shift_id;
        $teacher_id = $request->teacher_id;
        $classteacherlist = Teacher_has_period::where('shift_id',$shift_id)
                                            ->where('teacher_id', $teacher_id)
                                            ->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                                            ->with('getClass')
                                            ->groupBy('class_id')
                                            ->get();
        return Response::json($classteacherlist);
    }

    public function getShiftRSectionList(Request $request){
        $shift_id = $request->shift_id;
        $teacher_id = $request->teacher_id;
        $class_id = $request->class_id;
        $sectionteacherlist = Teacher_has_period::where('shift_id',$shift_id)
                                            ->where('teacher_id', $teacher_id)
                                            ->where('class_id', $class_id)
                                            ->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                                            ->with('section')
                                            ->get();
        return Response::json($sectionteacherlist);
    }

    public function getShiftRSubjectList(Request $request){
        $shift_id = $request->shift_id;
        $teacher_id = $request->teacher_id;
        $class_id = $request->class_id;
        $section_id = $request->section_id;
        $teacher_period_id = Teacher_has_period::where('shift_id',$shift_id)
                                            ->where('teacher_id', $teacher_id)
                                            ->where('class_id', $class_id)
                                            ->where('section_id', $section_id)
                                            ->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                                            ->value('id');
        $subjects = Teacher_has_subject::where('teacher_period_id',$teacher_period_id)
                                        ->with('getSubject')
                                        ->get();
        return Response::json($subjects);
    }

    public function getTeacherRoutineList(Request $request){
      $routines = Routine::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                          ->where('user_id',$request->teacher_id)
                          ->where('shift_id',$request->shift_id)
                          ->orderBy('period_id','ASC');
      $routines = $routines->get();
      $routines_count = count($routines);
      // dd($routines, $routines_count);
      return view('backend.dailyrecord.routine.create-ajax', compact('routines','routines_count'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'class' => 'required',
            'shift' => 'required',
            'section' => 'required',
            'teacher' => 'required',
            'period' => 'required',
            'subject' => 'required',
            'day' => 'required',
        ]);
        // dd($request);
        $teacher =  $request->teacher;
        // var_dump($teacher); die();
        $user_id = Teacher::where('user_id', $teacher)->value('id');
        // dd($teacher,$teacher_id);
        // $teachers = User::find($teacher_id)->getUserTeacher()->get();
        $shift_id = $request->shift;
        $teacher_id = $request->teacher;
        $class_id = $request->class;
        $section_id = $request->section;
        $teacher_period_id = Teacher_has_period::where('shift_id',$shift_id)
                            ->where('teacher_id', $teacher_id)
                            ->where('class_id', $class_id)
                            ->where('section_id', $section_id)
                            ->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                            ->value('id');
        $teacher_subject_id = Teacher_has_subject::where('teacher_period_id', $teacher_period_id)->where('subject_id',$request->subject)->where('teacher_id',$request->teacher)->value('id');
        // var_dump($teacher_subject_id); die();
        $routines= Routine::create([
            'class_id' => $request->class,
            'shift_id' => $request->shift,
            'section_id' => $request->section,
            'teacher_id' => $user_id,
            'user_id' => $teacher,
            'period_id' => $request->period,
            'teacher_subject_id' => $teacher_subject_id,
            'day_id' => $request->day,
            'school_id' => Auth::user()->school_id,
            'batch_id' => Auth::user()->batch_id,
            'created_by' => Auth::user()->id,
            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
            
        ]);
        $pass = array(
          'message' => 'Data added successfully!',
          'alert-type' => 'success'
      );
        return redirect()->route('admin.routine.index')->with($pass)->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $routines = Routine::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        return view('backend.dailyrecord.routine.show', compact('routines'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $routines = Routine::find($id);
        $subjects = Teacher_has_subject::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                    ->where('teacher_id',$routines->user_id)
                    ->get();
        $periods = Period::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                    ->where('is_active','1')
                    ->orderBy('sort_id','DESC')
                    ->get();
        // dd($subjects , $periods);
        return view('backend.dailyrecord.routine.edit', compact('routines','subjects','periods'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Routine $routine)
    {
        $this->validate($request, [
            'subject_id' => 'required',
            'period_id' => 'required',
            'day_id' => 'required',
        ]);
        // dd($request);
        $main_data = $request->all();
        // dd($main_data);
        $main_data['updated_by'] = Auth::user()->id;
        $main_data['teacher_subject_id'] = $request->subject_id;
        // dd($main_data);
        if($routine->update($main_data)){

          $notification = array(
            'message' => 'Data updated successfully!',
            'alert-type' => 'success'
        );
      }else{
          $notification = array(
            'message' => 'Data could not be updated!',
            'alert-type' => 'error'
        );
      }
      return redirect()->route('admin.routine.index')->with($notification)->withInput();
  }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Routine $routine)
    {
        if($routine->delete()){
            $notification = array(
                'message' => $routine->name.' is deleted successfully!',
                'status' => 'success'
            );
        }else{
            $notification = array(
                'message' => $routine->name.' could not be deleted!',
                'status' => 'error'
            );
        }
        return redirect()->route('admin.routine.index')->with($notification)->withInput();
  }

  public function isSort(Request $request,$id)
  {
      $sort_ids =  Routine::find($request->id);
      $sort_ids->sort_id = $request->value;
      if($sort_ids->save()){
        $response = array(
          'status' => 'success',
          'msg' => $sort_ids->n_name.' Successfully changed position to '.$request->value,
      );
    }else{
        $response = array(
          'status' => 'failure',
          'msg' => 'Sorry the data could not be change',
      );
    }
    return Response::json($response);
}

public function isactive(Request $request,$id)
{
  $get_is_active = Routine::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$id)->value('is_active');
  $isactive = Routine::find($id);
  if($get_is_active == 0){
    $isactive->is_active = 1;
    $notification = array(
      'message' => $isactive->name.' is Active!',
      'alert-type' => 'success'
  );
}
else {
    $isactive->is_active = 0;
    $notification = array(
      'message' => $isactive->name.' is inactive!',
      'alert-type' => 'error'
  );
}
if(!($isactive->update())){
    $notification = array(
      'message' => $isactive->name.' could not be changed!',
      'alert-type' => 'error'
  );
}
return back()->with($notification)->withInput();
}

  public function getRoutineRecord(Request $request){
    $routines = Routine::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id);
    if(!empty($request->shift_data))
    {            
      $routines = $routines->where('shift_id', $request->shift_data);
    }

    if(!empty($request->class_data))
    {            
      $routines = $routines->where('class_id', $request->class_data);
    }

    if(!empty($request->section_data))
    {            
      $routines = $routines->where('section_id', $request->section_data);
    }

    $date = date('Y-m-d');
    $routines = $routines->where('day_id', $request->date_data)->orderBy('period_id','ASC')->get();

    $routines_count = count($routines);
    return view('backend.dailyrecord.routine.index-ajax', compact('routines','date','routines_count'));
  }
}
