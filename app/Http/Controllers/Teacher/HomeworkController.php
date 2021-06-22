<?php

namespace App\Http\Controllers\Teacher;

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
use App\Routine;
use App\Teacher;
use App\Teacher_has_shift;
use App\Teacher_has_period;
use App\Teacher_has_subject;
use Auth;
use Response;

class HomeworkController extends Controller
{
    public function index()
    {
        $shifts = Teacher_has_shift::where('user_id', Auth::user()->id)
                                    ->where('school_id', Auth::user()->school_id)
                                    ->where('batch_id', Auth::user()->batch_id)
                                    ->where('is_active', True)
                                    ->get();
        $dates = $this->helper->date_np_con_parm(date('Y-m-d'));
        $homeworks = Homework::where('school_id', Auth::user()->school_id)
                            ->where('batch_id', Auth::user()->batch_id)
                            ->where('teacher_id', Auth::user()->id)
                            ->orderBy('id', 'DESC')
                            ->where('date', $dates)
                            ->get();
                            // dd($homeworks);
        return view('teacher.dailyrecord.homework.index', compact('homeworks','shifts'));
    }

    public function getAllHomework(Request $request)
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
        $shifts = Teacher_has_shift::where('user_id', Auth::user()->id)
                                    ->where('school_id', Auth::user()->school_id)
                                    ->where('batch_id', Auth::user()->batch_id)
                                    ->where('is_active', True)
                                    ->get();
        return view('teacher.dailyrecord.homework.create', compact('shifts'));
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
        ]);

        $homeworks = Homework::create([
            'class_id' => $request['class_id'],
            'section_id' => $request['section_id'],
            'shift_id' => $request['shift_id'],
            'subject_id' => $request['subject_id'],
            'teacher_id' => Auth::user()->id,
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
        return redirect()->route('teacher.homework.index')->with($pass);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $homeworks = Homework::where('id', $id)
                            ->where('teacher_id', Auth::user()->id)
                            ->where('school_id', Auth::user()->school_id)
                            ->where('batch_id', Auth::user()->batch_id)
                            ->get();
        return view('teacher.dailyrecord.homework.show', compact('homeworks'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $shifts = Teacher_has_shift::where('user_id', Auth::user()->id)
        //                             ->where('school_id', Auth::user()->school_id)
        //                             ->where('batch_id', Auth::user()->batch_id)
        //                             ->where('is_active', True)
        //                             ->get();
        $homeworks = Homework::find($id);
        $subjects = Teacher_has_subject::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                    ->where('teacher_id',$homeworks->teacher_id)
                    ->get();      
                    // dd($homeworks);                      
        return view('teacher.dailyrecord.homework.edit', compact('homeworks','subjects'));
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
        // dd($request);
        $request['updated_by'] = Auth::user()->id;
        if($homework->update($request->all())){
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
        return redirect()->route('teacher.homework.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Homework $homework)
    {
        $homework->where('teacher_id', Auth::user()->id)
                ->where('school_id', Auth::user()->school_id)
                ->where('batch_id', Auth::user()->batch_id);
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
        return Response::json($notification);
    }


    public function isSort(Request $request,$id)
    {
        $sort_ids =  Homework::where('teacher_id', Auth::user()->id)
                            ->where('school_id', Auth::user()->school_id)
                            ->where('batch_id', Auth::user()->batch_id)
                            ->find($request->id);
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
        $get_is_active = Homework::where('id',$id)
                                ->where('teacher_id', Auth::user()->id)
                                ->where('school_id', Auth::user()->school_id)
                                ->where('batch_id', Auth::user()->batch_id)
                                ->value('is_active');
        $isactive = Homework::where('teacher_id', Auth::user()->id)
                            ->where('school_id', Auth::user()->school_id)
                            ->where('batch_id', Auth::user()->batch_id)
                            ->find($id);
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

    public function getHomeworkRecord(Request $request){
        $homeworks = Homework::where('teacher_id', Auth::user()->id)
                            ->where('school_id', Auth::user()->school_id)
                            ->where('batch_id', Auth::user()->batch_id)
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
        // dd($request->date_data);        
          $homeworks = $homeworks->where('date', $request->date_data);
        }

        $homeworks = $homeworks->get();
        // dd($request->date_data);
        // dd($homeworks);

        $homeworks_count = count($homeworks);
        return view('teacher.dailyrecord.homework.index-ajax', compact('homeworks','homeworks_count'));
    }
}
