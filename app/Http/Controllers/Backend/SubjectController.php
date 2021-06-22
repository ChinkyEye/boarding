<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use App\Helper\Helper;
use Auth;
use Validator;
use App\SClass;
use App\Subject;
use App\SubjectHasTheoryPractical;
use Response;


class SubjectController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function main($slug)
    {
      $class_id = SClass::where('slug',$slug)->value('id'); 
      $page = SClass::where('slug',$slug)->value('name'); 
      $subjects = Subject::where('class_id',$class_id)->orderBy('sort_id','DESC')->orderBy('created_at','DESC')->get();
      return view('backend.mainentry.subject.index',compact('class_id','subjects','page'));
    }

    public function getStudentList(Request $request)
    { 
      $data_id = $request->input('data_id');
      $students = Student::where('section_id', $data_id)->get();
      return Response::json($student);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      // this page is not used
        $subjects=Subject::orderBy('sort_id','DESC')->orderBy('id','DESC')->paginate(5);
        $classes=SClass::orderBy('sort_id','DESC')->orderBy('id','DESC')->paginate(5);
        return view('backend.mainentry.subject.create',compact('subjects','classes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request['slug'] = $this->helper->slug_converter($request['name']).'-'.Auth::user()->school_id;
      $this->validate($request, [
        'name' => 'required',
        'slug' => 'required|unique:subjects|min:2',
        'subject_code' => 'required',
        'theory_practical' => 'required',
        'compulsory_optional' => 'required',
        'credit_hour' => 'required',
        'class_id' => 'required',
      ]);

      $subjects= Subject::create([
        'name' => $request['name'],
        'slug' => $this->helper->slug_converter($request['subject_code']).'-'.Auth::user()->school_id,
        'subject_code' => $request['subject_code'],
        'theory_practical' => $request['theory_practical'],
        'compulsory_optional' => $request['compulsory_optional'],
        'credit_hour' => $request['credit_hour'],
        'class_id' => $request['class_id'],
        'school_id' => Auth::user()->school_id,
        'batch_id' => Auth::user()->batch_id,
        'created_by' => Auth::user()->id,
        'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
      ]);

      if($request->theory_practical == 1){
        $subjecthastheorypractical= SubjectHasTheoryPractical::create([
          'theory_practical' => '1',
          'subject_id' => $subjects->id,
          'school_id' => Auth::user()->school_id,
          'batch_id' => Auth::user()->batch_id,
          'created_by' => Auth::user()->id,
          'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);
      }
      else if ($request->theory_practical == 2) {
        $subjecthastheorypractical= SubjectHasTheoryPractical::create([
          'theory_practical' => '2',
          'subject_id' => $subjects->id,
          'school_id' => Auth::user()->school_id,
          'batch_id' => Auth::user()->batch_id,
          'created_by' => Auth::user()->id,
          'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);
      }
      else{
        $value = array(1,2);
        foreach( $value AS $key=>$subject ){
          $subjecthastheorypractical= SubjectHasTheoryPractical::create([
            'theory_practical' => $subject,
            'subject_id' => $subjects->id,
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
      $subjects = Subject::where('id', $id)->get();
      // dd($subjects);
      return view('backend.mainentry.subject.show', compact('subjects'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $subjects = Subject::where('id', $id)->get();
      $class_id = Subject::where('id',$id)->value('class_id'); 
      $page = SClass::where('id',$class_id)->value('name'); 
      // dd($page);
      return view('backend.mainentry.subject.edit', compact('subjects','page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
      $check_dub = Subject::where('name',$request->name)->count();
      if($check_dub == 0 || $request->name == $subject->name){
        $this->validate($request, [
            'name' => 'required|min:2',
            'subject_code' => 'required',
            'credit_hour' => 'required',
        ]);
        $main_data = $request->all();
        $main_data['updated_by'] = Auth::user()->id;
        if($subject->update($main_data)){
            $notification = array(
                'message' => $request->name.' updated successfully!',
                'alert-type' => 'success'
            );
        }else{
            $notification = array(
                'message' => $request->name.' could not be updated!',
                'alert-type' => 'error'
            );
        }
      }else{
        $this->validate($request, [
            'name' => 'required|unique:subjects|min:2',
        ]);
      }
      return redirect()->route('admin.subject', $subject->sclass->slug)->with($notification);

      
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Subject $subject)
    {
      // dd($request);

      $data_id = $subject->id;
      $data_subject_id = SubjectHasTheoryPractical::where('subject_id',$data_id)->value('id');
      $delete_subject = SubjectHasTheoryPractical::find($data_subject_id);
      // dd($delete_subject);

      if($subject && $delete_subject){
        $delete_subject->delete();
        $subject->delete();
        $notification = array(
          'message' => $subject->name.' Subject is deleted successfully!',
          'status' => 'success'
        );
      }
      else{
        $notification = array(
          'message' => $subject->name.' Subject could not be deleted!',
          'status' => 'error'
        );
      }
      return Response::json($notification);
    }

    public function isSort(Request $request,$id)
    {
      $sort_ids =  Subject::find($request->id);
      $sort_ids->sort_id = $request->value;
      if($sort_ids->save()){
        $response = array(
          'status' => 'success',
          'msg' => $sort_ids->name.' Successfully changed position to '.$request->value,
        );
      }else{
        $response = array(
          'status' => 'failure',
          'msg' => 'Sorry, '.$sort_ids->name.' could not change position to '.$request->value,
        );
      }
      return Response::json($response);
    }

    public function isactive(Request $request,$id)
    {
      $get_is_active = Subject::where('id',$id)->value('is_active');
      $isactive = Subject::find($id);
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
          'message' =>  $isactive->name.' is inactive!',
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
}
