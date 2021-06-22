<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use App\Helper\Helper;
use App\Exam;
use App\SClass;
use App\MarkClass;
use App\Examhasclass;
use App\Subject;
use Auth;
use Response;


class ClassHasMarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function main($exam,$slug)
    {
        $exam_id = Exam::where('slug',$exam)->value('id');
        $class_id = SClass::where('slug',$slug)->value('id');
        $classexam_id = Examhasclass::where('exam_id',$exam_id)->where('class_id',$class_id)->value('id'); 
        // $subjects=Subject::where('class_id',$class_id)->get();
        // dd($subjects);
        $exams=Exam::where('id',$exam_id)->value('name');
        $classes=SClass::where('id',$class_id)->value('name');
        $classhasmarks = MarkClass::where('classexam_id',$classexam_id)->orderBy('sort_id','DESC')->orderBy('id','DESC')->get();
        $subjects = Subject::where('class_id',$class_id);
        $check_data = MarkClass::where('classexam_id',$classexam_id)->whereHas('getClassExam', function (Builder $query) use ($class_id) {
                                $query->where('class_id', $class_id);
                            })
                            ->count();
        // $check_data = $subjects->whereHas('getMarkSubjectList', function (Builder $query) use ($classexam_id) {
        //                         $query->where('classexam_id', $classexam_id);
        //                     })
        //                     ->count();
                            // dd($check_data);
        if($check_data != 0){
            $subjects = MarkClass::where('classexam_id',$classexam_id)->whereHas('getClassExam', function (Builder $query) use ($class_id) {
                                $query->where('class_id', $class_id);
                            })
                            ;
                            // dd($subjects);
        }
        $subjects = $subjects->get();
                            // dd($subjects);

        return view('backend.examsection.classhasmark.index',compact('classexam_id','subjects','classhasmarks','exams','classes','class_id','check_data'));
    }

    public function index()
    {
        //
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
      $classexam_id = $request->classexam_id;
      $exam_info = Examhasclass::with('getExam')->find($classexam_id);
      // dd();
      $class_id = $request->class_id;
      $check_data = MarkClass::where('classexam_id',$classexam_id)
                          ->whereHas('getClassExam', function (Builder $query) use ($class_id) {
                              $query->where('class_id', $class_id);
                          })
                          ->count();
      // dd($check_data,$request->type);
      $subject_id = $request->input('subject_id');
      if($check_data == 0){
        foreach( $subject_id AS $key=>$subject ){
          // dd($check_data,$request->full_mark[$key],$request);
          $classhasmark= MarkClass::create([
              'classexam_id' => $request['classexam_id'],
              'subject_id' => $subject,
              'full_mark' => $request->full_mark[$key],
              'pass_mark' => $request->pass_mark[$key],
              'type_id' => $request->type[$key], 
              'school_id' => Auth::user()->school_id,
              'batch_id' => Auth::user()->batch_id,
              'created_by' => Auth::user()->id,
              'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
              
          ]);
        }
      }
      else{
        $subject_id = $request->input('subject_id');
        // dd($subject_id);
        foreach( $subject_id AS $key=>$subject ){
          // dd($check_data,$request->subject,$request);
              $get_subject_id = MarkClass::where('classexam_id',$classexam_id)
              ->whereHas('getClassExam', function (Builder $query) use ($class_id,$subject) {
                              $query->where('class_id', $class_id);
                              $query->where('subject_id',$subject);
                          })
                          ->value('id'); 
              $std_tchr_update= MarkClass::find($get_subject_id);
              // dd($std_tchr_update);
              $std_tchr_update->subject_id = $subject;
              // dd($std_tchr_update->subject_id);
              $std_tchr_update->full_mark =  $request->full_mark[$key];
              $std_tchr_update->pass_mark = $request->pass_mark[$key];
              $std_tchr_update->updated_by = Auth::user()->id;
              // dd($std_tchr_update->updated_by);
              $std_tchr_update->update();
          }
      }
      $pass = array(
        'message' => 'Data added successfully!',
        'alert-type' => 'success'
      );
      return redirect()->route('admin.examhasclass',$exam_info->getExam->slug)->with($pass)->withInput();
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
        $classhasmarks = MarkClass::where('id', $id)->get();
        $class_id = Examhasclass::where('id',$id)->value('class_id');
        $subjects=Subject::where('class_id',$class_id)->get();
        return view('backend.examsection.classhasmark.edit', compact('classhasmarks','subjects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MarkClass $classhasmark)
    {
        $this->validate($request, [
          'subject_id' => 'required',
          'full_mark' => 'required',
          'pass_mark' => 'required',
          'room' => 'required',

        ]);
        // dd($request);
        $request['updated_by'] = Auth::user()->id;
        if($classhasmark->update($request->all())){
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
        return back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MarkClass $classhasmark)
    {
        if($classhasmark->delete()){
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
}
