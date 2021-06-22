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
use App\Teacher_has_period;
use App\Teacher_has_subject;
use App\Teacher_has_shift;
use App\Teacher;
use App\SClass;
use App\Section;
use App\Shift;
use App\User;

class TeacherHasPeriodController extends Controller
{
    public function index($slug)
    {
      $teacher_id = Teacher::where('slug',$slug)->value('user_id');
      $teacher = User::find($teacher_id);
      $teacher_shift = Shift::whereHas('getTeacherShiftList', function (Builder $query) use ($teacher_id) {
        $query->where('user_id', $teacher_id);
      })
      ->get();
      $teacherhasperiods = Teacher_has_period::where('teacher_id',$teacher_id)->orderBy('sort_id','DESC')->orderBy('created_at','DESC')->get();
      $shifts = Shift::orderBy('sort_id','DESC')->orderBy('id','DESC')->get();
      // dd($shifts);
      return view('backend.primaryentry.teacherhasperiods.index',compact('teacher_id','teacherhasperiods','shifts','teacher_shift','teacher'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $subjects=Subject::orderBy('sort_id','DESC')->orderBy('id','DESC')->paginate(5);
      $teachers=SClass::orderBy('sort_id','DESC')->orderBy('id','DESC')->paginate(5);
      return view('backend.primaryentry.subject.create',compact('subjects','teachers'));
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
          'class_id' => 'required',
          'shift_id' => 'required',
          'section_id' => 'required',
          'teacher_id' => 'required',
          'subject' => 'required',
      ]);
      $class_id = $request->class_id;
      $shift_id = $request->shift_id;
      $section_id = $request->section_id;
      $teacher_id = $request->teacher_id;
      // yo query milaunu paro
      // $data = Teacher_has_period::where('teacher_id','=', $teacher_id)->where('class_id','=', $class_id)->where('shift_id','=', $shift_id)->where('section_id','=', $section_id)->count();
      $data = 0;
      // dd($data);
      if($data > 0)
      {
       $pass = array(
         'message' => 'Subject already taken for the teacher!',
         'alert-type' => 'error'
       );
      }
      else
      {
        $teacherhasperiods = Teacher_has_period::create([
            'class_id' => $request['class_id'],
            'shift_id' => $request['shift_id'],
            'section_id' => $request['section_id'],
            'teacher_id' => $request['teacher_id'],
            'school_id' => Auth::user()->school_id,
            'batch_id' => Auth::user()->batch_id,
            'created_by' => Auth::user()->id,
            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);
        foreach ($request->subject as $key => $value) {
          $teacherhassubject = Teacher_has_subject::create([
            'teacher_id' => $request['teacher_id'],
            'subject_id' => $value,
            'teacher_period_id' => $teacherhasperiods->id,
            'date' => $this->helper->date_np_con_parm(Date('Y-m-d')),
            'date_en' => Date('Y-m-d'),
            'created_by' => Auth::user()->id,
            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
            'school_id' => Auth::user()->school_id,
            'batch_id' => Auth::user()->batch_id,
          ]);
        }
        $pass = array(
          'message' => 'Data added successfully!',
          'alert-type' => 'success'
        );
      }
     
      return back()->with($pass)->withInput();
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
    public function edit($id,Request $request)
    {
      // dd($request);
        $teacherhasperiods = Teacher_has_period::where('id', $id)->get();
        $teacher_id = Teacher_has_period::where('id', $id)->value('teacher_id');
        // dd($teacher_id);
        // $shifts = Shift::whereHas('getTeacherShiftList', function (Builder $query) use ($teacher_id) {
        //                       $query->where('user_id', $teacher_id);
        //                   })
        //                 ->get();
        $shifts = Shift::get();                
        // dd($shifts);                
        return view('backend.primaryentry.teacherhasperiods.edit', compact('teacherhasperiods','shifts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Teacher_has_period $teacherhasperiod)
    {
      // dd($request);
        $this->validate($request, [
            'class_id' => 'required',
            'shift_id' => 'required',
            'section_id' => 'required',
        ]);

        $main_data = $request->all();
        $main_data['updated_by'] = Auth::user()->id;
        // dd($request);
          // dd($teacherhasperiod->update($main_data));
        if($teacherhasperiod->update($main_data)){
            $notification = array(
                'message' => $request->teacher_id.' updated successfully!',
                'alert-type' => 'success'
            );
        }else{
            $notification = array(
                'message' => $request->teacher_id.' could not be updated!',
                'alert-type' => 'error'
            );
        }
        return redirect()->route('admin.teacherhasperiod', $teacherhasperiod->teacher->slug)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher_has_period $teacherhasperiod)
    {
      $teacher_subject = Teacher_has_subject::where('teacher_period_id',$teacherhasperiod->id)->get();

      foreach ($teacher_subject as $key => $value) {
        $value->getTeacherSubjectRoutine->each->delete();
      }

      if($teacherhasperiod->getTeacherSubject->each->delete()){
        $teacherhasperiod->delete();
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

    public function isSort(Request $request,$id)
    {
      $sort_ids =  Teacher_has_period::find($request->id);
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

    public function isactive(Request $request,$id)
    {
      $get_is_active = Teacher_has_period::where('id',$id)->value('is_active');
      $isactive = Teacher_has_period::find($id);
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

    public function getTeacherRoutineClassList(Request $request){
      // dd($request);
      $routine_list = Teacher_has_period::where('school_id',Auth::user()->school_id)
                          ->where('is_active','1')
                          ->where('teacher_id',$request->teacher_id)
                          ->where('shift_id',$request->shift_id)
                          ->where('class_id',$request->class_id)
                          ->where('section_id',$request->section_id)
                          ->count();
      $teacher_info = User::find($request->teacher_id);
      $class_name = SClass::where('id',$request->shift_id)->value('name');
      $shift_name = Shift::where('id',$request->class_id)->value('name');
      $section_name = Section::where('id',$request->section_id)->value('name');
      if($routine_list){
        $response = array(
          'data' => $routine_list,
          'status' => 'error',
          'msg' => $teacher_info->name.' already assign on '.$shift_name.', '.$class_name.' '.$section_name.'.',
        );
      }else{
        $response = array(
          'data' => $routine_list,
        );
      }
      return Response::json($response);
    }
}
