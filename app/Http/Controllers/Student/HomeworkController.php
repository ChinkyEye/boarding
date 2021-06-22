<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Student;
use App\Subject;
use App\Homework;
use Auth;

class HomeworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $student_id = Student::where('user_id', Auth::user()->id)->value('id');
        $student_info = Student::find($student_id);
        $total = '0';
        $homeworks = Homework::where('shift_id',$student_info->shift_id)
                            ->where('class_id', $student_info->class_id)
                            ->where('section_id', $student_info->section_id)
                            ->where('school_id', Auth::user()->school_id)
                            ->where('batch_id', Auth::user()->batch_id)
                            ->orderBy('id','DESC');
        $subjects = Subject::where('class_id', $student_info->class_id)
                            ->where('school_id', Auth::user()->school_id)
                            ->where('batch_id', Auth::user()->batch_id)
                            ->orderBy('id','DESC');                    
        if($request->date != ''){
            $homeworks = $homeworks->where('date_eng',$request->date);
        }
        if($request->search != ''){
            $search = $request->search;
            $homeworks = $homeworks->where('subject_id',$search);
        }
        $total = $homeworks->count();

        $homeworks = $homeworks->with('getTeacher','getSubject','getShift')->get();
        $subjects = $subjects->with('getUser')->get();


        $response = [
            'homeworklist' => $homeworks,
            'totallist' => $total,
            'subjectlist' => $subjects,
        ];
        return response()->json($response);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // dd($request, $id);
        $student_id = Student::where('user_id', Auth::user()->id)->value('id');
        $student_info = Student::find($student_id);
        // $user_id = Auth::user()->id;

        $homeworks = Homework::where('shift_id',$student_info->shift_id)
                    ->where('class_id', $student_info->class_id)
                    ->where('section_id', $student_info->section_id)
                    ->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                    ->where('id',$id)
                    ->orderBy('id','DESC');
        $homeworks = $homeworks->with('getUser')->get();          

        $response = [
            'homeworks' => $homeworks,
        ];
        return response()->json($response);
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
