<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Routine;
use App\Teacher_has_shift;

class RoutineController extends Controller
{
    public function index()
    {
      // var_dump(date('w'));
      $shifts = Teacher_has_shift::where('user_id', Auth::user()->id)
                                  ->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                                  ->where('is_active', True)
                                  ->get();
      $routines = Routine::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                          ->where('day_id', date('w'))
                          ->where('user_id',Auth::user()->id)
                          ->where('is_active', True)
                          ->with('getTeacherSubjectList')
                          ->orderBy('period_id','ASC')
                          ->get();
      return view('teacher.dailyrecord.routine.index',compact('routines','shifts'));
    }

    public function create()
    {
      //
    }

    public function store(Request $request)
    {
      //
    }

    public function show($id)
    {
      //
    }

    public function edit($id)
    {
      //
    }

    public function update(Request $request,Routine $routine)
    {
      //
    }

    public function destroy(Routine $routine)
    {
      //
    }

    public function getRoutineRecord(Request $request){
        $routines = Routine::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                          ->where('user_id',Auth::user()->id)
                          ->where('is_active', True);
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
      return view('teacher.dailyrecord.routine.index-ajax', compact('routines','date','routines_count'));
    }
}
