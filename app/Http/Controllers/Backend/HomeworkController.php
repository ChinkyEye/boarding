<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use App\Helper\Helper;
use App\Homework;
use App\Shift;
use App\SClass;
use App\Section;
use App\Subject;
use App\Teacher;
use App\Teacher_has_subject;
use Auth;
use Validator;
use Response;

class HomeworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->orderBy('id','DESC')->get();
        $date = Date('Y-m-d');
        $nepali_date = $this->helper->date_np_con_parm($date);
        $homeworks = Homework::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                            ->orderBy('id', 'DESC')
                            ->where('date', $nepali_date)
                            ->get();
        return view('backend.dailyrecord.homework.index',compact('homeworks','shifts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->orderBy('id','DESC')->get();
        return view('backend.dailyrecord.homework.create', compact('shifts'));
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
            'description' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'shift_id' => 'required',
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'date' => 'required',
        ]);
        // dd($request);

        $homeworks = Homework::create([
            'class_id' => $request['class_id'],
            'section_id' => $request['section_id'],
            'shift_id' => $request['shift_id'],
            'subject_id' => $request['subject_id'],
            'teacher_id' => $request['teacher_id'],
            'description' => $request['description'],
            'date' => $request['date'],
            'date_eng' => $this->helper->date_eng_con_parm($request['date']),
            'school_id' => Auth::user()->school_id,
            'batch_id' => Auth::user()->batch_id,
            'created_by' => Auth::user()->id,
            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);
        $pass = array(
            'message' => 'Data added successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.homework.index')->with($pass);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $homeworks = Homework::find($id);
        return view('backend.dailyrecord.homework.show', compact('homeworks'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $homeworks = Homework::find($id);
        $subjects = Teacher_has_subject::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                    ->where('teacher_id',$homeworks->teacher_id)
                    ->get();
        return view('backend.dailyrecord.homework.edit', compact('homeworks','subjects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Homework $homework)
    {
        $this->validate($request, [
            'description' => 'required',
            'subject_id' => 'required',
        ]);
        $main_data = $request->all();
        $main_data['updated_by'] = Auth::user()->id;
        $main_data['date'] = $request->date;
        $main_data['date_eng'] = $this->helper->date_eng_con_parm($request->date);
        // dd($main_data['date']);

        if($homework->update($main_data)){
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

        return redirect()->route('admin.homework.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Homework $homework)
    {
        if($homework->delete()){
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
        return redirect()->route('admin.homework.index')->with($notification)->withInput();
    }


    public function isSort(Request $request,$id)
    {
        $sort_ids =  Homework::find($request->id);
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
        $get_is_active = Homework::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$id)->value('is_active');
        $isactive = Homework::find($id);
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
                'message' => 'Data is unpublished!',
                'alert-type' => 'error'
            );
        }
        $isactive->update();
        return back()->with($notification)->withInput();
    }

    public function getHomeworkRecord(Request $request){
        $homeworks = Homework::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                            ->orderBy('id', 'DESC');
        if(!empty($request->shift_data))
        {            
          $homeworks = $homeworks->where('shift_id', $request->shift_data);
        }

        if(!empty($request->class_data))
        {            
          $homeworks = $homeworks->where('class_id', $request->class_data);
        }

        if(!empty($request->section_data))
        {            
          $homeworks = $homeworks->where('section_id', $request->section_data);
        }

        if(!empty($request->date_data))
        {   
          $homeworks = $homeworks->where('date', $request->date_data);
        }

        $homeworks = $homeworks->get();

        $homeworks_count = count($homeworks);
        return view('backend.dailyrecord.homework.index-ajax', compact('homeworks','homeworks_count'));
    }
}
