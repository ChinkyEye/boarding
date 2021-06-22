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
use Auth;
use Validator;
use App\SClass;
use App\Class_has_section;
use Response;


class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function index()
    {
        $exams = Exam::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->get();
        $all_class_section = SClass::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active', True);
        return view('backend.examsection.exam.index', compact('exams','all_class_section'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.examsection.exam.create');
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
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $exams = Exam::create([
            'name' => $request['name'],
            'slug' => $this->helper->slug_converter($request['name']).'-'.rand(1000,9999),
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'school_id' => Auth::user()->school_id,
'batch_id' => Auth::user()->batch_id,
            'created_by' => Auth::user()->id,
            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);
        $pass = array(
            'message' => 'Data added successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.exam.index')->with($pass);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data_id = $id;
        $exams = Exam::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$id)->get();
        $get_class = SClass::whereHas('getExamClass', function (Builder $query) use ($data_id) {
                              $query->where('exam_id', $data_id);
                          })
                        ->get();
        
        return view('backend.examsection.exam.show', compact('exams','get_class'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $exams = Exam::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        return view('backend.examsection.exam.edit', compact('exams'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exam $exam)
    {
        $this->validate($request, [
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $request['updated_by'] = Auth::user()->id;
        if($exam->update($request->all())){
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

        return redirect()->route('admin.exam.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exam $exam)
    {
        // examhas class
        $examhas_class = $exam->getExamClass->each;
        if($examhas_class){
            //exaamhasclass bata markclass ma relation
            foreach ($examhas_class->get() as $key => $value) {
                $examclass = $value->getClassExamMany->each->delete();
            }
            $examhas_class->delete();
            $exam->delete(); 
            $notification = array(
                                    'message' => 'Class has delete successfully!',
                                    'status' => 'success'
                                );
        }else{
            $notification = array(
              'message' => 'Class could not be delete!',
              'status' => 'error'
            );
            return back()->with($notification);
        }
        
        if($exam)
        {
            $exam->delete();
            $notification = array(
                'message' => $exam->name.' is deleted successfully!',
                'status' => 'success'
            ); 
        }
        else{
            $notification = array(
                'message' => $exam->name.' could not be deleted!',
                'status' => 'error'
            );

        }

        return back()->with($notification);
    }
}
