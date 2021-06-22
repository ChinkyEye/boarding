<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use App\Helper\Helper;
use App\Exam;
use App\Examhasclass;
use App\Shift;
use App\SClass;
use App\Section;
use App\Student;
use Auth;
use Response;
use App\Exports\ExamhasClassExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;


class ExamHasClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function admitcard(Request $request,$id) {
        // var_dump($id); die();
        $exam_id = ExamhasClass::where('id',$id)->value('exam_id');
        $exams = Exam::where('id',$exam_id)->value('name'); 
        $class_id = ExamhasClass::where('id',$id)->value('class_id');
        $shift_id = ExamhasClass::where('id',$id)->value('shift_id');
        $students = Student::where('shift_id',$shift_id)->where('class_id',$class_id)->get();
        return view('backend.examsection.exam_has_class.admit',compact('exams','students'));
  }


  public function main($slug)
  {
      $exam_id = Exam::where('slug',$slug)->value('id'); 
      $shifts = Shift::where('school_id', Auth::user()->school_id)
                  ->where('batch_id', Auth::user()->batch_id)
                  ->where('is_active','1')
                  ->get();
      $examhasclasses=Examhasclass::where('exam_id',$exam_id)
                                  ->orderBy('sort_id','DESC')
                                  ->orderBy('id','DESC')
                                  ->paginate(5);
      $exams = Exam::where('slug',$slug)->value('name'); 
      return view('backend.examsection.exam_has_class.index',compact('exam_id','shifts','examhasclasses','exams','slug'));
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
        $this->validate($request, [
            'class_id' => 'required',
            'shift_id' => 'required',
            'exam_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'result_date' => 'required',
        ]);
        $examhasclass= Examhasclass::create([
            'class_id' => $request['class_id'],
            'shift_id' => $request['shift_id'],
            'exam_id' => $request['exam_id'],
            'start_time' => $request['start_time'],
            'end_time' => $request['end_time'],
            'result_date' => $request['result_date'],
            'school_id' => Auth::user()->school_id,
            'batch_id' => Auth::user()->batch_id,
            'created_by' => Auth::user()->id,
            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
            
        ]);
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
    public function show($id)
    {
        $examhasclasses = Examhasclass::where('id', $id)->get();
        return view('backend.examsection.exam_has_class.show', compact('examhasclasses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $examhasclasses = Examhasclass::where('id', $id)->get();
        $exams=Exam::get();
        $classes=SClass::get();
        $sections=Section::get();
        $shifts=Shift::get();
        return view('backend.examsection.exam_has_class.edit', compact('examhasclasses','exams','classes','sections','shifts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Examhasclass $examhasclass)
    {
        $this->validate($request, [
          'start_time' => 'required',
          'end_time' => 'required',
          'result_date' => 'required',

      ]);
        // dd($request);
        $request['updated_by'] = Auth::user()->id;
        if($examhasclass->update($request->all())){
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
      return redirect()->route('admin.examhasclass', $examhasclass->getExam->slug)->with($notification);
  }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Examhasclass $examhasclass,Request $request)
    {
      // dd($request);
      $data_id = $examhasclass->id;
      $examhasclass = Examhasclass::find($data_id);
      $examhas_class_mark = $examhasclass->getClassExamMany->each;
      // dd($teacher_shift);

      if($examhas_class_mark->delete()){
          $notification = array(
              'message' => 'Marks of '.$examhasclass->name.' has delete successfully!',
              'status' => 'success'
          );
      }else{
          $notification = array(
            'message' => 'Subject of '.$examhasclass->name.' could not be delete!',
            'status' => 'error'
        );
          return back()->with($notification);
      }

      if($examhasclass->delete()){
       $notification = array(
         'message' => 'Data is deleted successfully!',
         'status' => 'success'
     );
   }else{
       $notification = array(
         'message' => 'Daata could not be deleted!',
         'status' => 'error'
     );
   }
   return back()->with($notification);
}

public function isactive(Request $request,$id)
{
  $get_is_active = Examhasclass::where('id',$id)->value('is_active');
  $isactive = Examhasclass::find($id);
  if($get_is_active == 0){
    $isactive->is_active = 1;
    $notification = array(
      'message' => 'This is Active!',
      'alert-type' => 'success'
  );
}
else {
    $isactive->is_active = 0;
    $notification = array(
      'message' => 'This is inactive!',
      'alert-type' => 'error'
  );
}
if(!($isactive->update())){
    $notification = array(
      'message' => 'This could not be changed!',
      'alert-type' => 'error'
  );
}
return back()->with($notification)->withInput();
}
}
